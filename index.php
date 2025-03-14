<?php
include("db/db.php");

// Requête pour compter le nombre d'inscrits
$sql = "SELECT COUNT(id) AS total FROM utilisateurs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_inscrits = $row["total"];
} else {
    $total_inscrits = 0;
}

// Affichage


$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <style>
     body {
    font-family: 'Arial', sans-serif;
    background: url('https://www.ducatindia.com/_next/image?url=https%3A%2F%2Fadmin.ducatindia.com%2Fblog%2F1722404581401How%20to%20Transpose%20a%20Matrix%20in%20C.webp&w=1920&q=75') no-repeat center center fixed;
    background-size: cover; 
    color: #d4d4d4; 
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    }

    .container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
            text-align: center;
            width: 350px;
            display: grid;
    }
    a{
        width: auto;
        padding: 10px;
        background-color: #121212;
        color: #00ff00;
        border-radius: 5px;
        font-size: 14px;
        outline: none;
        background-color: transparent;
        border: 2px solid #00ff00;
        box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        color: #00ff00;
        text-decoration: none;
        font-weight: bold;
    }
    a:hover{
        background-color: rgba(0, 255, 0, 0.5) ;
        color: whitesmoke;
    }

        
    </style>
</head>
<body>

<!-- Profil utilisateur -->
<div class="container">
    <h1>Ghost 👻</h1>
   <h3><?php  echo "Nombre total d'inscrits : $total_inscrits"; ?></h3>
    <a href="connexion.php">Se connecter </a>
    <br>
    <a href="Inscription.php">S'inscrire</a>
</div>


</body>
</html>