<?php
require_once './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/** Sends an email to an array of recipients using phpmailer */

function sendEmail($recipients, $subject, $body, $smtpConfig)
{

    try {

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = $smtpConfig['host'];
        $mail->Port = $smtpConfig['port'];
        $mail->Username = $smtpConfig['username'];
        $mail->Password = $smtpConfig['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        foreach ($recipients as $recipient) {

            $mail->addBCC($recipient);
        }

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->FromName = $smtpConfig['from'];

        if ($mail->send()) return ['success' => true];

        else return [
            'success' => false,
            'error' => $mail->ErrorInfo
        ];
        
    } catch (Exception $e) {

        return [
            'success' => false,
            'error' =>  $e->getMessage()
        ];
    }
}
