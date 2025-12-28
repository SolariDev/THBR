<?php
/*
Template Name: Ingresar
*/
get_header();

session_start();

global $wpdb;
$tabla = $wpdb->prefix . 'thbr_usuarios';

// Procesar login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['correo'], $_POST['password'])) {
        $correo = sanitize_email($_POST['correo']);
        $password = $_POST['password'];

        $usuario = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabla WHERE correo = %s", $correo));

        if ($usuario && password_verify($password, $usuario->password)) {
            $_SESSION['thbr_usuario'] = [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido
            ];
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

    <button type="submit" class="thbr-boton-ingreso">Iniciar sesión</button>
  </form>
</div>

<?php get_footer(); ?>
