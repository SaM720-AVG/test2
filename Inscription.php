<?php
// Connexion à la base de données
$servername = "localhost"; // Changez si nécessaire
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe
$dbname = "sam1"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérifier si l'email existe déjà
    $stmt = $conn->prepare("SELECT email FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Cet email est déjà utilisé.";
    } else {
        // Préparer et lier
        $stmt = $conn->prepare("INSERT INTO utilisateurs (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user, $email, $pass);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Inscription réussie !";
            // Redirection vers une page protégée ou un tableau de bord
            header("Location: ./C.Accueil.html");
            exit();
        } else {
            echo "Erreur: " . $stmt->error;
        }
    }

    // Fermer la déclaration
    $stmt->close();
}

$conn->close();
?>

