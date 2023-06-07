<?php session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include ("connexion.php") ?>
    <?php
 // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['connected_id'])) {
        // L'utilisateur est connecté, déconnectez-le
        session_unset(); // Supprimer toutes les variables de session
        session_destroy(); // Détruire la session
        // Rediriger vers la page de connexion ou toute autre page de votre choix
        header("Location: login.php");
        exit(); // Arrêter l'exécution du script
    }
    ?>







