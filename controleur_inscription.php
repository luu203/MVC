<?php

require 'connexion.php'; // Assurez-vous que ce fichier est correctement inclus

// Vérification que les champs nécessaires sont envoyés via POST
if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm-password"]) && isset($_POST["email"]) 
    && isset($_POST["adresse"]) && isset($_POST["code-postal"]) && isset($_POST["ville"])) {

    // Vérification que le mot de passe et la confirmation du mot de passe correspondent
    if ($_POST["password"] !== $_POST["confirm-password"]) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Vérifier si l'email existe déjà
    $database = new Database();
    $conn = $database->connect();
    $email = $_POST["email"];
    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "L'email est déjà utilisé.";
        exit;
    }

    // Créer une instance de la classe Database et se connecter à la base
    $nom = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $adresse = $_POST["adresse"];
    $code_postal = $_POST["code-postal"];
    $ville = $_POST["ville"];

    // Préparer la requête SQL pour insérer l'utilisateur
    $query = "INSERT INTO utilisateurs (username, password, email, adresse, code_postal, ville) 
              VALUES (:username, :password, :email, :adresse, :code_postal, :ville)";

    // Préparer la requête
    $stmt = $conn->prepare($query);

    // Exécuter la requête avec les valeurs
    $stmt->execute([
        ':username' => $nom, 
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':email' => $email,
        ':adresse' => $adresse,
        ':code_postal' => $code_postal,
        ':ville' => $ville
    ]);

    // Rediriger vers la page d'accueil après l'inscription
    header('Location: page_accueil.php');
    exit;

} else {
    // Afficher un message d'erreur si les champs sont vides
    echo "Veuillez remplir tous les champs.";
}
?>
