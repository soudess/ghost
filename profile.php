<?php
session_start();
include("db/db.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["pseudo"])) {
    header("Location: connexion.php");
    exit();
}

$message = "";

// Publier un article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["publier"])) {
    $titre = trim($_POST["titre"]);
    $contenu = trim($_POST["contenu"]);
    $utilisateur = $_SESSION["pseudo"];

    if (empty($titre) || empty($contenu)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $conn->prepare("INSERT INTO articles (utilisateur, titre, contenu) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $utilisateur, $titre, $contenu);
        if ($stmt->execute()) {
            $message = "Article publié avec succès !";
        } else {
            $message = "Erreur lors de la publication.";
        }
        $stmt->close();
    }
}

// Ajouter un like
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["like"])) {
    $article_id = intval($_POST["article_id"]);
    $stmt = $conn->prepare("UPDATE articles SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $stmt->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["dislike"])) {
    $article_id = intval($_POST["article_id"]);
    $stmt = $conn->prepare("UPDATE articles SET dislikes = dislikes + 1  WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $stmt->close();
}

// Récupérer les articles
$result_articles = $conn->query("SELECT * FROM articles ORDER BY date_publication DESC");

// Ajouter un message au chat
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["envoyer"])) {
    $utilisateur = $_SESSION["pseudo"];
    $msg = trim($_POST["message"]);

    if (!empty($msg)) {
        $stmt = $conn->prepare("INSERT INTO messages (utilisateur, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $utilisateur, $msg);
        $stmt->execute();
        $stmt->close();
    }
}

// Récupérer les messages du chat
$result_messages = $conn->query("SELECT * FROM messages ORDER BY date_envoi DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://www.ducatindia.com/_next/image?url=https%3A%2F%2Fadmin.ducatindia.com%2Fblog%2F1722404581401How%20to%20Transpose%20a%20Matrix%20in%20C.webp&w=1920&q=75') no-repeat center center fixed;
            background-size: cover;
            color: #d4d4d4;
            margin: 20px;
            display: flex;
            justify-content: space-evenly;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
            text-align: center;
            width: 350px;
        }
        textarea, input[type="text"] {
            width: 90%;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        input[type="submit"], button {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            background: #00ff00;
            color: black;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover, button:hover {
            background: #008000;
        }
        .article {
            background: black;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 2px solid #00ff00;
        }
        .chat-box {
            background: #333;
            padding: 10px;
            border-radius: 10px;
            height: 250px;
            overflow-y: auto;
        }
        .message {
            padding: 5px;
            border-bottom: 1px solid #444;
        }
        .message strong {
            color: #00ff00;
        }
    </style>
</head>
<body>

<!-- Publication d'articles -->
<div class="container">
    <h3>Publier un article</h3>
    <form action="" method="post">
        <input type="text" name="titre" placeholder="Titre de l'article" required>
        <textarea name="contenu" rows="4" placeholder="Écrivez votre article ici..." required></textarea>
        <input type="submit" name="publier" value="Publier">
    </form>

    <h3>Articles publiés</h3>
    <?php while ($row = $result_articles->fetch_assoc()) : ?>
        <div class="article">
            <h4><?php echo htmlspecialchars($row["titre"]); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($row["contenu"])); ?></p>
            <small>Publié par <?php echo htmlspecialchars($row["utilisateur"]); ?> le <?php echo $row["date_publication"]; ?></small>
            <form action="" method="post">
                <input type="hidden" name="article_id" value="<?php echo $row["id"]; ?>">
                <button type="submit" name="like">👍 Like (<?php echo $row["likes"]; ?>)</button>
            </form>
            <form action="" method="post">
                <input type="hidden" name="article_id" value="<?php echo $row["id"]; ?>">
                <button type="submit" name="dislike">👎 dislike (<?php echo $row["dislikes"]; ?>)</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>

<!-- Profil utilisateur -->
<div class="container">
    <h2>Bienvenue, <?php echo $_SESSION["pseudo"]; ?> 👻</h2>
    <p>Pseudo: <?php echo $_SESSION["pseudo"]; ?></p>
    <style>
        a{
        color: #00ff00;
        text-decoration: none;
        font-weight: bold;
        }
    </style>
    <a href="logout.php">Se Déconnecter</a>
    <br>
    <a href="publier.php">Publier un Gbairai</a>
</div>

<!-- Chat public -->
<div class="container">
    <h3>Chat Public</h3>
    <div class="chat-box">
        <?php while ($row = $result_messages->fetch_assoc()) : ?>
            <div class="message">
                <strong><?php echo htmlspecialchars($row["utilisateur"]); ?></strong>: 
                <?php echo nl2br(htmlspecialchars($row["message"])); ?>
                <br>
                
            </div>
        <?php endwhile; ?>
    </div>

    <form action="" method="post">
        <textarea name="message" placeholder="Écris un message..." required></textarea>
        <input type="submit" name="envoyer" value="Envoyer">
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
