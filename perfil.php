<?php
session_start();
require_once 'conexao.php';

// Função para calcular o tempo desde a criação da conta e retornar string amigável
function tempoDesdeCriacao($dataCriacaoStr) {
    $dataCriacao = new DateTime($dataCriacaoStr);
    $agora = new DateTime();
    $intervalo = $dataCriacao->diff($agora);

    $tempoCriacao = "";
    if ($intervalo->y > 0) {
        $tempoCriacao .= $intervalo->y . " ano" . ($intervalo->y > 1 ? "s " : " ");
    }
    if ($intervalo->m > 0) {
        $tempoCriacao .= $intervalo->m . " mês" . ($intervalo->m > 1 ? "es " : " ");
    }
    if ($intervalo->d > 0) {
        $tempoCriacao .= $intervalo->d . " dia" . ($intervalo->d > 1 ? "s" : "");
    }
    if ($tempoCriacao === "") {
        $tempoCriacao = "menos de 1 dia";
    }
    return trim($tempoCriacao);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado!";
    exit;
}

// Consulta para obter as perguntas respondidas pelo usuário
$query_respostas = "
    SELECT p.enunciado, p.resposta_correta, r.resposta
    FROM respostas r
    JOIN perguntas p ON r.pergunta_id = p.id
    WHERE r.usuario_id = ?
";
$stmt_respostas = $conexao->prepare($query_respostas);
$stmt_respostas->bind_param("i", $usuario_id);
$stmt_respostas->execute();
$respostas_result = $stmt_respostas->get_result();

$mensagem = "";
$erro = "";

// Processar atualização do perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_perfil'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // Validação simples
    if ($nome === "" || $email === "") {
        $mensagem = "<div class='msg-erro'>Nome e email não podem ficar vazios.</div>";
    } elseif ($senha !== "" && $senha !== $confirma_senha) {
        $mensagem = "<div class='msg-erro'>As senhas não coincidem.</div>";
    } else {
        // Verificar se o email é único (se mudou)
        if ($email !== $usuario['email']) {
            $verificaEmail = $conexao->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $verificaEmail->bind_param("si", $email, $usuario_id);
            $verificaEmail->execute();
            $resultadoEmail = $verificaEmail->get_result();
            if ($resultadoEmail->num_rows > 0) {
                $mensagem = "<div class='msg-erro'>Este email já está em uso por outro usuário.</div>";
            }
        }

        if ($mensagem === "") {
            // Atualizar senha se fornecida
            if ($senha !== "") {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $atualiza = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
                $atualiza->bind_param("sssi", $nome, $email, $senhaHash, $usuario_id);
            } else {
                // Sem alterar senha
                $atualiza = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                $atualiza->bind_param("ssi", $nome, $email, $usuario_id);
            }

            if ($atualiza->execute()) {
                $mensagem = "<div class='msg-sucesso'>Perfil atualizado com sucesso!</div>";
                // Atualiza dados da sessão e variável usuário para refletir mudanças
                $_SESSION['usuario_nome'] = $nome;
                $usuario['nome'] = $nome;
                $usuario['email'] = $email;
            } else {
                $mensagem = "<div class='msg-erro'>Erro ao atualizar perfil. Tente novamente.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Perfil - QAPnaProva</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        /* Tema escuro sóbrio */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #ddd;
            margin: 0;
            padding: 20px;
            transition: background-color 0.5s ease, color 0.5s ease;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: #1f1f1f;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            animation: fadeIn 0.7s ease forwards;
        }
        h1, h2 {
            color: #ccc;
            margin-bottom: 15px;
            /* sem texto neon */
            text-shadow: none;
        }
        .info {
            background: #2a2a2a;
            border-left: 5px solid #555;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            box-shadow: none;
            color: #ccc;
        }
        .mensagem, .erro {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: none;
        }
        .mensagem {
            background-color: #264d26;
            color: #a5d6a7;
            border: 1px solid #3a7d3a;
        }
        .erro {
            background-color: #4d2626;
            color: #ff9999;
            border: 1px solid #aa4444;
        }
        form label {
            display: block;
            margin: 10px 0 6px;
            font-weight: 600;
            color: #ccc;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background: #333;
            color: #ddd;
            box-shadow: inset 0 0 5px #000;
            transition: box-shadow 0.3s ease;
        }
        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form input[type="password"]:focus {
            outline: none;
            box-shadow: 0 0 8px #555;
            background: #222;
        }
        form button {
            margin-top: 20px;
            background: #555;
            border: none;
            padding: 14px 30px;
            color: #ddd;
            font-size: 16px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: none;
            transition: background-color 0.3s ease;
            animation: none;
        }
        form button:hover {
            background-color: #777;
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        ul li {
            background: #2a2a2a;
            border-left: 6px solid #555;
            margin-bottom: 15px;
            padding: 15px 20px;
            border-radius: 6px;
            box-shadow: none;
            transition: transform 0.3s ease;
            color: #ccc;
        }
        ul li:hover {
            transform: scale(1.02);
            box-shadow: none;
        }
        ul li span {
            font-weight: 700;
        }
        .acertou {
            color: #6fbf73;
            font-weight: bold;
        }
        .errou {
            color: #d9534f;
            font-weight: bold;
        }
        .btn-voltar {
            display: inline-block;
            margin-top: 30px;
            background: #555;
            color: #ddd;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            box-shadow: none;
            transition: background-color 0.3s ease;
            animation: none;
        }
        .btn-voltar:hover {
            background-color: #777;
        }
        /* Animações simples */
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Perfil de <?= htmlspecialchars($usuario['nome']) ?></h1>
        <p class="info"><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p class="info"><strong>Conta criada há:</strong> <?= tempoDesdeCriacao($usuario['criado_em']) ?></p>

        <?php if ($mensagem): ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php endif; ?>
        <?php if ($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>

        <h2>Editar Perfil</h2>
        <form method="post" action="perfil.php" autocomplete="off">
            <input type="hidden" name="atualizar_perfil" value="1" />
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label for="senha">Nova senha (deixe em branco para não alterar):</label>
            <input type="password" id="senha" name="senha" autocomplete="new-password">

            <label for="confirma_senha">Confirmar nova senha:</label>
            <input type="password" id="confirma_senha" name="confirma_senha" autocomplete="new-password">

            <button type="submit">Atualizar Perfil</button>
        </form>

        <h2>Perguntas Respondidas</h2>
        <?php if ($respostas_result->num_rows === 0): ?>
            <p>Você ainda não respondeu nenhuma pergunta.</p>
        <?php else: ?>
            <ul>
                <?php while ($linha = $respostas_result->fetch_assoc()): ?>
                    <li>
                        <p><strong>Pergunta:</strong> <?= htmlspecialchars($linha['enunciado']) ?></p>
                        <p><strong>Resposta Correta:</strong> <?= htmlspecialchars($linha['resposta_correta']) ?></p>
                        <p><strong>Sua Resposta:</strong> <?= htmlspecialchars($linha['resposta']) ?></p>
                        <p>Status: 
                            <?php if ($linha['resposta'] === $linha['resposta_correta']): ?>
                                <span class="acertou">Acertou</span>
                            <?php else: ?>
                                <span class="errou">Errou</span>
                            <?php endif; ?>
                        </p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <a href="index.html" class="btn-voltar">Voltar para o Quiz</a>
    </div>
</body>
</html>
