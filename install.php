<?php
// install.php - Script d'installation de la base de données

require_once 'config.php';

$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$dbname = DB_NAME;

// Connexion à MySQL sans sélectionner de base de données
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

echo "<h2>Installation de la base de données Chat App</h2>";
echo "<hr>";

// Créer la base de données
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`;";
if ($conn->query($sql) === TRUE) {
    echo "✓ Base de données créée/vérifiée avec succès.<br>";
} else {
    echo "✗ Erreur lors de la création de la base de données: " . $conn->error . "<br>";
    exit;
}

// Sélectionner la base de données
if (!$conn->select_db($dbname)) {
    echo "✗ Erreur lors de la sélection de la base de données: " . $conn->error . "<br>";
    exit;
}

// Créer la table des utilisateurs
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql_users) === TRUE) {
    echo "✓ Table 'users' créée/vérifiée avec succès.<br>";
} else {
    echo "✗ Erreur lors de la création de la table users: " . $conn->error . "<br>";
}

// Créer la table des messages
$sql_messages = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipient_user_id INT DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_recipient_user_id (recipient_user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql_messages) === TRUE) {
    echo "✓ Table 'messages' créée/vérifiée avec succès.<br>";
} else {
    echo "✗ Erreur lors de la création de la table messages: " . $conn->error . "<br>";
}

// Ajouter le champ recipient_user_id si la table existe sans cette colonne
$result = $conn->query("SHOW COLUMNS FROM messages LIKE 'recipient_user_id'");
if ($result && $result->num_rows === 0) {
    $sql_add_column = "ALTER TABLE messages ADD COLUMN recipient_user_id INT DEFAULT NULL AFTER user_id";
    if ($conn->query($sql_add_column) === TRUE) {
        echo "✓ Colonne 'recipient_user_id' ajoutée à 'messages'.<br>";
    } else {
        echo "✗ Erreur lors de l'ajout de la colonne recipient_user_id: " . $conn->error . "<br>";
    }

    $sql_add_fk = "ALTER TABLE messages ADD CONSTRAINT fk_messages_recipient_user_id FOREIGN KEY (recipient_user_id) REFERENCES users(id) ON DELETE SET NULL";
    if ($conn->query($sql_add_fk) === TRUE) {
        echo "✓ Clé étrangère pour recipient_user_id ajoutée.<br>";
    } else {
        echo "ℹ Clé étrangère recipient_user_id non ajoutée ou déjà existante: " . $conn->error . "<br>";
    }
}

// Créer la table des sessions
$sql_sessions = "CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql_sessions) === TRUE) {
    echo "✓ Table 'sessions' créée/vérifiée avec succès.<br>";
} else {
    echo "✗ Erreur lors de la création de la table sessions: " . $conn->error . "<br>";
}

// Insérer les utilisateurs de test
// password123 et test123 hachés avec password_hash
$password_hash_1 = password_hash('password123', PASSWORD_DEFAULT);
$password_hash_2 = password_hash('test123', PASSWORD_DEFAULT);

$sql_insert_users = "INSERT IGNORE INTO users (username, email, password, is_active) VALUES
('User1', 'user@example.com', '$password_hash_1', TRUE),
('Test User', 'test@test.com', '$password_hash_2', TRUE);";

if ($conn->query($sql_insert_users) === TRUE) {
    echo "✓ Utilisateurs de test insérés/vérifiés avec succès.<br>";
} else {
    echo "✗ Erreur lors de l'insertion des utilisateurs de test: " . $conn->error . "<br>";
}

// Nettoyer les messages de test existants
$sql_delete_default_messages = "DELETE FROM messages WHERE message IN (
    'Hello, how are you?',
    'I''m good, thanks! How about you?',
    'I''m doing well, just working on a project.'
)";

if ($conn->query($sql_delete_default_messages) === TRUE) {
    echo "✓ Messages de test supprimés s'ils existaient.<br>";
} else {
    echo "⚠️ Impossible de supprimer les messages de test: " . $conn->error . "<br>";
}

$conn->close();

echo "<hr>";
echo "<h3 style='color: green;'>✓ Installation terminée avec succès!</h3>";
echo "<p><strong>Utilisateurs de test disponibles:</strong></p>";
echo "<ul>";
echo "<li>Email: <code>user@example.com</code> | Mot de passe: <code>password123</code></li>";
echo "<li>Email: <code>test@test.com</code> | Mot de passe: <code>test123</code></li>";
echo "</ul>";
echo "<p><a href='login.php'>Aller à la page de connexion →</a></p>";
?>
