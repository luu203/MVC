<?php

require_once 'connexion.php';

class Modele {
    
    private $db;

    public function __construct(){
        $this->db = new Database(); // Correction de l'instanciation de Database
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: page_accueil.php'); // Correction de la syntaxe de Location
        exit(); // Ajout de exit() après la redirection pour éviter l'exécution du code suivant
    }
}
?>
