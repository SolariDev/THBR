<?php
/*
Template Name: Editar Contrato
*/
get_header();

session_start();
$usuario = $_SESSION['thbr_usuario'] ?? null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

session_write_close();

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
    'calle'           => sanitize_text_field($_POST['calle'] ?? ''), 
    'numero'          => sanitize_text_field($_POST['numero'] ?? ''),
    'manzana'         => sanitize_text_field($_POST['manzana'] ?? ''),
    'solar'           => sanitize_text_field($_POST['solar'] ?? ''),
    'barrio'          => sanitize_text_field($_POST['barrio'] ?? ''),
    'departamento'    => sanitize_text_field($_POST['departamento'] ?? ''),
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
       style="margin-right:12px;font-weight:600;text-decoration:none;color: #1c35a5ff;">⚙️ Panel</a>
    <a href="<?php echo home_url('/historial'); ?>" 
       style="font-weight:600;text-decoration:none;color: #1c35a5ff;">📂 Historial</a>
  </div>
  <!-- Usuario activo a la derecha -->
  <div style="font-weight:600;color: #1c35a5ff;">
    <?php echo esc_html($usuario['nombre'].' '.$usuario['apellido']); ?>
  </div>
</div>

<div class="thbr-contrato">
  <h2>Editar Contrato</h2>

  <form method="post" class="thbr-form" autocomplete="off">

    <!-- Propiedad -->
   
  <fieldset>
    <div class="thbr-legend"><legend>Propiedad</legend></div>

    <div class="thbr-doble">
      <div class="thbr-doble-item">
        <label for="calle">Calle</label>
        <input type="text" id="calle" name="calle" value="<?php echo esc_attr($contrato->calle); ?>" required>
      </div>
      <div class="thbr-doble-item">      
          <label for="numero">N° de puerta</label>
          <input type="text" id="numero" name="numero" value="<?php echo esc_attr($contrato->numero); ?>">
      </div>
    </div>

    <div class="thbr-doble">
      <div class="thbr-doble-item">
        <label for="manzana">Manzana</label>
        <input type="text" id="manzana" name="manzana" value="<?php echo esc_attr($contrato->manzana); ?>">
      </div>
      <div class="thbr-doble-item">
        <label for="solar">Solar</label>
        <input type="text" id="solar" name="solar" value="<?php echo esc_attr($contrato->solar); ?>">
      </div>
    </div>

    <div class="thbr-campo">
      <label for="barrio">Barrio / Localidad</label>
      <input type="text" id="barrio" name="barrio" value="<?php echo esc_attr($contrato->barrio); ?>" required>
    </div>

    <div class="thbr-campo">
      <label for="departamento">Departamento</label>
      <select id="departamento" name="departamento" required>
      <?php
        $departamentos = ['Montevideo','Canelones','Maldonado','Colonia','San José','Soriano','Flores','Florida','Durazno','Lavalleja','Treinta y Tres','Rocha','Rivera','Tacuarembó','Artigas','Paysandú','Salto', 'Cerro Largo', 'Río Negro'];
        foreach ($departamentos as $d) {
          echo '<option value="'.esc_attr($d).'" '.selected($contrato->departamento ?? '', $d, false).'>'.$d.'</option>';
        }
      ?>
      </select>
    </div>

    <div class="thbr-doble">
      <div class="thbr-doble-item">
        <label for="apartamento">Apartamento</label>
        <input type="text" id="apartamento" name="apartamento" value="<?php echo esc_attr($contrato->apartamento); ?>">
      </div>
      <div class="thbr-doble-item">
        <label for="garage">Garage</label>
        <input type="text" id="garage" name="garage" value="<?php echo esc_attr($contrato->garage); ?>">
      </div>
    </div>
  </fieldset>

    <!-- Propietario/a -->
  <fieldset>
    <div class="thbr-legend"><legend>Propietario/a</legend></div>

    <div class="thbr-doble">
      <div class="thbr-doble-item">
        <input type="text" name="prop_nombre" value="<?php echo esc_attr($contrato->prop_nombre); ?>" required>
      </div>
      <div class="thbr-doble-item"> 
        <input type="text" name="prop_apellido" value="<?php echo esc_attr($contrato->prop_apellido); ?>" required>
      </div>
    </div>

    <div class="thbr-campo">
      <input type="tel" name="prop_telefono" value="<?php echo esc_attr($contrato->prop_telefono); ?>" required>
    </div>
    <div class="thbr-campo">
      <input type="email" name="prop_mail" value="<?php echo esc_attr($contrato->prop_mail); ?>" required>
    </div>
  </fieldset>

    <!-- Inquilino/a -->
  <fieldset>
    <div class="thbr-legend"><legend>Inquilino/a</legend></div>

    <div class="thbr-doble">
      <div class="thbr-doble-item">
        <input type="text" name="inq_nombre" value="<?php echo esc_attr($contrato->inq_nombre); ?>" required>
      </div>
      <div class="thbr-doble-item">
        <input type="text" name="inq_apellido" value="<?php echo esc_attr($contrato->inq_apellido); ?>" required>
      </div>
    </div>

    <div class="thbr-campo">
      <input type="tel" name="inq_telefono" value="<?php echo esc_attr($contrato->inq_telefono); ?>" required>
    </div>
    <div class="thbr-campo">
      <input type="email" name="inq_mail" value="<?php echo esc_attr($contrato->inq_mail); ?>" required>
    </div>
  </fieldset>

    <!-- Condiciones -->
  <fieldset>
    <div class="thbr-legend"><legend>Condiciones del contrato</legend></div>

    <!-- Precio del alquiler -->
  <div class="thbr-fila">
    <label for="precio_alquiler">Precio del alquiler</label>
    <div class="thbr-opciones">
      <label>
        <input type="radio" name="moneda" value="UYU" <?php checked($contrato->moneda ?? '', 'UYU'); ?> required> $U
      </label>
      <label>
        <input type="radio" name="moneda" value="USD" <?php checked($contrato->moneda ?? '', 'USD'); ?>> $USD
      </label>
    </div>
  </div>

  <div class="thbr-campo">
    <input type="number" name="precio_alquiler" step="1" min="0" 
           value="<?php echo esc_attr($contrato->precio_alquiler ?? ''); ?>" required>
  </div>

    <!-- Tipo de reajuste -->
  <div class="thbr-fila">
    <label for="tipo_reajuste">Tipo de reajuste</label>
    <div class="thbr-opciones">
      <label>
        <input type="radio" name="tipo_reajuste" value="IPC" <?php checked($contrato->tipo_reajuste ?? '', 'IPC'); ?> required> IPC
      </label>
      <label>
        <input type="radio" name="tipo_reajuste" value="URA" <?php checked($contrato->tipo_reajuste ?? '', 'URA'); ?>> URA
      </label>
      <label><input type="radio" name="tipo_reajuste" value="Ley 14.219" <?php checked($contrato->tipo_reajuste ?? '', 'Ley 14.219'); ?>> Ley 14.219
      </label>
    </div>
  </div>

  <!-- Garantía -->
  <div class="thbr-campo">
    <label for="garantia">Garantía</label>
    <select id="garantia" name="garantia" required>
      <?php
      $garantias = ['PORTO SEGURO','MAPFRE','SURA','ANDA','CGM','DEPÓSITO EN BHU','PROPIEDAD'];
      foreach ($garantias as $g) {
        echo '<option value="'.esc_attr($g).'" '.selected($contrato->garantia ?? '', $g, false).'>'.$g.'</option>';
      }
      ?>
    </select>
  </div>

  <!-- Tiempo de contrato -->

  <label for="duracion_anios" style="display:block; margin:20px 0 15px 22px; color: #1455bdff; font-weight:500; font-size:1rem; text-align:left;">Duración del contrato
     </label>

  <div class="thbr-doble">
    <div class="thbr-doble-item">
      <label for="duracion_anios">Años</label>
      <select id="duracion_anios" name="duracion_anios">
        <?php for ($i=0; $i<=5; $i++): ?>
          <option value="<?php echo $i; ?>" <?php selected($contrato->duracion_anios ?? '', $i); ?>>
            <?php echo $i === 0 ? '0' : $i.' año'.($i>1?'s':''); ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="thbr-doble-item">
      <label for="duracion_meses">Meses</label>
      <select id="duracion_meses" name="duracion_meses">
        <?php for ($m=0; $m<=11; $m++): ?>
          <option value="<?php echo $m; ?>"
            <?php selected($contrato->duracion_meses ?? '', $m); ?>>
            <?php echo $m.' mes'.($m!=1?'es':''); ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>
  </div>

    <!-- Fechas -->
  <div class="thbr-doble">
    <div class="thbr-doble-item">
      <label for="inicio">Fecha de inicio</label>
      <input type="date" id="inicio"  name="inicio" 
             value="<?php echo esc_attr($contrato->inicio ?? ''); ?>" required>
    </div>
    <div class="thbr-doble-item">
      <label for="fin">Fecha de término</label>
      <input type="date" id="fin"  name="fin" 
             value="<?php echo esc_attr($contrato->fin ?? ''); ?>" readonly>
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

<script>
function calcularFechaFin() {
  const inicio = document.getElementById('inicio').value;
  const anios = parseInt(document.getElementById('duracion_anios').value);
  const meses = parseInt(document.getElementById('duracion_meses').value);

  if (!inicio) return;

  const fechaInicio = new Date(inicio);
  const fechaFin = new Date(fechaInicio);
  fechaFin.setFullYear(fechaFin.getFullYear() + anios);
  fechaFin.setMonth(fechaFin.getMonth() + meses);

  const dia = fechaInicio.getDate();
  if (fechaFin.getDate() !== dia) {
    fechaFin.setDate(0);
  }

  const yyyy = fechaFin.getFullYear();
  const mm = String(fechaFin.getMonth() + 1).padStart(2, '0');
  const dd = String(fechaFin.getDate()).padStart(2, '0');
  document.getElementById('fin').value = `${yyyy}-${mm}-${dd}`;
}

document.getElementById('inicio').addEventListener('change', calcularFechaFin);
document.getElementById('duracion_anios').addEventListener('change', calcularFechaFin);
document.getElementById('duracion_meses').addEventListener('change', calcularFechaFin);
</script>

<?php get_footer(); ?>