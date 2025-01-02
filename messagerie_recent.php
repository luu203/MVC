<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Vous devez être connecté pour voir vos messages récents.");
}

$username = $_SESSION['username'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=messagerie", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les messages récents
$stmt = $pdo->prepare("SELECT sender, message, timestamp 
                       FROM messages 
                       WHERE receiver = ? 
                       ORDER BY timestamp DESC 
                       LIMIT 10");
$stmt->execute([$username]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages récents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .messages-container {
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .message-box p {
            margin: 5px 0;
        }
        .message-box small {
            display: block;
            text-align: right;
            color: #888;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Vos messages récents</h1>
    <?php if (!empty($messages)): ?>
        <div class="messages-container">
            <?php foreach ($messages as $msg): ?>
                <div class="message-box">
                    <p><strong>De : <?php echo htmlspecialchars($msg['sender']); ?></strong></p>
                    <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                    <small><?php echo htmlspecialchars($msg['timestamp']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align:center;">Aucun message disponible.</p>
    <?php endif; ?>
</body>
</html>


