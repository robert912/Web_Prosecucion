<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/php-email-form/Exception.php';
require '../assets/vendor/php-email-form/PHPMailer.php';
require '../assets/vendor/php-email-form/SMTP.php';

// Mensaje de depuración inicial
echo "Script PHP cargado correctamente.<br>";

// Validación de datos del formulario
if (isset($_POST['email'], $_POST['name'], $_POST['subject'], $_POST['message'])) {
    $receiving_email_address = 'roberto.orellana.t@usach.cl';
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug  = 0;
        $mail->isSMTP();
        $mail->CharSet    = 'UTF-8';
        $mail->Host       = 'mail.dimin.cl';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'support@dimin.cl';
        $mail->Password   = '{j+c+eB&VJFj';
        $mail->Port       = 587;
        $mail->SMTPSecure = 'tls';

        // Configuración de remitente y destinatario
        $mail->setFrom($_POST['email'], $_POST['name']);
        $mail->addAddress($receiving_email_address);

        // Adjuntos (si hay archivos)
        if (isset($_FILES["resume"]["name"])) {
            $totalFiles = count($_FILES["resume"]["name"]);
            for ($i = 0; $i < $totalFiles; $i++) {
                $name = $_FILES["resume"]["name"][$i];
                $path = $_FILES["resume"]["tmp_name"][$i];
                $mail->addAttachment($path, $name);
            }
        }

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = "Prosecución de Estudios - {$_POST['subject']}";
        $mail->Body = file_get_contents("../template/template_correo_form.html");
        $mail->Body = str_replace("nombreForm", $_POST['name'], $mail->Body);
        $mail->Body = str_replace("mailForm", $_POST['email'], $mail->Body);
        $mail->Body = str_replace("asuntoForm", $_POST['subject'], $mail->Body);
        $mail->Body = str_replace("msjForm", $_POST['message'], $mail->Body);

        $mail->send();
    } catch (Exception $e) {
        echo "Error en el envío de correo: {$mail->ErrorInfo}";
    }
} else {
    echo "Error: Datos incompletos en el formulario.<br>";
}
?>
