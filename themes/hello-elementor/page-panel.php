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

  <!-- Bloque centrado: Logo y botones -->
   <div class="thbr-botones-centrados">
    <img src="<?php echo plugins_url( 'assets/logobrthbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" alt="Logo TreeHouse" />

   <div class="grupo-botones">
    <a href="<?php echo home_url('/nuevocontrato'); ?>" class="thbr-btn">Nuevo contrato</a>
    <a href="<?php echo home_url('/historial'); ?>" class="thbr-btn">Historial</a>    
  </div>
</div>

</div>

<div style="position: absolute; bottom: 30px; left: 30px;">
  <a href="<?php echo get_template_directory_uri(); ?>/cerrarsesion.php" 
     style="display: inline-block; font-size: 1rem; font-weight: 600; color: #fff; text-decoration: none; 
            background-color: #2c3e50; /* azul institucional oscuro */
            padding: 10px 18px; border-radius: 6px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); 
            transition: background-color 0.2s ease;"
     onmouseover="this.style.backgroundColor='#34495e'" 
     onmouseout="this.style.backgroundColor='#2c3e50'">
     🔒 Cerrar sesión
  </a>
</div>

<?php 
get_footer();
?>