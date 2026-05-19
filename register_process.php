<?php
// register_process.php - Traitement de l'inscription

require_once 'config.php';
require_once 'db_connect.php';

// Démarrer la session
session_start();

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Récupérer les données du formulaire
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

// Valider les données
$errors = [];

if (empty($username)) {
    $errors[] = "Le nom d'utilisateur est requis";
} elseif (strlen($username) < 3) {
    $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères";
} elseif (strlen($username) > 50) {
    $errors[] = "Le nom d'utilisateur ne peut pas dépasser 50 caractères";
}

if (empty($email)) {
    $errors[] = "L'adresse email est requise";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'adresse email n'est pas valide";
}

if (empty($password)) {
    $errors[] = "Le mot de passe est requis";
} elseif (strlen($password) < 6) {
    $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
}

if ($password !== $confirm_password) {
    $errors[] = "Les mots de passe ne correspondent pas";
}

// S'il y a des erreurs, rediriger vers register avec message
if (!empty($errors)) {
    $_SESSION['error'] = implode(', ', $errors);
    header('Location: register.php');
    exit;
}

// Vérifier que l'email n'existe pas déjà
$existing_email = fetchOne(
    $conn,
    "SELECT id FROM users WHERE email = ?",
    [$email],
    "s"
);

if ($existing_email) {
    $_SESSION['error'] = "Cet email est déjà utilisé";
    header('Location: register.php');
    exit;
}

// Vérifier que le nom d'utilisateur n'existe pas déjà
$existing_username = fetchOne(
    $conn,
    "SELECT id FROM users WHERE username = ?",
    [$username],
    "s"
);

if ($existing_username) {
    $_SESSION['error'] = "Ce nom d'utilisateur est déjà utilisé";
    header('Location: register.php');
    exit;
}

// Hacher le mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insérer l'utilisateur dans la base de données
$stmt = $conn->prepare("INSERT INTO users (username, email, password, is_active) VALUES (?, ?, ?, TRUE)");

if (!$stmt) {
    debug_log("Erreur de préparation: " . $conn->error);
    $_SESSION['error'] = "Une erreur est survenue lors de l'inscription";
    header('Location: register.php');
    exit;
}

$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
    $stmt->close();
    debug_log("Nouvel utilisateur créé: " . $email);
    $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    header('Location: login.php');
    exit;
} else {
    debug_log("Erreur lors de l'insertion: " . $stmt->error);
    $_SESSION['error'] = "Une erreur est survenue lors de l'inscription";
    header('Location: register.php');
    exit;
}

$conn->close();
?>
