<?php
include("db/db.php");
  // Vérifier si un like a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["article_id"])) {
    $article_id = intval($_POST["article_id"]);

    // Mettre à jour le nombre de likes
    $stmt = $conn->prepare("UPDATE articles SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    
    if ($stmt->execute()) {
        $message = "Like ajouté !";
    } else {
        $message = "Erreur lors du like.";
    }

    $stmt->close();
}


?>