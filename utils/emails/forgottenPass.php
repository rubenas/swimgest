<?php

function forgottenPassEmail($swimmer) {

    $subject = 'Recuperación de contraseña en cnescualos.es';

    $body = '<p>Hola, '.$swimmer->getName().':</p>';

    $body .= '<p>Para cambiar tu contraseña en la web de CN Escualos, pincha 
                <a href="http://localhost/escualos/index.php?controller=login&action=forgottenPass&id='.$swimmer->getId().'&token='.$swimmer->getResetPassToken().'">AQUÍ</a> 
                y sigue los pasos indicados.</p>';

    $body .= '<p>Dispones de 30 minutos para modificar tu contraseña antes de que caduque el enlace</p>';

    $body .= '<p>Un saludo,</p>';

    $body .= '<p>Equipo web CN Escualos.</p>';

    return [
        'subject' => $subject,
        'body' => $body
    ];
}