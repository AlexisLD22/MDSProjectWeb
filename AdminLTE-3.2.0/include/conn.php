<?php

$host = "localhost";
$username = "root";
$password = "root";
$database = "toiletagecanin";

// Connexion avec la base de données
$conn = new mysqli($host, $username, $password, $database);

// On vérifie si la connexion s'est sans soucis.
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>