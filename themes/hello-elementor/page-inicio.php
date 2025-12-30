<?php
/*
Template Name: Inicio
*/
get_header();
?>

<div class="thbr-home">
  <!-- Logo institucional -->
  <img src="<?php echo plugins_url( 'assets/logobrthbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" alt="Logo TreeHouse" />

  <!-- Misión institucional -->
  <p class="thbr-intro">
    En TreeHouse Bienes Raíces trabajamos para ofrecer un servicio de calidad,<br>
    gestionando propiedades con compromiso, transparencia y cercanía.
  </p>

  <!-- Botones de acción -->
  <div>
    <a href="<?php echo home_url('/registro'); ?>" class="thbr-btn">Registro</a>
    <a href="<?php echo home_url('/ingresar'); ?>" class="thbr-btn">Iniciar sesión</a>
  </div>
</div>

<?php
get_footer();
?>