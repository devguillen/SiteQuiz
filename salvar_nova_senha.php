<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conexao->prepare("SELECT id, token_expiracao FROM usuarios WHERE token_recuperacao=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && strtotime($usuario['token_expiracao']) > time()) {
        $id = $usuario['id'];
        $stmt = $conexao->prepare("UPDATE usuarios SET senha=?, token_recuperacao=NULL, token_expiracao=NULL WHERE id=?");
        $stmt->bind_param("si", $senha, $id);
        $stmt->execute();
        echo "<script>alert('Senha redefinida com sucesso!'); window.location.href='formlogin.php';</script>";
    } else {
        echo "<script>alert('Token inválido ou expirado.'); window.location.href='esqueceu_senha.php';</script>";
    }
}
?>
