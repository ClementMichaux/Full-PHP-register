<?php
    // Connexion à la DB
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=mydb;charset=utf8', 'root', '');
    } catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
    }
?>
