<?php
session_start(); 
include("pages/header.php");
include("db/db.php");

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST["pseudo"]);
    $password = trim($_POST["password"]);

    if (empty($pseudo) || empty($password)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        
        $stmt = $conn->prepare("SELECT id, password FROM utilisateurs WHERE pseudo = ?");
        $stmt->bind_param("s", $pseudo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $_SESSION["pseudo"] = $pseudo; 
                $_SESSION["user_id"] = $row["id"]; 
                header("Location: profile.php"); 
                exit();
            } else {
                $message = "Mot de passe incorrect.";
            }
        } else {
            $message = "Pseudo introuvable.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

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
        background-color:black; 
        padding: 25px 50px 25px 50px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 255, 0, 0.5); 
        width: auto;
        text-align: center;
        border: 2px solid #00ff00;
    }
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
    
   
    .input {
        margin-bottom: 15px;
        text-align: left;
    }
    
    .input label {
        display: block;
        color: whitesmoke; 
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
        background-color: whitesmoke;
        border: 2px solid #00ff00;
        box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
    }
    
    .input input::placeholder {
        color: #777;
    }
    
    
    span[style="color: red;"] {
        color: #ff0000 !important;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }
    
   
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
    
    
    .input input:focus {
        box-shadow: 0 0 10px #00ff00;
    }
    
</style>
<section class="formulaire">
    <form action="" method="post">
        <?php if (!empty($message)) { echo "<p style='color: red;'>$message</p>"; } ?>
        
        <div class="se_connecter">
            <span>J'ai pas encore de compte, <a href="inscription.php">m'inscrire maintenant 👻☠️</a></span>
        </div>

        <div class="input">
            <label for="pseudo">Pseudo 👻:</label>
            <input type="text" id="pseudo" name="pseudo" placeholder="Entrez un pseudonyme" required>
        </div>

        <div class="input">
            <label for="password">Mot de passe 🤫:</label>
            <input type="password" id="password" name="password" placeholder="Entrez un mot de passe" required>
        </div>

        <div class="input_button">
            <input type="submit" value="Se connecter">
        </div>
    </form>
</section>

<?php
include("pages/footer.php")
?>