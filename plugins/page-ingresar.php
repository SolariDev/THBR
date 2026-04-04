<?php
// shortcode: [thbr_ingresar]
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $wpdb;

    $tabla = $wpdb->prefix . 'thbr_usuarios';

    $correo = sanitize_email($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($correo && $password) {
        $usuario = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabla WHERE correo = %s", $correo));

        if ($usuario && password_verify($password, $usuario->password)) {            
            $_SESSION['thbr_usuario'] = $usuario->id_usuario;

                wp_set_current_user($usuario->id_usuario);
                wp_set_auth_cookie($usuario->id_usuario);

                echo "<div class='thbr-exito'>Sesión iniciada correctamente.</div>";
                echo "<script>setTimeout(() => window.location.href='" . home_url('/panel') . "', 1000);</script>";
            } else {
                echo "<div class='thbr-error'>Credenciales incorrectas.</div>";
            } 
        } else {
            echo "<div class='thbr-error'>Faltan campos obligatorios.</div>"; 
        }
    }
?>

  <!-- Vista institucional del login -->
<div class="thbr-ingreso">

    <!-- Logo institucional -->
  <img src="<?php echo plugins_url( 'assets/logobrthbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" alt="Logo TreeHouse" />
  
    <!-- Formulario de ingreso -->
  <form method="post" autocomplete="off">
    <input type="email" id="correo" name="correo" required placeholder="Correo electrónico" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false">

    <input type="password" id="password" name="password" required placeholder="Contraseña" autocomplete="new-password" autocorrect="off" autocapitalize="none" spellcheck="false">

    <button type="submit" class="thbr-boton-ingreso" style="margin: 0 auto 8px;">Iniciar sesión</button>

    <div class="thbr-link thbr-registro">
        <a href="<?php echo home_url('/cambiar-password'); ?>" class="enlace-ingreso">¿Olvidaste tu contraseña?</a>
    </div>
  </form>        
</div>

<style>
    .thbr-ingreso form .thbr-link {
        margin: o !important;
        padding: 0 !important;
        line-height: 1 !important;
        display: inline-block !important;
    }
</style>