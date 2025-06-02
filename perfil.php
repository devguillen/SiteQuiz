<?php
session_start();
require_once 'conexao.php';

function tempoDesdeCriacao($dataCriacaoStr) {
    if (!$dataCriacaoStr) return "data de criação desconhecida";
    $dataCriacao = new DateTime($dataCriacaoStr);
    $agora = new DateTime();
    $intervalo = $dataCriacao->diff($agora);
    $tempoCriacao = "";
    if ($intervalo->y > 0) $tempoCriacao .= $intervalo->y . " ano" . ($intervalo->y > 1 ? "s " : " ");
    if ($intervalo->m > 0) $tempoCriacao .= $intervalo->m . " mês" . ($intervalo->m > 1 ? "es " : " ");
    if ($intervalo->d > 0) $tempoCriacao .= $intervalo->d . " dia" . ($intervalo->d > 1 ? "s" : "");
    return $tempoCriacao ?: "menos de 1 dia";
}

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

$query_respostas = "
    SELECT p.enunciado, p.resposta_correta, p.alternativa_a, p.alternativa_b, p.alternativa_c, p.alternativa_d, p.alternativa_e, r.resposta
    FROM respostas r
    JOIN perguntas p ON r.pergunta_id = p.id
    WHERE r.usuario_id = ?
    ORDER BY r.id DESC
";
$stmt_respostas = $conexao->prepare($query_respostas);
$stmt_respostas->bind_param("i", $usuario_id);
$stmt_respostas->execute();
$respostas_result = $stmt_respostas->get_result();

$total = $acertos = $erros = 0;
$respostas = [];
while ($linha = $respostas_result->fetch_assoc()) {
    $total++;
    if (strcasecmp(trim($linha['resposta']), trim($linha['resposta_correta'])) === 0) {
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
        $mensagem = "<div class='erro'><span class='fechar' onclick='this.parentElement.style.display=\"none\";'>&times;</span>Nome e email não podem ficar vazios.</div>";
    } elseif ($senha !== "" && $senha !== $confirma_senha) {
        $mensagem = "<div class='erro'><span class='fechar' onclick='this.parentElement.style.display=\"none\";'>&times;</span>As senhas não coincidem.</div>";
    } else {
        if ($email !== $usuario['email']) {
            $verificaEmail = $conexao->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $verificaEmail->bind_param("si", $email, $usuario_id);
            $verificaEmail->execute();
            if ($verificaEmail->get_result()->num_rows > 0) {
                $mensagem = "<div class='erro'><span class='fechar' onclick='this.parentElement.style.display=\"none\";'>&times;</span>Este email já está em uso.</div>";
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
                $mensagem = "<div class='mensagem'><span class='fechar' onclick='this.parentElement.style.display=\"none\";'>&times;</span>Perfil atualizado com sucesso!</div>";
                $_SESSION['usuario_nome'] = $nome;
                $usuario['nome'] = $nome;
                $usuario['email'] = $email;
            } else {
                $mensagem = "<div class='erro'><span class='fechar' onclick='this.parentElement.style.display=\"none\";'>&times;</span>Erro ao atualizar perfil.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Perfil - QAPnaProva</title>
<link rel="stylesheet" href="style.css">
<style>
body { background: #121212; color: #ddd; font-family: Arial, sans-serif; }
.container { max-width: 1200px; margin: auto; background: #1f1f1f; padding: 20px; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
.mensagem, .erro { padding: 10px; margin-bottom: 10px; border-radius: 5px; position: relative; }
.mensagem { background: #2e7d32; color: #a5d6a7;  }
.erro { background: #c62828; color: #ffcdd2; }
.mensagem .fechar {
    position: absolute;
    top: 7px;
    right: 10px;
    font-size: 24px; /* aumenta o X */
    font-weight: bold;
    cursor: pointer;
}
.btn { background-color: green; color: white; padding: 10px 20px; border: none; text-decoration: none; border-radius: 5px; margin-top: 10px; display: inline-block; transition: transform 0.3s ease, background-color 0.3s ease; }
.btnvoltar { background-color: green; color: white; padding: 10px 20px; border: none; text-decoration: none; border-radius: 5px; margin-left: 10px; margin-top: 10px; display: inline-block; transition: transform 0.3s ease, background-color 0.3s ease; }
.btnvoltar:hover { background-color: darkgreen; }
.btn:hover { background-color: darkgreen; }
.form-perfil {
    max-width: 650px; /* deixa mais estreito *//* centraliza */
}

.form-perfil input {
    width: 100%; /* mantém largura total dentro da form, mas como a form é menor, fica ajustado */
}


input[type="text"], input[type="email"], input[type="password"], .campo-pesquisa {
    width: 100%; padding: 10px; margin: 5px 0 10px; border-radius: 5px; border: none; background: #333; color: #fff; transition: background 0.3s ease;
}
input:focus { background: #444; }
ul { list-style: none; padding: 0; }
ul li { margin: 10px 0; padding: 10px; background: #2a2a2a; border-left: 4px solid #555; border-radius: 5px; opacity: 0; transform: translateY(20px); transition: all 0.5s ease; cursor: pointer; }
ul li.show { opacity: 1; transform: translateY(0); }
.acertou { color: #4caf50; font-weight: bold; }
.errou { color: #e74c3c; font-weight: bold; }
</style>
</head>
<body>
<a href="index.php" class="btnvoltar">Voltar</a>
<div class="container">
<h1>Olá, <?=htmlspecialchars($usuario['nome'])?></h1>
<p>Email: <?=htmlspecialchars($usuario['email'])?></p>
<p>Conta criada há: <?=tempoDesdeCriacao($usuario['data_criacao'])?></p>
<p>Respondeu: <?=$total?> | Acertos: <?=$acertos?> (<?=$percentual_acertos?>%) | Erros: <?=$erros?></p>

<h2>Atualizar Perfil</h2>
<?=$mensagem?>
<form method="post" class="form-perfil">
    <label>Nome:</label>
    <input type="text" name="nome" value="<?=htmlspecialchars($usuario['nome'])?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?=htmlspecialchars($usuario['email'])?>" required>
    <label>Nova Senha:</label>
    <input type="password" name="senha">
    <label>Confirme a senha:</label>
    <input type="password" name="confirma_senha">
    <button class="btn" type="submit" name="atualizar_perfil">Atualizar</button>
    
</form>

<h2>Respostas</h2>
<input type="text" id="pesquisa" class="campo-pesquisa" placeholder="Pesquisar enunciado...">
<button class="btn btn-fechar" onclick="toggleRespostas()">Ver/Esconder Respostas</button>


<ul id="listaRespostas" style="display:none;">
<?php foreach ($respostas as $resp): ?>
    <li>
        <strong>Enunciado:</strong> <?= $resp['enunciado'] ?><br><br>
        <strong>Alternativas:</strong><br>
        A) <?= htmlspecialchars($resp['alternativa_a']) ?><br>
        B) <?= htmlspecialchars($resp['alternativa_b']) ?><br>
        C) <?= htmlspecialchars($resp['alternativa_c']) ?><br>
        D) <?= htmlspecialchars($resp['alternativa_d']) ?><br>
        E) <?= htmlspecialchars($resp['alternativa_e']) ?><br><br>
        <strong>Sua Resposta:</strong> <?= htmlspecialchars($resp['resposta']) ?><br>
        <?php if (strcasecmp(trim($resp['resposta']), trim($resp['resposta_correta'])) === 0): ?>
            <span class="acertou">Acertou</span>
        <?php else: ?>
            <span class="errou">Errou (Resposta correta: <?= htmlspecialchars($resp['resposta_correta']) ?>)</span>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
</div>

<script>
function toggleRespostas() {
    const lista = document.getElementById('listaRespostas');
    if (lista.style.display === 'none' || lista.style.display === '') {
        lista.style.display = 'block';
        mostrarItensComAnimacao();
    } else {
        lista.style.display = 'none';
    }
}

document.getElementById('pesquisa').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const lista = document.getElementById('listaRespostas');
    const items = lista.getElementsByTagName('li');
    for (let i = 0; i < items.length; i++) {
        const texto = items[i].textContent.toLowerCase();
        items[i].style.display = texto.includes(filtro) ? '' : 'none';
    }
});

function mostrarItensComAnimacao() {
    const items = document.querySelectorAll('#listaRespostas li');
    items.forEach((item, index) => {
        setTimeout(() => item.classList.add('show'), index * 100);
    });
}
</script>
</body>
</html>
