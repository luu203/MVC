<?php 
// Inclusion des fichiers nécessaires pour la connexion et le modèle
require_once 'connexion.php';
require_once 'modele.php';

// Création de l'objet Database et connexion à la base de données
$ddb = new Database();
$connect = $ddb->connect();

// Vérifier si une annonce doit être supprimée
if (isset($_POST['id_annonce'])) {
    // Récupérer l'ID de l'annonce à supprimer
    $id_annonce = $_POST['id_annonce'];

    // Requête pour supprimer l'annonce
    $query = "DELETE FROM annonces WHERE id_annonce = :id_annonce";
    $stmt = $connect->prepare($query);
    $stmt->bindParam(':id_annonce', $id_annonce, PDO::PARAM_INT);

    // Exécuter la suppression
    if ($stmt->execute()) {
        echo "<p>Annonce supprimée avec succès.</p>";
    } else {
        echo "<p>Erreur lors de la suppression de l'annonce.</p>";
    }
}

// Requête pour récupérer toutes les annonces depuis la base de données
$query = "SELECT * FROM annonces";
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
    <link rel="stylesheet" type="text/css" href="annonce.css">
    <title>Liste des Annonces</title>
</head>
<body>
    <!-- Lien vers la page d'ajout d'annonce -->
    <a href="add_annonce.html"><input type="button" value="Ajouter une annonce"></a>

    <div class="annonces">
        <?php
        // Affichage des annonces
        if ($annonces) {
            foreach ($annonces as $annonce) {
                // Sécurisation des sorties pour éviter les problèmes de sécurité
                $titre = htmlspecialchars($annonce['titre']);
                $description = htmlspecialchars($annonce['description']);
                $annonce_image = $annonce['annonce_image']; // Récupération de l'image en BLOB
                $id_annonce = $annonce['id_annonce']; // Récupération de l'ID de l'annonce

                echo '<div class="annonce-item">';
                echo '<h2>' . $titre . '</h2>';
                echo '<p>' . $description . '</p>';

                // Affichage de l'image si elle existe
                if ($annonce_image) {
                    $image_base64 = base64_encode($annonce_image);  // Conversion du BLOB en base64 pour l'affichage
                    echo '<img src="data:image/jpeg;base64,' . $image_base64 . '" alt="Image de l\'annonce" style="max-width: 100%; height: auto;">';
                }

                // Formulaire pour supprimer une annonce
                echo '<form method="POST" action="">';  // Action vide pour soumettre sur la même page
                echo '<input type="hidden" name="id_annonce" value="' . $id_annonce . '">'; // Champ caché avec l'ID
                echo '<input type="submit" value="Supprimer">'; // Bouton pour supprimer l'annonce
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo '<p>Aucune annonce trouvée.</p>';
        }
        ?>
    </div>
</body>
</html>