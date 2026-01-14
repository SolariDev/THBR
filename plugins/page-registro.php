<?php
// shortcode [thbr_registro]

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $wpdb;
    $tabla = $wpdb->prefix . 'thbr_usuarios';

    $nombre   = sanitize_text_field($_POST['nombre'] ?? '');
    $apellido = sanitize_text_field($_POST['apellido'] ?? '');
    $correo   = sanitize_email($_POST['correo'] ?? '');
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';


    if ($nombre && $apellido && $correo && $password) {
        // Verificar si el correo ya existe
        $existe = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $tabla WHERE correo = %s", $correo));

        if ($existe == 0) {
            $wpdb->insert($tabla, [
                'nombre'   => $nombre,
                'apellido' => $apellido,
                'correo'   => $correo,
                'password' => $password
            ]);

            $id_usuario = $wpdb->insert_id;

            session_start();
            $_SESSION['thbr_usuario'] = $id_usuario;
            session_write_close();

            echo "<div class='thbr-exito'>Usuario registrado correctamente.</div>";
        } else {
            echo "<div class='thbr-error'>El correo ya está registrado.</div>";
        }
    } else {
        echo "<div class='thbr-error'>Faltan campos obligatorios en el formulario.</div>";
    }
}
?>

<div class="thbr-registro" style="padding-top: 10px;" >
    <!-- Logo institucional -->
  <img src="<?php echo plugins_url( 'assets/logobrthbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" alt="Logo TreeHouse" />

    <!-- Título institucional -->
  <h2 style="margin-top:-20px;">Crea tu cuenta</h2>

    <!-- Formulario de registro -->
  <form method="post">
    <div class="thbr-doble">
      <div class="thbr-doble-item">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>
      </div>
      <div class="thbr-doble-item">
          <label for="apellido">Apellido</label>
          <input type="text" id="apellido" name="apellido" required>
      </div>
    </div>

    <div class="thbr-campo">
    <label for="correo">Correo electrónico</label>
    <input type="email" id="correo" name="correo" required autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false">
    </div>

    <div class="thbr-campo">
    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" required autocomplete="new-password">
    </div>

    <button type="submit" class="thbr-boton-registro">Registrate</button>
  </form>

    <!-- Enlace inferior -->
  <div class="thbr-link">
    <a href="<?php echo home_url('/ingresar'); ?>" class="enlace-ingreso">¿Ya tenés cuenta? Inicia sesión</a>
  </div>
</div>