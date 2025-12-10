<?php
/* 
Template Name: Panel
*/
get_header();

// Verificar sesión activa
session_start();
if (!isset($_SESSION['thbr_usuario'])) {
  echo "<div class='thbr-error'>Acceso no autorizado. Iniciá sesión primero.</div>";
  echo "<script>setTimeout(() => window.location.href='" . home_url('/ingresar') . "', 1500);</script>";
  exit;
}

$usuario = $_SESSION['thbr_usuario'];
?>

<div class="thbr-panel">

  <div style="position: absolute; top: 50px; right: 30px; font-size: 1rem; color: #333; font-weight: 500;">
    Bienvenido, <?php echo esc_html($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
  </div>

  <!-- Logo institucional -->
  <img src="<?php echo plugins_url( 'assets/logobrthbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" alt="Logo TreeHouse" />

  <!-- Botones institucionales -->
   <div class="btn-grupo">
    <a href="<?php echo home_url('/nuevocontrato'); ?>" class="thbr-btn">Nuevo contrato</a>
    <a href="<?php echo home_url('/historial'); ?>" class="thbr-btn">Historial</a>
    <a href="<?php echo home_url('/inicio'); ?>" class="thbr-btn">Volver al inicio</a>
  </div>
</div>

<?php 
get_footer();
?>