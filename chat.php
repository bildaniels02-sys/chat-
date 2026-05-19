<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';
require_once 'db_connect.php';

// Récupérer l'email de l'utilisateur
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Utilisateur';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Récupérer la liste des utilisateurs actifs pour le sélecteur de destinataire
$recipient_users = fetchAll(
    $conn,
    "SELECT id, username FROM users WHERE is_active = TRUE AND id != ? ORDER BY username ASC",
    [$user_id],
    "i"
);

// Récupérer les messages pertinents pour l'utilisateur connecté
$messages = fetchAll(
    $conn,
    "SELECT m.id, m.message, m.created_at, m.user_id AS sender_id, u.username AS sender_name,
            m.recipient_user_id, r.username AS recipient_name
     FROM messages m
     JOIN users u ON m.user_id = u.id
     LEFT JOIN users r ON m.recipient_user_id = r.id
     WHERE m.recipient_user_id IS NULL OR m.recipient_user_id = ? OR m.user_id = ?
     ORDER BY m.created_at DESC
     LIMIT 50",
    [$user_id, $user_id],
    "ii"
);

// Inverser l'ordre pour afficher du plus ancien au plus récent
$messages = array_reverse($messages);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en direct</title>
</head>
<body>
    <div class="header">
        <div class="user-info">
            <span>Connecté en tant que: <strong><?php echo htmlspecialchars($user_email); ?></strong></span>
            <a href="logout.php" class="logout-btn">Déconnexion</a>
        </div>
    </div>
    <div class="CONTANAIR">
        <div class="app-header">
            <div>
                <h1>Chat en direct</h1>
                <p>Envoyer un message privé ou à tout le monde.</p>
            </div>
            <div class="user-actions">
                <span>Connecté comme <strong><?php echo htmlspecialchars($user_email); ?></strong></span>
                <a href="logout.php" class="logout-btn">Déconnexion</a>
            </div>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="success-message">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <div class="chat-layout">
            <section class="messages-panel">
                <div class="messages">
                    <ul>
                        <?php
                        if (empty($messages)) {
                            echo '<li class="mess empty-message">Aucun message pour le moment...</li>';
                        } else {
                            foreach ($messages as $msg) {
                                $timestamp = date('Y-m-d H:i:s', strtotime($msg['created_at']));
                                $sender = htmlspecialchars($msg['sender_name']);
                                $message = htmlspecialchars($msg['message']);
                                $recipientText = 'à tout le monde';

                                if (!empty($msg['recipient_user_id'])) {
                                    $recipientName = htmlspecialchars($msg['recipient_name'] ?? 'inconnu');
                                    if ($msg['sender_id'] === $user_id) {
                                        $recipientText = 'à ' . $recipientName;
                                    } elseif ($msg['recipient_user_id'] === $user_id) {
                                        $recipientText = 'à vous';
                                    } else {
                                        $recipientText = 'à ' . $recipientName;
                                    }
                                }

                                echo '<li class="mess">'
                                    . '<div class="message-meta">' . $sender . ' <span class="message-recipient">' . $recipientText . '</span></div>'
                                    . '<div class="message-text">' . $message . '</div>'
                                    . '<div class="message-time">' . $timestamp . '</div>'
                                    . '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </section>

            <aside class="composer-panel">
                <form method="POST" action="send_message.php">
                    <div class="form-group">
                        <label for="recipient_id">Envoyer à :</label>
                        <select name="recipient_id" id="recipient_id">
                            <option value="">Tous</option>
                            <?php foreach ($recipient_users as $recipient): ?>
                                <option value="<?php echo $recipient['id']; ?>"><?php echo htmlspecialchars($recipient['username']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" id="message" rows="4" placeholder="Tapez votre message ici..." required></textarea>
                    </div>

                    <div class="form-group">
                        <input type="submit" value="ENVOYER" class="btn-submit">
                    </div>
                </form>
            </aside>
        </div>
    </div>
</body>
<STYle>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      background-color: bisque;
      font-family: Arial, Helvetica, sans-serif;
    }
    
    .header {
      background-color: rgb(116, 115, 115);
      padding: 15px 20px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      border-bottom: 2px solid white;
    }
    
    .user-info {
      display: flex;
      align-items: center;
      gap: 20px;
      color: white;
      font-weight: bold;
    }
    
    .logout-btn {
      background-color: #f44336;
      color: white;
      padding: 8px 15px;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s;
    }
    
    .logout-btn:hover {
      background-color: #da190b;
    }
    
    body {
      background-color: #f4f1e8;
      font-family: Arial, Helvetica, sans-serif;
      color: #333;
      min-height: 100vh;
    }

    .CONTANAIR {
      width: 90%;
      max-width: 960px;
      background-color: rgb(116,115,115);
      border-radius: 14px;
      margin: 20px auto;
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .app-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.25);
    }

    .app-header h1 {
      color: white;
      margin-bottom: 6px;
    }

    .app-header p {
      color: #e9e4d3;
      margin: 0;
    }

    .user-actions {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .user-actions span {
      color: white;
      font-weight: bold;
    }

    .logout-btn {
      background-color: #f44336;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
      transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .logout-btn:hover {
      transform: translateY(-1px);
      background-color: #d32f2f;
    }

    .chat-layout {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
    }

    .messages-panel,
    .composer-panel {
      background-color: white;
      border-radius: 12px;
      padding: 18px;
      box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.05);
    }

    .messages {
      max-height: 420px;
      overflow-y: auto;
      padding-right: 5px;
    }

    .messages ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .mess {
      background-color: #faf7f2;
      border-radius: 12px;
      padding: 14px 16px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
      display: grid;
      gap: 8px;
    }

    .mess.empty-message {
      text-align: center;
      color: #777;
      background-color: transparent;
      box-shadow: none;
    }

    .message-meta {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px;
      font-size: 13px;
      color: #555;
      font-weight: bold;
    }

    .message-recipient {
      font-style: italic;
      color: #777;
      font-weight: normal;
    }

    .message-text {
      line-height: 1.6;
      color: #2f2f2f;
      white-space: pre-wrap;
      word-break: break-word;
    }

    .message-time {
      font-size: 12px;
      color: #999;
    }

    .composer-panel form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    label {
      color: #333;
      display: block;
      margin-bottom: 6px;
      font-weight: 700;
    }

    select,
    textarea,
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 14px;
      border: 1px solid #dadada;
      border-radius: 10px;
      font-size: 14px;
      background-color: #ffffff;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    select:focus,
    textarea:focus,
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #9a8f7d;
      box-shadow: 0 0 0 3px rgba(154, 143, 125, 0.12);
    }

    textarea {
      min-height: 120px;
      resize: vertical;
    }

    .btn-submit,
    input[type="submit"] {
      width: 100%;
      padding: 14px 18px;
      border: none;
      border-radius: 10px;
      background-color: #4caf50;
      color: white;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .btn-submit:hover,
    input[type="submit"]:hover {
      background-color: #43a047;
      transform: translateY(-1px);
    }

    .error-message,
    .success-message {
      border-radius: 10px;
      padding: 12px 16px;
      margin: 0 0 10px;
      font-weight: bold;
    }

    .error-message {
      background-color: #fdecea;
      color: #b71c1c;
      border: 1px solid #f5c6cb;
    }

    .success-message {
      background-color: #e8f5e9;
      color: #1b5e20;
      border: 1px solid #c8e6c9;
    }

    @media (max-width: 860px) {
      .chat-layout {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 520px) {
      .CONTANAIR {
        width: 96%;
        padding: 16px;
      }

      .app-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .user-actions {
        width: 100%;
        justify-content: space-between;
      }

      .messages {
        max-height: 360px;
      }
    }

    select:focus,
    textarea:focus,
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
      outline: none;
      background-color: #f0f0f0;
    }

    textarea {
      resize: vertical;
    }

    .error-message,
    .success-message {
      width: 90%;
      margin: 10px auto;
      padding: 12px;
      border-radius: 5px;
      text-align: center;
      font-weight: bold;
    }

    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .success-message {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .mess {
      border-bottom: 1px solid #ddd;
      padding: 10px 15px;
      list-style: none;
    }

</STYle>
</html>