<style>
  html, body {
    overflow: hidden;
    height: 100%;
    margin: 0;
  }
</style>

<?php
session_start();

$id_usuario = $_SESSION['thbr_usuario'] ?? 0;
$nombre_apellido = '';

if($id_usuario > 0) {
    global $wpdb;
    $tabla = $wpdb->prefix . 'thbr_usuarios';
    $usuario = $wpdb->get_row(
        $wpdb->prepare("SELECT nombre, apellido FROM $tabla WHERE id_usuario = %d", $id_usuario)
    );

    if ($usuario) {
        $nombre_apellido = esc_html($usuario->nombre . ' ' . $usuario->apellido);
    } else {
        $nombre_apellido = 'Usuario no registrado';
    }
    } else {
        $nombre_apellido = 'Sesión no iniciada';
    }
?>

<div class="thbr-panel">

  <div style="position: absolute; top: 50px; right: 30px; font-size: 1rem; color: #1c35a5ff; font-weight: 500;">
    <?php echo $nombre_apellido; ?>
  </div>

  <!-- Bloque centrado: Logo y botones -->
  <div class="thbr-botones-centrados"
        style="justify-content:flex-start;">
    <img src="<?php echo plugins_url( 'assets/logobrthbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" alt="Logo TreeHouse" />

    <div class="grupo-botones">
      <a href="<?php echo home_url('/nuevocontrato'); ?>" class="thbr-btn">Nuevo contrato</a>
      <a href="<?php echo home_url('/historial'); ?>" class="thbr-btn">Historial</a>    
    </div>
  </div>
</div>

<div style="position: absolute; bottom: 30px; left: 30px;">
  <a href="<?php echo home_url('/inicio'); ?>" 
     style="display: inline-block; font-size: 1rem; font-weight: 600; color: #2c3e50; text-decoration: none; 
            background-color: #bdc3c7;
            padding: 10px 18px; border-radius: 6px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); 
            transition: background-color 0.2s ease;"
     onmouseover="this.style.backgroundColor='#a7b1b7'" 
     onmouseout="this.style.backgroundColor='#bdc3c7'">
     🔒 Cerrar sesión
  </a>
</div>