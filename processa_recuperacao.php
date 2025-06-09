<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Caminho para o autoload do PHPMailer
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(32));
    $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $stmt = $conexao->prepare("UPDATE usuarios SET token_recuperacao=?, token_expiracao=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expira, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $link = "http://localhost:8000/redefinir_senha.php?token=$token";

        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'qapnaprova@gmail.com'; // Seu email
            $mail->Password = 'ytpy oloh ozyu ggvy';         // Senha do app Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configurações do remetente e destinatário
            $mail->setFrom('SEU_EMAIL@gmail.com', 'QapnaProva');
            $mail->addAddress($email);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = '🔐 Redefina sua senha - QapnaProva';

            $mail->Body = "
              <div style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #28a745;'>Olá!</h2>
                <p>Recebemos uma solicitação para redefinir sua senha na plataforma <strong>QapnaProva</strong>.</p>
                <p>Para continuar, clique no botão abaixo e crie uma nova senha com segurança:</p>
                <p style='text-align: center; margin: 20px 0;'>
                  <a href='$link' style='padding: 12px 20px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;'>
                    Redefinir Senha
                  </a>
                </p>
                <p>Se você não solicitou essa alteração, apenas ignore este e-mail.</p>
                <hr>
                <p style='font-size: 12px; color: #888;'>Este link é válido por 1 hora por motivos de segurança.</p>
              </div>
            ";            

            $mail->send();
            echo "<script>alert('Link de recuperação enviado com sucesso!'); window.location.href='formlogin.php';</script>";
        } catch (Exception $e) {
            echo "Erro ao enviar email: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>alert('E-mail não encontrado.'); window.location.href='esqueceu_senha.php';</script>";
    }
}
?>
