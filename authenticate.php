<?php
// authenticate.php - Traitement de la connexion

require_once 'config.php';
require_once 'db_connect.php';

// Démarrer la session
session_start();

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Récupérer les données du formulaire
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Valider les données
$errors = [];

if (empty($email)) {
    $errors[] = "L'adresse email est requise";
}

if (empty($password)) {
    $errors[] = "Le mot de passe est requis";
}

// S'il y a des erreurs, rediriger vers login avec message
if (!empty($errors)) {
    $_SESSION['error'] = implode(', ', $errors);
    header('Location: login.php');
    exit;
}

// Vérifier les identifiants dans la base de données
$user = fetchOne(
    $conn,
    "SELECT id, username, email, password FROM users WHERE email = ? AND is_active = TRUE",
    [$email],
    "s"
);

if ($user && password_verify($password, $user['password'])) {
    // Connexion réussie
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['logged_in'] = true;
    
    debug_log("Connexion réussie pour: " . $user['email']);
    
    // Rediriger vers la page du chat
    header('Location: chat.php');
    exit;
} else {
    // Identifiants invalides
    debug_log("Tentative de connexion échouée pour: " . $email);
    $_SESSION['error'] = "Email ou mot de passe incorrect";
    header('Location: login.php');
    exit;
}

$conn->close();
?>
