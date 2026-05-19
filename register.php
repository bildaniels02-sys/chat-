<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Chat en direct</title>
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
            min-height: 100vh;
            padding: 20px;
        }
        
        .register-container {
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
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            background-color: white;
        }
        
        input[type="text"]:focus,
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: white;
        }
        
        .login-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Inscription</h1>
        
        <form method="POST" action="register_process.php">
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Choisissez un pseudo..."
                    required
                >
            </div>
            
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
            
            <div class="form-group">
                <label for="confirm_password">Confirmez le mot de passe :</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Confirmez votre mot de passe..."
                    required
                >
            </div>
            
            <div class="button-group">
                <input type="submit" value="S'INSCRIRE">
            </div>
        </form>
        
        <div class="login-link">
            Vous avez déjà un compte ? <a href="login.php">Se connecter</a>
        </div>
    </div>
</body>
</html>
