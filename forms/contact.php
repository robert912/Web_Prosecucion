<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/php-email-form/Exception.php';
require '../assets/vendor/php-email-form/PHPMailer.php';
require '../assets/vendor/php-email-form/SMTP.php';

define('PRIVATE_KEY_RV3', '6LfjyiEpAAAAAL-oLH-JByg7InC3uo1Z1nbyHOuD');
validate_recaptcha();
  // Replace contact@example.com with your real receiving email address
  $receiving_email_address = 'p.minas@usach.cl';
  $mail = new PHPMailer(true);

try {
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

} catch (Exception $e) {
    echo "{$mail->ErrorInfo}";
}

function validate_recaptcha(){
  $token = $_POST['token'];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => '6LfjyiEpAAAAAL-oLH-JByg7InC3uo1Z1nbyHOuD', 'response' => $token)));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  $arrResponse = json_decode($response, true);

  // verify the response
  if($arrResponse["success"] != '1' || $arrResponse["score"] < 0.5) {
    echo "<script>console.log('Console: " . $arrResponse . "' );</script>";
    echo "Message send for Bot {$arrResponse['success']} and {$arrResponse['score']} = {$token}";
    #echo "{$mail->ErrorInfo}";// wp_redirect( home_url("/contacto/")."?sent=-1" );
    exit;
  }
}

?>
