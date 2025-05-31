<?php
session_start();
require_once 'conexao.php';

// Função para calcular tempo desde criação da conta, tratando valor nulo
function tempoDesdeCriacao($dataCriacaoStr) {
    if (!$dataCriacaoStr) {
        return "data de criação desconhecida";
    }
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

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar dados do usuário
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

// Buscar respostas dadas pelo usuário junto com a pergunta
$query_respostas = "
    SELECT p.enunciado, p.resposta_correta, r.resposta
    FROM respostas r
    JOIN perguntas p ON r.pergunta_id = p.id
    WHERE r.usuario_id = ?
    ORDER BY r.id DESC
";
$stmt_respostas = $conexao->prepare($query_respostas);
$stmt_respostas->bind_param("i", $usuario_id);
$stmt_respostas->execute();
$respostas_result = $stmt_respostas->get_result();

// Estatísticas básicas
$total = 0;
$acertos = 0;
$erros = 0;

$respostas = [];
while ($linha = $respostas_result->fetch_assoc()) {
    $total++;
    // Comparar respostas ignorando maiúsculas/minúsculas e espaços
    if (strtolower(trim($linha['resposta'])) === strtolower(trim($linha['resposta_correta']))) {
        $acertos++;
    } else {
        $erros++;
    }
    $respostas[] = $linha;
}

$percentual_acertos = $total > 0 ? round(($acertos / $total) * 100, 2) : 0;

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_perfil'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($nome === "" || $email === "") {
        $mensagem = "<div class='msg-erro'>Nome e email não podem ficar vazios.</div>";
    } elseif ($senha !== "" && $senha !== $confirma_senha) {
        $mensagem = "<div class='msg-erro'>As senhas não coincidem.</div>";
    } else {
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
            if ($senha !== "") {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $atualiza = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
                $atualiza->bind_param("sssi", $nome, $email, $senhaHash, $usuario_id);
            } else {
                $atualiza = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                $atualiza->bind_param("ssi", $nome, $email, $usuario_id);
            }

            if ($atualiza->execute()) {
                $mensagem = "<div class='msg-sucesso'>Perfil atualizado com sucesso!</div>";
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
        /* seu CSS existente aqui */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #ddd;
            margin: 0;
            padding: 20px;
            transition: background-color 0.5s ease, color 0.5s ease;
        }
.container {
    max-width: 1200px; /* ou até mais: 1400px */
    margin: auto;
    background-color: #1f1f1f;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    animation: fadeIn 0.7s ease forwards;
}
        }
        h1, h2 {
            color: #ccc;
            margin-bottom: 15px;
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
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
.btn {
    padding: 8px 16px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #2980b9;
}

.campo-pesquisa {
    padding: 8px;
    width: 100%;
    max-width: 300px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.acertou {
    color: green;
    font-weight: bold;
}

.errou {
    color: red;
    font-weight: bold;
}

    </style>
</head>
<body>
    <a href="index.php" style="
    position: fixed;
    top: 10px;
    left: 10px;
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: background-color 0.3s ease;
">← Voltar</a>
    <div class="container">
        <h1>Olá, <?=htmlspecialchars($usuario['nome'])?></h1>
<p>Bem-vindo ao seu perfil no <strong>QAPnaProva</strong>.</p>

<div style="display: flex; gap: 30px; align-items: flex-start; flex-wrap: wrap; padding: 20px 0;">


  <!-- Info + Gráfico -->
  <div style="flex: 2; min-width: 400px;">
    <div class="info">
      <p><strong>Email:</strong> <?=htmlspecialchars($usuario['email'])?></p>
      <p><strong>Conta criada há:</strong> <?=tempoDesdeCriacao($usuario['data_criacao'])?></p>
      <p><strong>Você respondeu:</strong> <?=$total?> perguntas</p>
      <p><strong>Acertou:</strong> <?=$acertos?> (<?=$percentual_acertos?>%)</p>
      <p><strong>Errou:</strong> <?=$erros?></p>
    </div>

    <div style="margin-top: 20px;">
      <canvas id="graficoBarras" width="300" height="200"></canvas>
    </div>
  </div>

  <!-- Atualizar perfil -->
  <div style="flex: 1; min-width: 300px;">
    <p><strong>Atualizar Perfil</strong></p>
    <?=$mensagem?>
    <form method="post" action="">
      <label for="nome">Nome:</label>
      <input type="text" name="nome" id="nome" value="<?=htmlspecialchars($usuario['nome'])?>" required />

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" value="<?=htmlspecialchars($usuario['email'])?>" required />

      <label for="senha">Senha (deixe em branco para não alterar):</label>
      <input type="password" name="senha" id="senha" />

      <label for="confirma_senha">Confirme a senha:</label>
      <input type="password" name="confirma_senha" id="confirma_senha" />

      <button type="submit" name="atualizar_perfil">Atualizar Perfil</button>
    </form>
  </div>

  <!-- Respostas -->
  <div style="flex: 1; min-width: 300px;">
    <h2>Suas respostas</h2>

    <button id="mostrarRespostasBtn" class="btn">Mostrar perguntas respondidas</button>

    <div id="respostasRespondidas" style="display: none; margin-top: 15px;">
      <input type="text" id="pesquisaResposta" placeholder="Pesquisar resposta..." class="campo-pesquisa">
      <ul>
        <?php foreach ($respostas as $resposta): ?>
          <li>
            <strong>Pergunta:</strong> <?= html_entity_decode(strip_tags($resposta['enunciado'])) ?><br />
            <strong>Resposta correta:</strong> <?= htmlspecialchars($resposta['resposta_correta']) ?><br />
            <strong>Sua resposta:</strong> <?= htmlspecialchars($resposta['resposta']) ?><br />
            <?php if (strtolower($resposta['resposta']) === strtolower($resposta['resposta_correta'])): ?>
              <span class="acertou">Acertou</span>
            <?php else: ?>
              <span class="errou">Errou</span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>

<script>
  document.getElementById('mostrarRespostasBtn').addEventListener('click', function() {
    const div = document.getElementById('respostasRespondidas');
    div.style.display = div.style.display === 'none' ? 'block' : 'none';
  });

  const pesquisaInput = document.getElementById('pesquisaResposta');
  pesquisaInput.addEventListener('keyup', function() {
    const termo = pesquisaInput.value.toLowerCase();
    document.querySelectorAll('#respostasRespondidas ul li').forEach(function(item) {
      const texto = item.textContent.toLowerCase();
      item.style.display = texto.includes(termo) ? '' : 'none';
    });
  });
</script>


</body>
</html>
