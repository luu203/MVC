<?php
session_start(); // Démarre la session

// Détruire toutes les variables de session
$_SESSION = array();

// Si vous voulez vraiment supprimer la session, utilisez session_destroy()
session_destroy();

// Rediriger vers la page d'accueil après la déconnexion
header('Location: page_accueil.php');
exit;
?>