<?php
// db_connect.php - Connexion à la base de données

require_once 'config.php';

// Créer la connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Vérifier la connexion
if ($conn->connect_error) {
    debug_log("Erreur de connexion à la base de données: " . $conn->connect_error);
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Définir le charset UTF-8
if (!$conn->set_charset("utf8mb4")) {
    debug_log("Erreur lors de la définition du charset: " . $conn->error);
}

// Fonction pour préparer et exécuter une requête
function executeQuery($conn, $query, $params = [], $types = '') {
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        debug_log("Erreur de préparation: " . $conn->error);
        return false;
    }
    
    if (!empty($params) && !empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        debug_log("Erreur d'exécution: " . $stmt->error);
        return false;
    }
    
    return $stmt;
}

// Fonction pour récupérer un résultat unique
function fetchOne($conn, $query, $params = [], $types = '') {
    $stmt = executeQuery($conn, $query, $params, $types);
    if (!$stmt) return null;
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row;
}

// Fonction pour récupérer tous les résultats
function fetchAll($conn, $query, $params = [], $types = '') {
    $stmt = executeQuery($conn, $query, $params, $types);
    if (!$stmt) return [];
    
    $result = $stmt->get_result();
    $rows = [];
    
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    $stmt->close();
    return $rows;
}
?>
