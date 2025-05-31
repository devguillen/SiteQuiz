<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "Você precisa estar logado para ver suas respostas.";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar as respostas do usuário juntando com as perguntas para pegar o enunciado
$query = "
    SELECT r.id, r.resposta, p.enunciado
    FROM respostas r
    JOIN perguntas p ON r.pergunta_id = p.id
    WHERE r.usuario_id = ?
    ORDER BY r.id DESC
";

$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Minhas Respostas</title>
</head>
<body>
    <h1>Minhas Respostas</h1>

    <?php if ($result->num_rows === 0): ?>
        <p>Você ainda não respondeu nenhuma pergunta.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID da Resposta</th>
                    <th>Pergunta</th>
                    <th>Resposta dada</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['enunciado']) ?></td>
                    <td><?= htmlspecialchars($row['resposta']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
