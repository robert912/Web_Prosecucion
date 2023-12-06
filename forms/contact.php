<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/php-email-form/Exception.php';
require '../assets/vendor/php-email-form/PHPMailer.php';
require '../assets/vendor/php-email-form/SMTP.php';

$secretKey = '6LfjyiEpAAAAAL-oLH-JByg7InC3uo1Z1nbyHOuD';

// Replace contact@example.com with your real receiving email address
$receiving_email_address = 'p.minas@usach.cl';
$mail = new PHPMailer(true);

try {
    if(!empty($_POST['g-recaptcha-response'])){ 
        // Google reCAPTCHA verification API Request 
        $api_url = 'https://www.google.com/recaptcha/api/siteverify'; 
        $resq_data = array( 
            'secret' => $secretKey, 
            'response' => $_POST['g-recaptcha-response'], 
            'remoteip' => $_SERVER['REMOTE_ADDR'] 
        ); 

        $curlConfig = array( 
            CURLOPT_URL => $api_url, 
            CURLOPT_POST => true, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_POSTFIELDS => $resq_data, 
            CURLOPT_SSL_VERIFYPEER => false 
        ); 

        $ch = curl_init(); 
        curl_setopt_array($ch, $curlConfig); 
        $response = curl_exec($ch); 
        if (curl_errno($ch)) { 
            $api_error = curl_error($ch); 
        } 
        curl_close($ch); 

        // Decode JSON data of API response in array 
        $responseData = json_decode($response); 

        // If the reCAPTCHA API response is valid 
        if(!empty($responseData) && $responseData->success){ 
            // Send email notification to the site admin 
            //Server settings
            $mail->SMTPDebug  = 0;                                   //Enable verbose debug output
            $mail->isSMTP();                                         //Send using SMTP
            $mail->CharSet    = 'UTF-8';
            $mail->Host       = 'mail.dimin.cl';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                //Enable SMTP authentication
            $mail->Username   = 'support@dimin.cl';                  //SMTP username
            $mail->Password   = '{j+c+eB&VJFj';                      //SMTP password
            $mail->Port       = 587;
            /*$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;*/     //Enable implicit TLS encryption

            //Destinatarios
            $mail->setFrom($_POST['email'], $_POST['name']);
            #$mail->addAddress($receiving_email_address);    //Add a recipient
            #$mail->addAddress('comunicaciones.dimin@usach.cl');            //Name is optional
            $mail->addAddress('roberto.orellana.t@usach.cl');
            /*$mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');*/
            
            // Adjuntos
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

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = "Prosecucion de Estudios - {$_POST['subject']}";
            $mail->Body = file_get_contents("../template/template_correo_form.html");
            $mail->Body = str_replace("nombreForm", $_POST['name'], $mail->Body);
            $mail->Body = str_replace("mailForm", $_POST['email'], $mail->Body);
            $mail->Body = str_replace("asuntoForm", $_POST['subject'], $mail->Body);
            $mail->Body = str_replace("msjForm", $_POST['message'], $mail->Body);

            echo $mail->send();
            //echo 'Message has been sent'; 
        }else{ 
            $statusMsg = !empty($api_error)?$api_error:'The reCAPTCHA verification failed, please try again.'; 
        } 
    }else{ 
        $statusMsg = 'Something went wrong, please try again.'; 
    }
    echo "{$mail->statusMsg}";
} catch (Exception $e) {
    echo "{$mail->ErrorInfo}";
}

?>
