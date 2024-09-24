<?php
// Démarrer la session
session_start();

// Connexion à la base de données
$servername = "localhost"; // Changez si nécessaire
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe
$dbname = "sam1"; // Remplacez par le nom de votre base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection échouée : " . $conn->connect_error);
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    // Préparer et exécuter la requête pour trouver l'utilisateur
    $stmt = $conn->prepare("SELECT motdepasse FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Vérifier si l'utilisateur existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($motdepasse_hache);
        $stmt->fetch();

        // Vérifier le mot de passe
        if (password_verify($motdepasse, $motdepasse_hache)) {
            // Mot de passe correct, créer une session
            $_SESSION['email'] = $email;
            echo "Connexion réussie !";
            // Redirection vers une page protégée ou un tableau de bord
            header("Location: ./C.Accueil.html");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }

    // Fermer la connexion
    $stmt->close();
}

$conn->close();
?>
