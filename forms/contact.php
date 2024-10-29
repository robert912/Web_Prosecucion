<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/php-email-form/Exception.php';
require '../assets/vendor/php-email-form/PHPMailer.php';
require '../assets/vendor/php-email-form/SMTP.php';

// Mensaje de depuración inicial
echo "Script PHP cargado correctamente.<br>";

$secretKey = '6LdJuikpAAAAAALgTmvWWPbT2Q6RfRJzm9aHQsgw';

// Replace contact@example.com with your real receiving email address
//$receiving_email_address = 'p.minas@usach.cl';
$receiving_email_address = 'roberto.orellana.t@usach.cl';
$mail = new PHPMailer(true);

try {
    if(!empty($_POST['g-recaptcha-response'])){
        echo "reCAPTCHA verificado.<br>";
        // Google reCAPTCHA verification API Request 
        $api_url = 'https://www.google.com/recaptcha/api/siteverify'; 
        $resq_data = array( 
            'secret' => $secretKey, 
            'response' => $_POST['g-recaptcha-response'], 
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ); 
        $ch = curl_init(); 
        curl_setopt_array($ch, array( 
            CURLOPT_URL => $api_url, 
            CURLOPT_POST => true, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_POSTFIELDS => $resq_data, 
            CURLOPT_SSL_VERIFYPEER => false 
        )); 

        $response = curl_exec($ch); 
        
        if ($response === false) { 
            $api_error = curl_error($ch);
            echo "cURL Error: " . $api_error . "<br>";
        } else {
            echo "Raw API Response: " . $response . "<br>";
            
            $responseData = json_decode($response); 
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "JSON Decode Error: " . json_last_error_msg() . "<br>";
            } else {
                if(!empty($responseData) && $responseData->success){
                    echo "reCAPTCHA verificado con éxito. Procediendo a enviar el correo.<br>";
                    // Send email notification to the site admin 
                    $mail->SMTPDebug  = 0;                                   //Enable verbose debug output
                    $mail->isSMTP();                                         //Send using SMTP
                    $mail->CharSet    = 'UTF-8';
                    $mail->Host       = 'mail.dimin.cl';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                //Enable SMTP authentication
                    $mail->Username   = 'support@dimin.cl';                  //SMTP username
                    $mail->Password   = '{j+c+eB&VJFj';                      //SMTP password
                    $mail->Port       =  587;
                    $mail->SMTPSecure = 'tls';                               //Enable implicit TLS encryption

                    // Configuración de remitente y destinatario
                    $mail->setFrom($_POST['email'], $_POST['name']);
                    $mail->addAddress($receiving_email_address);    //Add a recipient
                    $mail->addAddress('test-7kshgg9ze@srv1.mail-tester.com');    //Add a recipient
                    
                    //$mail->addAddress('comunicaciones.dimin@usach.cl');            //Name is optional
                    //$mail->addAddress('roberto.orellana.t@usach.cl');
                    //$mail->addAddress('patricia.munoz.l@usach.cl');
                    /*$mail->addReplyTo('info@example.com', 'Information');
                    $mail->addCC('cc@example.com');
                    $mail->addBCC('bcc@example.com');*/
                    
                    // Adjuntos (si hay archivos)
                    if (isset($_FILES["resume"]["name"])) {
                        $totalFiles = count($_FILES["resume"]["name"]);
                        for ($i = 0; $i < $totalFiles; $i++) {
                            $name = $_FILES["resume"]["name"][$i];
                            $path = $_FILES["resume"]["tmp_name"][$i];
                            $mail->addAttachment($path, $name);
                        }
                    }
                    //Attachments
                    //$name = $_FILES["resume"]["name"];
                    //$path = $_FILES["resume"]["tmp_name"];
                    //$mail->AddAttachment($path, $name);

                    // Contenido del correo
                    $mail->isHTML(true); //Set email format to HTML
                    $mail->Subject = "Prosecucion de Estudios - {$_POST['subject']}";
                    $mail->Body = file_get_contents("../template/template_correo_form.html");
                    $mail->Body = str_replace("nombreForm", $_POST['name'], $mail->Body);
                    $mail->Body = str_replace("mailForm", $_POST['email'], $mail->Body);
                    $mail->Body = str_replace("asuntoForm", $_POST['subject'], $mail->Body);
                    $mail->Body = str_replace("msjForm", $_POST['message'], $mail->Body);

                    if ($mail->send()) {
                        echo "El mensaje ha sido enviado correctamente.<br>";
                    } else {
                        echo "Error al enviar el mensaje.<br>";
                    }
                    #header("Location: ../contact.html");
                    #$output = "<div id='phppot-message' class='success'>Feedback received.</div>";
                    #print $output;
                    #exit;
                    //echo 'Message has been sent'; 
                } else { 
                    $statusMsg = 'The reCAPTCHA verification failed. API Response: ' . print_r($responseData, true);
                    echo $statusMsg . "<br>";
                }
            }
        }
        
        curl_close($ch); 
    } else { 
        echo "No reCAPTCHA response received.<br>";
    }
    echo "{$mail->$statusMsg}";
} catch (Exception $e) {
    echo "{$mail->ErrorInfo}";
}
