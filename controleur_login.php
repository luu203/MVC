<?php
session_start();
require_once 'connexion.php';

// Affichage des erreurs pour faciliter le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Connexion à la base de données réussie"; // Pour vérifier si on arrive jusqu'ici

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $database = new Database();
    $conn = $database->connect();

    echo "Connexion à la base de données établie"; // Vérifiez que la connexion a réussi

    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer la requête SQL pour sélectionner l'utilisateur
    $stmt = $conn->prepare("SELECT id, username, password FROM utilisateurs WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_password = $user_data['password'];

        if (password_verify($password, $stored_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['user_id'] = $user_data['id']; // Enregistrer l'ID utilisateur dans la session

            // Redirection vers la page d'accueil après connexion
            header('Location: page_accueil.php');
            exit; // Important pour arrêter l'exécution après redirection
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>