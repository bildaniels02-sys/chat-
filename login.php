<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Chat en direct</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: bisque;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .login-container {
            width: 30%;
            background-color: rgb(116, 115, 115);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 3px;
            border-bottom: 2px solid white;
            padding-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: white;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            background-color: white;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            background-color: #f0f0f0;
        }
        
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: white;
        }
        
        .signup-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #f5c6cb;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion</h1>
        
        <?php
        session_start();
        
        // Afficher les messages d'erreur ou de succès
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        
        if (isset($_SESSION['success'])) {
            echo '<div class="success-message">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        
        <form method="POST" action="authenticate.php">
            <div class="form-group">
                <label for="email">Adresse email :</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Entrez votre email..."
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Entrez votre mot de passe..."
                    required
                >
            </div>
            
            <div class="button-group">
                <input type="submit" value="SE CONNECTER">
            </div>
        </form>
        
        <div class="signup-link">
            Vous n'avez pas de compte ? <a href="register.php">S'inscrire</a>
        </div>
    </div>
</body>
</html>
