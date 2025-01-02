<?php 
session_start();  // Démarre la session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $nom_utilisateur = $_SESSION['username']; 
}

// Inclusion du modèle (par exemple pour les données de la base de données ou autre logique)
require 'modele.php';

// Connexion à la base de données
$ddb = new Database();
$connect = $ddb->connect();

// Récupérer les 6 dernières annonces ajoutées (par date décroissante)
$query = "
    SELECT a.*, u.username 
    FROM annonces a
    INNER JOIN utilisateurs u ON a.id_utilisateur = u.id
    ORDER BY a.Date_annonce DESC
    LIMIT 6
";
$query = $connect->prepare($query);
$query->execute();

// Récupération des résultats sous forme de tableau associatif
$annonces = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="page_accueil.css">
    <title>Page d'Accueil</title>
</head>
<body>
    <div class="container">
        <!-- Section menu -->
        <div class="flex">
            <div class="flex1 flex">
                <div class="menu">
                    <div onclick="toggleMenu()">
                        <img src="menu.png" class="menuImage" />
                    </div>
                    <div class="menu-content" id="menuContent">
                        <a href="#">Categories appartements</a>
                        <a href="#">Maisons</a>
                        <a href="#">Villes</a>
                    </div>
                </div>
                <div><img class="logo" src="logo.png" /></div>
            </div>

            <div class="flex6">
                <div class="search-container">
                    <input class="input" placeholder="Rechercher" />
                    <img class="magnifierImage" src="magnifier.png" />
                </div>
                <div class="tab">
                    <?php 
                    // Affichage des liens de l'utilisateur si connecté
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
                        <a href="page_annonce.php" class="width20">Mes annonces</a>
                        <a href="coup_decoeur.html" class="width20">Coup de cœur</a>
                        <a href="profil.php" class="width20">Profil</a>
                    <?php } ?>

                    <a href="filtre.html" class="width20">Filtre</a>
                </div>
            </div>

            <div class="flex1 flex auth-links">
                <div>
                    <img src="information.png" class="width30" />
                    
                    <a href="messagerie.php">Messagerie</a>
                </div>

                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <div>
                        <img src="exit.png" class="width30" />
                        <a href="logout.php" class="rightText">Déconnexion</a>
                    </div>
                <?php else: ?>
                    <div>
                        <img src="exit.png" class="width30" />
                        <a href="page_login.html" class="rightText">Login</a>
                    </div>

                    <div>
                        <img src="img/exit.png" class="width30" />
                        <a href="page_inscription.html" class="rightText">Inscription</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Message de bienvenue -->
        <div class="welcome-message-container">
            <?php if (isset($nom_utilisateur)): ?>
                <div class="welcome-message">
                    <span>Bienvenue, <?php echo htmlspecialchars($nom_utilisateur); ?> !</span>
                </div>
            <?php endif; ?>
        </div>
        <a href="messagerie_recent.php">Voir vos messages récents</a>

        <!-- Section des 6 dernières annonces -->
        <div class="recent-annonces">
            
            <div class="annonce-container">
                <?php 
                // Afficher les 6 dernières annonces
                if ($annonces) {
                    foreach ($annonces as $annonce) {
                        // Sécurisation des sorties
                        $titre = htmlspecialchars($annonce['titre']);
                        $description = htmlspecialchars($annonce['description']);
                        $annonce_image = $annonce['annonce_image']; // Image en BLOB
                        $id_annonce = $annonce['id_annonce']; // ID de l'annonce
                        $username = htmlspecialchars($annonce['nom_utilisateur']); // Nom d'utilisateur
                        ?>
                        <div class="annonce-item">
                            <h3><?php echo $titre; ?></h3>
                            <p>Posté par : <?php echo $username; ?></p>
                            <p><?php echo $description; ?></p>
                            <?php 
                            if ($annonce_image) {
                                $image_base64 = base64_encode($annonce_image);  // Conversion en base64 pour l'affichage
                                echo '<img src="data:image/jpeg;base64,' . $image_base64 . '" alt="Image de l\'annonce" class="annonce-image">';
                            }
                            ?>
                            <a href="annonce_details.php?id=<?php echo $id_annonce; ?>" class="view-details">Voir plus</a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>Aucune annonce trouvée.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
