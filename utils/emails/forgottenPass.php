<?php

function forgottenPassEmail($swimmer)
{

    $subject = 'Recuperación de contraseña en cnescualos.es';

    $body = '<p>Hola, ' . $swimmer->getName() . ':</p>';

    $body .= '<p>Para cambiar tu contraseña en la web de CN Escualos, pincha 
                <a href="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/escualos/index.php?controller=login&action=forgottenPass&id=' . $swimmer->getId() . '&token=' . $swimmer->getResetPassToken() . '">AQUÍ</a> 
                y sigue los pasos indicados.</p>';

    $body .= '<p>Dispones de 30 minutos para modificar tu contraseña antes de que caduque el enlace</p>';

    $body .= '<p>Un saludo,</p>';

    $body .= '<hr>';

    $body .= '<p>Equipo web CN Escualos.</p>';

    $body .= '<p><img src="' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/escualos/public/img/logo_escualos.svg" style="width:150px"></p>';

    $body .= '<hr><p style="font-size:10px">Este mensage se dirige exclusivamente a su destinatario/a. Puede contener información confidencial sometida a secreto profesional o cuya divulgación esté prohibida, en virtud de la legislación vigente. No está permitida la divulgación, copia o distribución a terceros sin la autorización previa del remitente. Si has recibido este mensage por error, rogamos que nos lo comuniques inmediatamente por esta misma vía y procedas a su destrucción.</p><hr>';

    return [
        'subject' => $subject,
        'body' => $body
    ];
}
