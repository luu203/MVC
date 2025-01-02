<?php 
session_start();  // Démarre la session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $nom_utilisateur = $_SESSION['username']; 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profil.css">
    <title>Mon Profil</title>

    <script type="text/javascript">
        // Fonction qui demande confirmation avant de sauvegarder et redirige vers la page d'accueil
        function confirmSave() {
            var result = confirm("Êtes-vous sûr de vouloir changer vos données ?");
            if (result) {
                // Si l'utilisateur confirme, on peut soumettre le formulaire et rediriger
                document.getElementById("profilForm").submit(); // Soumet le formulaire
                setTimeout(function() { window.location.href = "page_accueil.php"; }, 10); // Redirige après 1 seconde
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Mon Profil</h2>
        <form id="profilForm" action="controleur_profil.php" method="POST">
            <!-- Champs de profil -->
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($nom_utilisateur); ?>" disabled>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="exemple@email.com" required>

            <label for="password">Nouveau mot de passe:</label>
            <input type="password" id="password" name="password">

            <label for="confirm-password">Confirmer le mot de passe:</label>
            <input type="password" id="confirm-password" name="confirm-password">

            <label for="adresse">Adresse:</label>
            <input type="text" id="adresse" name="adresse" value="123 Rue Exemple" required>

            <label for="ville">Ville:</label>
            <input type="text" id="ville" name="ville" value="Paris" required>

            <label for="code-postal">Code Postal:</label>
            <input type="number" id="code-postal" name="code-postal" value="75000" required>

            <!-- Bouton Sauvegarder -->
            <button type="button" onclick="confirmSave()">Sauvegarder</button>
        </form>
        
        <!-- Message de bienvenue -->
        <div class="welcome-message">
            <?php if (isset($nom_utilisateur)): ?>
                <span>Bienvenue, <?php echo htmlspecialchars($nom_utilisateur); ?> !</span>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
