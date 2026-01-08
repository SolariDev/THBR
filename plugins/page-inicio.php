<!-- shortcode: [thbr_inicio] -->

<style>
  .page-inicio .thbr-botones-centrados {
    min-height: 100vh;
  }
  html, body {
    overflow-y: hidden;
  }
</style>

<div class="thbr-botones-centrados" >
  <!-- Logo institucional -->
  <img src="<?php echo plugins_url( 'assets/logothbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" alt="Logo TreeHouse" style="max-width:280px; margin-bottom:0px;"/>

  <!-- Misión institucional -->
  <p class="thbr-intro" style="margin-top:0; margin-bottom:10px; line-height:1.4;">
    En TreeHouse Bienes Raíces trabajamos para ofrecer un servicio de calidad,<br>
    gestionando propiedades con compromiso, transparencia y cercanía.
  </p>

  <!-- Botones de acción -->
  <div class="grupo-botones">
    <a href="<?php echo home_url('/registro'); ?>" class="thbr-btn">Registro</a>
    <a href="<?php echo home_url('/ingresar'); ?>" class="thbr-btn">Iniciar sesión</a>
  </div>
</div>