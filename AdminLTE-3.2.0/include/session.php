<?php

session_start(); // Créer une session ou reprends l'ancienne.

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    //Dans le cas ou la connexion n'existe pas renvoie vers la page login.php
    header("Location: login.php");
    exit();
}
?>