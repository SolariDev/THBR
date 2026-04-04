<?php
// shortcode: [thbr_nueva_password]
function thbr_nueva_password_shortcode() {
    global $wpdb;

    $tabla = $wpdb->prefix . 'thbr_usuarios';
    $token = sanitize_text_field($_POST['token'] ?? ($_GET['token'] ?? ''));
    $mensaje = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevaClave = $_POST['password'] ?? '';

        if ($token && $nuevaClave) {
            $usuario = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabla WHERE token = %s", $token));

            if ($usuario && strtotime($usuario->token_expiry) > time()) {

                $hash = password_hash($nuevaClave, PASSWORD_DEFAULT);

                $wpdb->update(
                    $tabla,
                    [
                        'password' => $hash,
                        'token' => null,
                        'token_expiry' => null
                    ], 
                    ['id_usuario' => $usuario->id_usuario]
                );
                
                $mensaje = "<div class='thbr-exito'>Tu contraseña fue actualizada correctamente.</div>";
                echo "<script>
                        setTimeout(function(){
                            window.location.href = '" . home_url('/ingresar') . "';
                        }, 3000);
                     </script>";

            } else {
                $mensaje = "<div class='thbr-error'>El enlace no es válido o ha expirado.</div>";
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
        <h2>Nueva contraseña</h2>
        <?php echo $mensaje; ?>
        <form method="post" class="thbr-form">
            <input type="hidden" name="token" value="<?php echo esc_attr($token); ?>" />
            <fieldset>
                <div class="thbr-campo">
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu nueva contraseña">
                </div>
            </fieldset>
            <button type="submit" style="margin-top:5px; padding:10px 20px; background:#0056B3; color:#fff; border:none; border-radius:4px;">
                Guardar contraseña
            </button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('thbr_nueva_password', 'thbr_nueva_password_shortcode');
