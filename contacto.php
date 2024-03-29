<?php

function responseEmail($error, $mensaje, $adicional)
{
    $respuesta = array(
        'error' => isset($error) ? $error : false,
        'mensaje' => isset($mensaje) ? $mensaje : 'Mensaje enviado correctamente',
        'tecnico' => isset($adicional) ? $adicional : ''
    );

    http_response_code(200);

    if ($error) {
        http_response_code(500);

        isset($mensaje)
            ? $respuesta['mensaje'] = $mensaje
            : $respuesta['mensaje'] = 'No se pudo enviar el mensaje';
    }

    echo json_encode($respuesta);
}


function sendMail()
{
    $to         = 'holamundo@anclajemedia.com.mx';

    $from       = $_POST['email'];
    $nombre     = $_POST['nombre'];
    $asunto     = $_POST['asunto'];
    $mensaje    = $_POST['mensaje'];
    $compacto   = "<Correo desde el formulario del sitio web> De: $nombre. Escribe: $mensaje";

    //
    // Headers
    $headers    = "MIME-Version: 1.0" . "\r\n";
    $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers    .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    //
    // Guards
    if (!isset($from)) {
        return responseEmail(true, 'No se ha proporcionado un email', null);
    }

    if (!isset($nombre)) {
        return responseEmail(true, 'No se ha proporcionado un nombre', null);
    }

    try {
        mail($to, $asunto, $compacto, $headers)
            ? responseEmail(false, null, null)
            : responseEmail(true, null, "Falló al tratar de enviar el correo");
    } catch (Exception $e) {
        return responseEmail(true, null, $e->getMessage());
    }
}

sendMail();
