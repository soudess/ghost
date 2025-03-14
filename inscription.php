<?php
session_start(); // Doit être au début
include("pages/header.php");
include("db/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST["pseudo"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Vérification des champs
    if (empty($pseudo) || empty($password) || empty($confirm_password)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (strlen($password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si le pseudo existe déjà
        $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE pseudo = ?");
        $stmt->bind_param("s", $pseudo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Ce pseudo est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Insérer l'utilisateur
            $stmt = $conn->prepare("INSERT INTO utilisateurs (pseudo, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $pseudo, $password_hash);

            if ($stmt->execute()) {
                header("Location: connexion.php?success=1");
                exit();
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!-- CSS amélioré -->
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

    
    .formulaire {
        background-color: black; 
        padding: 25px 50px 25px 50px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 255, 0, 0.5); 
        width: auto;
        text-align: center;
        border: 2px solid #00ff00;
    }

    /* Texte de connexion */
    .se_connecter span {
        color: #00ff00;
        font-weight: bold;
        display: block;
        margin-bottom: 15px;
    }

    .se_connecter a {
        color: #00ff00;
        text-decoration: none;
        font-weight: bold;
    }

    .se_connecter a:hover {
        text-decoration: underline;
    }

    /* Champs du formulaire */
    .input {
        margin-bottom: 15px;
        text-align: left;
    }

    .input label {
        display: block;
        color: #d4d4d4;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .input input {
        width: 100%;
        padding: 10px;
        background-color: #121212;
        color: #00ff00;
        border-radius: 5px;
        font-size: 14px;
        outline: none;
        border: 2px solid #00ff00;
        box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
    }

    .input input::placeholder {
        color: #777;
    }

    /* Messages d'erreur */
    span[style="color: red;"] {
        color: #ff0000 !important;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }

    /* Bouton */
    .input_button input {
        width: auto;
        background-color: #00ff00;
        color: #000;
        padding: 10px;
        border: none;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 5px;
        transition: 0.3s ease-in-out;
    }

    .input_button input:hover {
        background-color: #008000;
    }

    /* Effet néon */
    .input input:focus {
        box-shadow: 0 0 10px #00ff00;
    }
</style>
<!-- Formulaire HTML -->
<section class="formulaire">
    <form action="" method="post">
    <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
        <div class="se_connecter">
            <span>J'ai déjà un compte, me connecter maintenant 👻☠️ <a href="connexion.php">Se connecter</a></span>
        </div>

        <div class="input">
            <label for="pseudo">Pseudo 👻:</label>
            <input type="text" id="pseudo" name="pseudo" placeholder="Entrez un pseudonyme" required>
        </div>

        <div class="input">
            <label for="password">Mot de passe 🤫:</label>
            <input type="password" id="password" name="password" placeholder="Entrez un mot de passe" required>
        </div>

        <div class="input">
            <label for="confirm_password">Confirmez le mot de passe 🔐:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
        </div>

        <div class="input_button">
            <input type="submit" name="inscription" value="S'inscrire">
        </div>
    </form>
</section>

<?php
include("pages/footer.php");
?>
