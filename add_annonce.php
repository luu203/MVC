<?php
session_start(); // Démarre la session
require_once 'connexion.php'; // Inclusion du fichier de connexion

// Vérifier si l'utilisateur est connecté (si la clé 'user_id' existe dans la session)
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo "Vous devez être connecté pour ajouter une annonce.";
    exit;
}

// Récupérer l'ID utilisateur de la session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    echo "Erreur: ID utilisateur non défini dans la session.";
    exit;
}

// Connexion à la base de données
$database = new Database();
$conn = $database->connect();

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titre'], $_POST['description'], $_POST['category'])) {
    // Récupération des données envoyées par le formulaire
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $contrainte = isset($_POST['contrainte']) ? $_POST['contrainte'] : null;

    // Vérification et traitement de l'image
    $annonce_image = null;
    if (isset($_FILES['annonce_image']) && $_FILES['annonce_image']['error'] == 0) {
        // Récupérer l'image
        $image_tmp = $_FILES['annonce_image']['tmp_name'];
        $image_data = file_get_contents($image_tmp); // Lire le contenu du fichier

        // Optionnel : Vérifier si le fichier est une image (par exemple, un JPEG ou PNG)
        $image_type = mime_content_type($image_tmp);
        if (in_array($image_type, ['image/jpeg', 'image/png', 'image/gif'])) {
            $annonce_image = $image_data; // L'image sera insérée sous forme de BLOB
        } else {
            echo "Le fichier n'est pas une image valide.";
            exit;
        }
    }

    // Insertion dans la base de données
    try {
        $query = "INSERT INTO annonces (titre, description, contrainte, category, id_utilisateur, annonce_image) 
                  VALUES (:titre, :description, :contrainte, :category, :id_utilisateur, :annonce_image)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':contrainte' => $contrainte,
            ':category' => $category,
            ':id_utilisateur' => $user_id,
            ':annonce_image' => $annonce_image // Insertion de l'image dans la base de données
        ]);

        echo "Annonce ajoutée avec succès!";
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de l'annonce : " . $e->getMessage();
    }
}
?>
