<?php
// shortcode: [thbr_cambiar_password]

require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function thbr_cambiar_password_shortcode() {
    global $wpdb;

    $mensaje = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tabla  = $wpdb->prefix . 'thbr_usuarios';
        $correo = sanitize_email($_POST['correo'] ?? '');

        if ($correo) {
            $usuario = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabla WHERE correo = %s", $correo));

            if ($usuario) {
                $token  = bin2hex(random_bytes(32));
                $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

                $wpdb->update(
                    $tabla,
                    ['token' => $token, 'token_expiry' => $expiry],
                    ['id_usuario' => $usuario->id_usuario]
                );

                // Enviar correo con PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'pruebassolaridev@gmail.com';
                    $mail->Password   = 'xygccbzpmvotwzai';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;
                    $mail->CharSet    = 'UTF-8';

                    $mail->setFrom('pruebassolaridev@gmail.com', 'TreeHouse Bienes Raíces');
                    $mail->addAddress($correo);
                    $mail->addBCC('pruebassolaridev@gmail.com', 'Cambio de contraseña');

                    $mail->isHTML(true);
                    $mail->Subject = "Instrucciones para cambiar tu contraseña";

                    // URL del logo (pública)
                    $logo_url = 'https://app.treehouse.com.uy/wp-content/plugins/thbr/assets/logobrthbr.png';

                    $mail->Body = "
                        <html>
                            <body style='font-family: Montserrat, sans-serif; background-color:#fff; color:#0056B3;'>
                                <div style='max-width:600px; margin:auto; border:1px solid #ddd; padding:20px;'>
                                    <div style='text-align:center; margin-bottom:15px;'>
                                        <img src='" . $logo_url . "' alt='Logo TreeHouse' style='max-width:120px; height:auto;' />
                                    </div>
                                    <h3 style='color:#0056B3;'>Actualización de contraseña</h3>
                                    <p>Para actualizar tu contraseña, utiliza el siguiente enlace:</p>
                                    <p><a href='" . home_url('/nueva-password?token=' . $token) . "' style='color:#0056B3; font-weight:600;'>Cambiar contraseña</a></p>
                                    <p>Este enlace expira en 1 hora.</p>
                                </div>
                            </body>
                        </html>
                    ";

                    $mail->send();
                    $mensaje = "<div class='thbr-exito'>Correo para cambiar contraseña enviado con éxito.</div>";
                } catch (Exception $e) {
                    $mensaje = "<div class='thbr-error'>No se pudo enviar el correo. Error: {$mail->ErrorInfo}</div>";
                }
            } else {
                $mensaje = "<div class='thbr-error'>El correo no está registrado.</div>";
            }
        }
    }

    ob_start(); ?>
    <div class="thbr-contrato">
        <div style="text-align:center; margin-bottom:20px;">
            <img src="<?php echo esc_url( plugins_url('assets/logobrthbr.png', WP_PLUGIN_DIR . '/thbr/index.php') ); ?>"
                 alt="Logo TreeHouse"
                 style="display:block; margin:0 auto; max-width:250px; height:auto;" />
        </div>
        <h2>Cambiar contraseña</h2>
        <?php echo $mensaje; ?>
        <form method="post" class="thbr-form">
            <fieldset>
                <div class="thbr-campo">
                    <input type="email" id="correo" name="correo" required placeholder="Ingresa tu correo">
                </div>
            </fieldset>
            <button type="submit" style="margin-top:5px; padding:10px 20px; background:#0056B3; color:#fff; border:none; border-radius:4px;">
                Enviar enlace
            </button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('thbr_cambiar_password', 'thbr_cambiar_password_shortcode');
