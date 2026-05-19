<?php
// send_message.php - Envoyer un message au chat

require_once 'config.php';
require_once 'db_connect.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: chat.php');
    exit;
}

// Récupérer le message et le destinataire
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$recipient_id = isset($_POST['recipient_id']) ? trim($_POST['recipient_id']) : '';
$user_id = $_SESSION['user_id'];

// Valider le message
if (empty($message)) {
    $_SESSION['error'] = "Le message ne peut pas être vide";
    header('Location: chat.php');
    exit;
}

if (strlen($message) > 5000) {
    $_SESSION['error'] = "Le message ne peut pas dépasser 5000 caractères";
    header('Location: chat.php');
    exit;
}

// Valider le destinataire si nécessaire
$recipient_id = ($recipient_id === '') ? null : intval($recipient_id);

if ($recipient_id !== null) {
    $recipient = fetchOne(
        $conn,
        "SELECT id FROM users WHERE id = ? AND is_active = TRUE",
        [$recipient_id],
        "i"
    );

    if (!$recipient) {
        $_SESSION['error'] = "Le destinataire sélectionné est invalide";
        header('Location: chat.php');
        exit;
    }
}

// Insérer le message dans la base de données
if ($recipient_id === null) {
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("is", $user_id, $message);
    }
} else {
    $stmt = $conn->prepare("INSERT INTO messages (user_id, recipient_user_id, message) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iis", $user_id, $recipient_id, $message);
    }
}

if (!$stmt) {
    debug_log("Erreur de préparation: " . $conn->error);
    $_SESSION['error'] = "Une erreur est survenue lors de l'envoi du message";
    header('Location: chat.php');
    exit;
}

if ($stmt->execute()) {
    $stmt->close();
    debug_log("Message envoyé par l'utilisateur " . $user_id);
    header('Location: chat.php');
    exit;
} else {
    debug_log("Erreur lors de l'insertion: " . $stmt->error);
    $_SESSION['error'] = "Une erreur est survenue lors de l'envoi du message";
    header('Location: chat.php');
    exit;
}

$conn->close();
?>
