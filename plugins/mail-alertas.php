<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // para pruebas en local
        $mail->SMTPAuth = true;
        $mail->Username = 'pruebassolaridev@gmail.com'; 
        $mail->Password = 'xygccbzpmvotwzai';    
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom('pruebassolaridev@gmail.com', 'TreeHouse Bienes Raíces');
        $mail->addAddress('inquilinopruebas0@gmail.com');
        $mail->addAddress('propietarioprueba729@gmail.com');
        $mail->addBCC('pruebassolaridev@gmail.com', 'Registro de Notificación');

        $mail->isHTML(true);
        $mail->Subject = "Prueba de alertas - THBR";
        $mail->Body = "
        <html>
            <head>
                <link href='https://fonts.googleapis.com/css2?family=Montserrat&display=swap' rel='stylesheet'>
            </head>
            <body style='font-family: Montserrat, sans-serif; background-color:#fff; color:#0056B3; margin:0; padding:0;'>
                <div style='max-width:600px; margin:auto; border:1px solid #ddd; padding:20px;'>
                    <div style='text-align:center; border-bottom:1px solid #ccc; padding-bottom:15px;'>
                        <img src= 'https://app.treehouse.com.uy/wp-content/plugins/thbr/assets/logobrthbr.png' 
                                 alt='Logo TreeHouse' style='max-width:120px; height:auto;' />
                        <h3 style='margin-top:10px; font-size:18px; color: #0056B3;'>Notificación de vencimiento</h3>
                    </div>
                    <div style='padding:20px;'>
                        <p>Estimado cliente,</p>
                        <p>Desde <strong>TreeHouse Bienes Raíces</strong> le recordamos que en 30 días vence su contrato de alquiler. 
                        Por favor, coordine las acciones necesarias.</p>
                    </div>
                    <div style='border-top:1px solid #ccc; padding-top:15px; font-size:12px; text-align:center; color:#777;'>
                        <p>Tel: +598 1234 5678 | Email: contacto@thbr.com</p>
                        <p>Este correo es automático, por favor no responder.</p>
                    </div>
                </div>
            </body>
        </html>
        ";

        $mail->send();
        echo "Correo de prueba enviado correctamente";
    } catch (Exception $e) {
        echo "Error al enviar alerta: {$mail->ErrorInfo}";
    }