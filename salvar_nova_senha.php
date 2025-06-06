<?php
require_once 'conexao.php';

$token = $_POST['token'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';

if (!$token || !$nova_senha) {
    die("Dados inválidos.");
}

$sql = "SELECT * FROM usuarios WHERE token_recuperacao = ? AND expira_em > NOW()";
$stmt = $conexao->prepare($sql);
$stmt->execute([$token]);

if ($stmt->rowCount() == 0) {
    die("Token expirado ou inválido.");
}

$usuario = $stmt->fetch();
$hash = password_hash($nova_senha, PASSWORD_DEFAULT);

// Atualizar senha e limpar token
$sql = "UPDATE usuarios SET senha = ?, token_recuperacao = NULL, expira_em = NULL WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->execute([$hash, $usuario['id']]);

echo "Senha redefinida com sucesso. <a href='formlogin.php'>Fazer login</a>";
