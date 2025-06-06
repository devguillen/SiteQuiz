<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - QAPnaProva</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #121212;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      animation: fadeIn 0.8s ease-in-out;
    }

    .wrapper {
      display: flex;
      width: 800px;
      height: 500px;
      background: #1e1e1e;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    }

    .form-section {
      width: 50%;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: #fff;
    }

    .image-section {
      width: 50%;
      background-image: url('img/login.jpg');
      background-size: cover;
      background-position: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #fff;
      text-align: center;
    }

    input[type='text'],
    input[type='password'] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #444;
      background: #2c2c2c;
      color: #fff;
      border-radius: 8px;
      transition: border-color 0.3s ease;
    }

    input:focus {
      border-color: #218838;
      outline: none;
    }

    button {
      background-color: #218838;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      margin-top: 15px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #1c6c30;
    }

    .login-link {
      margin-top: 20px;
      font-size: 14px;
      text-align: center;
      color: #ccc;
    }

    .login-link a {
      color: #218838;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    .login-link a:hover {
      color: #1c6c30;
    }

    .error-message {
      color: red;
      font-weight: bold;
      margin-bottom: 15px;
      text-align: center;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-15px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 900px) {
      .wrapper {
        flex-direction: column;
        width: 90%;
        height: auto;
      }

      .form-section, .image-section {
        width: 100%;
        height: 300px;
      }

      .image-section {
        height: 250px;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="form-section">
      <h2>Login</h2>
      <?php if (isset($_SESSION['erro_login'])): ?>
        <p class="error-message"><?= htmlspecialchars($_SESSION['erro_login']) ?></p>
        <?php unset($_SESSION['erro_login']); ?>
      <?php endif; ?>
      <form action="login.php" method="POST">
        <input type="text" name="email" placeholder="E-mail" required />
        <input type="password" name="senha" placeholder="Senha" required />
        <button type="submit">Entrar</button>
      </form>
      <p class="login-link">
        Ainda não tem conta? <a href="cadastro.html">Cadastre-se</a>
      </p>
    </div>
    <div class="image-section"></div>
  </div>
</body>
</html>
