<?php
require_once './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/** Sends an email to an array of recipients (Object swimmer) using phpmailer */

function sendEmail($recipients,$subject,$body)
{
    try {

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = "ssl0.ovh.net";
        $mail->Port = "465";
        $mail->Username = "admin@cnescualos.es";
        $mail->Password = "OteOteElQueN0B0t3";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        foreach ($recipients as $recipient) {

            $mail->addBCC($recipient->getEmail(), $recipient->getName() . ' ' . $recipient->getSurname());
        }

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();

        return ['success' => true];

    } catch (Exception $e) {

        return [
            'success' => false,
            'error' =>  $mail->ErrorInfo
        ];
    }
}
