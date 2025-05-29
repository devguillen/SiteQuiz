<?php
session_start();
require('conexao.php');

if (!isset($_POST['email']) || !isset($_POST['senha'])) {
    // Redireciona de volta para o formulário se não veio o POST esperado
    header('Location: formlogin.php');
    exit;
}

$email = trim($_POST['email']);
$senha = $_POST['senha'];

$sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario['senha'])) {
        // Login OK, salva dados na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        // Redireciona para index.php após login bem sucedido
        header('Location: index.php');
        exit;
    } else {
        // Senha incorreta
        $_SESSION['erro_login'] = "Informações erradas!";
        header('Location: formlogin.php');
        exit;
    }
} else {
    // Usuário não encontrado
    $_SESSION['erro_login'] = "Informações erradas!";
    header('Location: formlogin.php');
    exit;
}

$stmt->close();
$conexao->close();
?>