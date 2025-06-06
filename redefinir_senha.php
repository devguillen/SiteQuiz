<?php
require_once 'conexao.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE token_recuperacao = ? AND token_expira > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nova_senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            $stmt = $conexao->prepare("UPDATE usuarios SET senha = ?, token_recuperacao = NULL, token_expira = NULL WHERE id = ?");
            $stmt->bind_param("si", $nova_senha, $usuario['id']);
            $stmt->execute();

            echo "Senha redefinida com sucesso! <a href='formlogin.php'>Fazer login</a>";
            exit;
        }
        ?>
        <form method="post">
            <label>Nova Senha:</label>
            <input type="password" name="senha" required>
            <button type="submit">Redefinir</button>
        </form>
        <?php
    } else {
        echo "Token inválido ou expirado.";
    }
} else {
    echo "Token não fornecido.";
}
?>
