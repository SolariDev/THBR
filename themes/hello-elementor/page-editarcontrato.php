<?php
/*
Template Name: Editar Contrato
*/
get_header();
session_start();

$usuario = $_SESSION['thbr_usuario'] ?? null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$usuario || !$id) {
  echo "<div class='thbr-error'>Acceso no autorizado.</div>";
  get_footer();
  exit;
}

global $wpdb;
$tabla = $wpdb->prefix . 'thbr_contratos';

$contrato = $wpdb->get_row($wpdb->prepare(
  "SELECT * FROM $tabla WHERE id = %d AND id_usuario = %d",
  $id,
  $usuario['id']
));

if (!$contrato) {
  echo "<div class='thbr-error'>Contrato no encontrado.</div>";
  get_footer();
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $link_drive = !empty($_POST['link_drive']) ? filter_var($_POST['link_drive'], FILTER_VALIDATE_URL) : '';

  $datos = [
    'direccion'       => sanitize_text_field($_POST['direccion'] ?? ''),
    'apartamento'     => sanitize_text_field($_POST['apartamento'] ?? ''),
    'garage'          => sanitize_text_field($_POST['garage'] ?? ''),
    'prop_nombre'     => sanitize_text_field($_POST['prop_nombre'] ?? ''),
    'prop_apellido'   => sanitize_text_field($_POST['prop_apellido'] ?? ''),
    'prop_telefono'   => sanitize_text_field($_POST['prop_telefono'] ?? ''),
    'prop_mail'       => sanitize_email($_POST['prop_mail'] ?? ''),
    'inq_nombre'      => sanitize_text_field($_POST['inq_nombre'] ?? ''),
    'inq_apellido'    => sanitize_text_field($_POST['inq_apellido'] ?? ''),
    'inq_telefono'    => sanitize_text_field($_POST['inq_telefono'] ?? ''),
    'inq_mail'        => sanitize_email($_POST['inq_mail'] ?? ''),
    'precio_alquiler' => isset($_POST['precio_alquiler']) ? floatval($_POST['precio_alquiler']) : null,
    'moneda'          => sanitize_text_field($_POST['moneda'] ?? ''),
    'garantia'        => sanitize_text_field($_POST['garantia'] ?? ''),
    'tiempo_contrato' => sanitize_text_field($_POST['tiempo_contrato'] ?? ''),
    'inicio'          => sanitize_text_field($_POST['inicio'] ?? ''),
    'fin'             => sanitize_text_field($_POST['fin'] ?? ''),
    'link_drive'      => $link_drive ? esc_url_raw($link_drive) : '',
    'tipo_reajuste'   => sanitize_text_field($_POST['tipo_reajuste'] ?? '')
  ];

  $wpdb->update($tabla, $datos, ['id' => $id]);

  echo "<div class='thbr-exito'>Contrato actualizado correctamente.</div>";
  echo "<script>setTimeout(() => window.location.href='" . home_url('/historial') . "', 800);</script>";
}
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin:30px 40px;">
  <!-- Botones a la izquierda -->
  <div>
    <a href="<?php echo home_url('/panel'); ?>" 
       style="margin-right:12px;font-weight:600;text-decoration:none;color:#2c3e50;">⚙️ Panel</a>
    <a href="<?php echo home_url('/historial'); ?>" 
       style="font-weight:600;text-decoration:none;color:#2c3e50;">📂 Historial</a>
  </div>

  <!-- Usuario activo a la derecha -->
  <div style="font-weight:600;color:#2c3e50;">
    Usuario: <?php echo esc_html($usuario['nombre'].' '.$usuario['apellido']); ?>
  </div>
</div>

<div class="thbr-contrato">
  <h2>Editar Contrato</h2>

  <form method="post" class="thbr-form" autocomplete="off">
    <!-- Propiedad -->
    <fieldset>
      <div class="thbr-legend"><legend>Propiedad</legend></div>
      <div class="thbr-campo">
        <input type="text" name="direccion" value="<?php echo esc_attr($contrato->direccion); ?>" required>
      </div>
      <div class="thbr-doble">
        <input type="text" name="apartamento" value="<?php echo esc_attr($contrato->apartamento); ?>">
        <input type="text" name="garage" value="<?php echo esc_attr($contrato->garage); ?>">
      </div>
    </fieldset>

    <!-- Propietario/a -->
    <fieldset>
      <div class="thbr-legend"><legend>Propietario/a</legend></div>
      <div class="thbr-doble">
        <input type="text" name="prop_nombre" value="<?php echo esc_attr($contrato->prop_nombre); ?>" required>
        <input type="text" name="prop_apellido" value="<?php echo esc_attr($contrato->prop_apellido); ?>" required>
      </div>
      <div class="thbr-campo"><input type="tel" name="prop_telefono" value="<?php echo esc_attr($contrato->prop_telefono); ?>" required></div>
      <div class="thbr-campo"><input type="email" name="prop_mail" value="<?php echo esc_attr($contrato->prop_mail); ?>" required></div>
    </fieldset>

    <!-- Inquilino/a -->
    <fieldset>
      <div class="thbr-legend"><legend>Inquilino/a</legend></div>
      <div class="thbr-doble">
        <input type="text" name="inq_nombre" value="<?php echo esc_attr($contrato->inq_nombre); ?>" required>
        <input type="text" name="inq_apellido" value="<?php echo esc_attr($contrato->inq_apellido); ?>" required>
      </div>
      <div class="thbr-campo"><input type="tel" name="inq_telefono" value="<?php echo esc_attr($contrato->inq_telefono); ?>" required></div>
      <div class="thbr-campo"><input type="email" name="inq_mail" value="<?php echo esc_attr($contrato->inq_mail); ?>" required></div>
    </fieldset>

    <!-- Condiciones -->
    <fieldset>
  <div class="thbr-legend"><legend>Condiciones del contrato</legend></div>

  <!-- Precio del alquiler -->
  <div class="thbr-fila">
    <label>Precio del alquiler</label>
    <div class="thbr-opciones">
      <label>
        <input type="radio" name="moneda" value="UYU" <?php checked($contrato->moneda ?? '', 'UYU'); ?>> UYU
      </label>
      <label>
        <input type="radio" name="moneda" value="USD" <?php checked($contrato->moneda ?? '', 'USD'); ?>> USD
      </label>
    </div>
  </div>
  <div class="thbr-campo">
    <input type="number" name="precio_alquiler" step="0.01" 
           value="<?php echo esc_attr($contrato->precio_alquiler ?? ''); ?>" required>
  </div>

  <!-- Tipo de reajuste -->
  <div class="thbr-fila">
    <label for="tipo_reajuste">Tipo de reajuste</label>
    <div class="thbr-opciones">
      <label>
        <input type="radio" name="tipo_reajuste" value="IPC" <?php checked($contrato->tipo_reajuste ?? '', 'IPC'); ?>> IPC
      </label>
      <label>
        <input type="radio" name="tipo_reajuste" value="URA" <?php checked($contrato->tipo_reajuste ?? '', 'URA'); ?>> URA
      </label>
    </div>
  </div>

  <!-- Garantía -->
  <div class="thbr-campo">
    <label for="garantia">Garantía</label>
    <select name="garantia" required>
      <?php
      $garantias = ['PORTO SEGURO','MAPFRE','SURA','ANDA','CGM','DEPÓSITO EN BHU','PROPIEDAD'];
      foreach ($garantias as $g) {
        echo '<option value="'.esc_attr($g).'" '.selected($contrato->garantia ?? '', $g, false).'>'.$g.'</option>';
      }
      ?>
    </select>
  </div>

  <!-- Tiempo de contrato -->
  <div class="thbr-campo">
    <label>Tiempo de contrato</label>
    <input type="text" name="tiempo_contrato" 
           placeholder="Ej: 12meses"
           value="<?php echo esc_attr($contrato->tiempo_contrato ?? ''); ?>">
  </div>

  <!-- Fechas -->
  <div class="thbr-doble-label">
    <div class="thbr-fecha">
      <label for="inicio">Fecha de inicio</label>
      <input type="date" name="inicio" 
             value="<?php echo esc_attr($contrato->inicio ?? ''); ?>" required>
    </div>
    <div class="thbr-fecha">
      <label for="fin">Fecha de término</label>
      <input type="date" name="fin" 
             value="<?php echo esc_attr($contrato->fin ?? ''); ?>" required>
    </div>
  </div>

  <!-- Link Drive -->
  <div class="thbr-campo">
    <label for="link_drive">Link de carpeta Drive</label>
    <input type="url" name="link_drive" 
           placeholder="https://..."
           value="<?php echo esc_attr($contrato->link_drive ?? ''); ?>">

    <?php if (!empty($contrato->link_drive)): ?>
    <p>
      <a href="<?php echo esc_url($contrato->link_drive); ?>" target="_blank" rel="noopener noreferrer">
        🔗 Abrir carpeta en Drive
      </a>
    </p>
    <?php endif; ?>

  </div>
  
</fieldset>
    
     <button type="submit">Guardar cambios</button>
  </form>
</div>

<?php get_footer(); ?>