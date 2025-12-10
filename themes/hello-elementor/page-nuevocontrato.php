<?php
/*
Template Name: Nuevo Contrato
*/
get_header();
session_start();

$usuario = $_SESSION['thbr_usuario'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario) {
  global $wpdb;
  $tabla = $wpdb->prefix . 'thbr_contratos';

  $datos = [
    'id_usuario'     => intval($usuario['id']),
    'direccion'      => sanitize_text_field($_POST['direccion'] ?? ''),
    'apartamento'    => sanitize_text_field($_POST['apartamento'] ?? ''),
    'garage'         => sanitize_text_field($_POST['garage'] ?? ''),
    'prop_nombre'    => sanitize_text_field($_POST['prop_nombre'] ?? ''),
    'prop_apellido'  => sanitize_text_field($_POST['prop_apellido'] ?? ''),
    'prop_telefono'  => sanitize_text_field($_POST['prop_telefono'] ?? ''),
    'prop_mail'      => sanitize_email($_POST['prop_mail'] ?? ''),
    'inq_nombre'     => sanitize_text_field($_POST['inq_nombre'] ?? ''),
    'inq_apellido'   => sanitize_text_field($_POST['inq_apellido'] ?? ''),
    'inq_telefono'   => sanitize_text_field($_POST['inq_telefono'] ?? ''),
    'inq_mail'       => sanitize_email($_POST['inq_mail'] ?? ''),
    'precio_alquiler' => isset($_POST['precio_alquiler']) ? floatval($_POST['precio_alquiler']) : null,
    'moneda'         => sanitize_text_field($_POST['moneda'] ?? ''), 
    'garantia'       => sanitize_text_field($_POST['garantia'] ?? ''),
    'inicio'         => sanitize_text_field($_POST['inicio'] ?? ''),
    'fin'            => sanitize_text_field($_POST['fin'] ?? ''),
    'link_drive'     => esc_url_raw($_POST['link_drive'] ?? ''),
    'tipo_reajuste'  => sanitize_text_field($_POST['tipo_reajuste'] ?? ''),
    'fecha_creacion' => current_time('mysql')
  ];

  $wpdb->insert($tabla, $datos);
  echo "<div class='thbr-exito'>Contrato registrado correctamente.</div>";
}
?>

<div class="thbr-contrato">
  <h2>Nuevo Contrato</h2>

  <form method="post" class="thbr-form" autocomplete="off">

    <!-- Propiedad -->
<fieldset>
  <div class="thbr-legend"><legend>Propiedad</legend></div>

  <div class="thbr-campo">
    <input type="text" name="direccion" placeholder="Dirección del inmueble" required>
  </div>

  <div class="thbr-doble">
    <input type="text" name="apartamento" placeholder="Apartamento">
    <input type="text" name="garage" placeholder="Garage">
  </div>
</fieldset>


    <!-- Propietario/a -->
    <fieldset>
      <div class="thbr-legend"><legend>Propietario/a</legend></div>

      <div class="thbr-doble">
        <input type="text" name="prop_nombre" placeholder="Nombre" required>
        <input type="text" name="prop_apellido" placeholder="Apellido" required>
      </div>

      <div class="thbr-campo"><input type="tel" name="prop_telefono" placeholder="Teléfono de contacto" required></div>
      <div class="thbr-campo"><input type="email" name="prop_mail" placeholder="Mail" required></div>
    </fieldset>

    <!-- Inquilino/a -->
    <fieldset>
      <div class="thbr-legend"><legend>Inquilino/a</legend></div>

      <div class="thbr-doble">
        <input type="text" name="inq_nombre" placeholder="Nombre" required>
        <input type="text" name="inq_apellido" placeholder="Apellido" required>
      </div>
      
      <div class="thbr-campo"><input type="tel" name="inq_telefono" placeholder="Teléfono de contacto" required></div>
      <div class="thbr-campo"><input type="email" name="inq_mail" placeholder="Mail" required></div>
    </fieldset>

<fieldset>
  <div class="thbr-legend"><legend>Condiciones del contrato</legend></div>

  <!-- Precio del alquiler -->
  <div class="thbr-fila">
    <label for="precio_alquiler">Precio del alquiler</label>
    <div class="thbr-opciones">
      <label><input type="radio" name="moneda" value="UYU" required> $U</label>
      <label><input type="radio" name="moneda" value="USD"> $USD</label>
    </div>
  </div>
  <div class="thbr-campo">
    <input type="number" id="precio_alquiler" name="precio_alquiler" step="0.01" value="0.00" required>
  </div>

  <!-- Garantía -->
  <div class="thbr-campo">
    <label for="garantia">Garantía</label>
    <select id="garantia" name="garantia" required>
      <option>PORTO SEGURO</option>
      <option>MAPFRE</option>
      <option>SURA</option>
      <option>ANDA</option>
      <option>CGM</option>
      <option>DEPÓSITO EN BHU</option>
      <option>PROPIEDAD</option>
    </select>
  </div>

    <!-- Tiempo de contrato -->
<div class="thbr-campo">
  <label>Tiempo de contrato</label>
  <input type="text" id="tiempo_contrato" name="tiempo_contrato" placeholder="Ej: 12meses">
</div>

<div class="thbr-doble-label">
  <div class="thbr-fecha">
    <label for="inicio">Fecha de inicio</label>
    <input type="date" id="inicio" name="inicio" required>
  </div>

  <div class="thbr-fecha">
    <label for="fin">Fecha de término</label>
    <input type="date" id="fin" name="fin" required>
  </div>
</div>


  
  <!-- Link de carpeta Drive -->
  <div class="thbr-campo">
    <label for="link_drive">Link de carpeta Drive</label>
    <input type="url" id="link_drive" name="link_drive" placeholder="https://...">
  </div>

  <!-- Tipo de reajuste -->
  <div class="thbr-fila">
    <label for="tipo_reajuste">Tipo de reajuste</label>
    <div class="thbr-opciones">
      <label><input type="radio" name="tipo_reajuste" value="IPC" required> IPC</label>
      <label><input type="radio" name="tipo_reajuste" value="URA"> URA</label>
    </div>
  </div>
</fieldset>    

    <!-- Botón -->
    <button type="submit">Guardar Contrato</button>
  </form>
</div>

<?php get_footer(); ?>



