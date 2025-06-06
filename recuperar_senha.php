
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Senha</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #121212;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background-color: #1e1e1e;
      padding: 30px;
      border-radius: 10px;
      color: #fff;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 0 10px rgba(0,0,0,0.6);
    }

    h2 {
      margin-bottom: 20px;
      text-align: center;
    }

    input[type="email"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #444;
      background: #2c2c2c;
      color: #fff;
      border-radius: 8px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #218838;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #1c6c30;
    }

    .mensagem {
      margin-top: 15px;
      color: #ccc;
      text-align: center;
    }
  </style>
</head>
<body>
   <div class="login-container">
    <h2>Recuperar Senha</h2>
    
<?php if (isset($_SESSION['recuperar_msg'])): ?>
  <p style="color: #0f0"><?= $_SESSION['recuperar_msg'] ?></p>
  <?php unset($_SESSION['recuperar_msg']); ?>
<?php endif; ?>


    <form action="processa_recuperacao.php" method="POST">
      <input type="email" name="email" placeholder="Digite seu e-mail" required />
      <button type="submit">Enviar link de recuperação</button>
    </form>

    <p class="login-link"><a href="formlogin.php">Voltar ao login</a></p>
  </div>
</body>
</html>
