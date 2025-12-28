<?php
/*
Template Name: Nuevo Contrato
*/
get_header();

session_start();
$usuario = $_SESSION['thbr_usuario'] ?? null;
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario) {
  global $wpdb;
  $tabla = $wpdb->prefix . 'thbr_contratos';

  // Construir tiempo_contrato desde años/meses (si vienen del formulario)
  $anios = isset($_POST['duracion_anios']) ? intval($_POST['duracion_anios']) : 0;
  $meses = isset($_POST['duracion_meses']) ? intval($_POST['duracion_meses']) : 0;
  
  $tiempo_contrato = '';
  if ($anios > 0) {
    $tiempo_contrato .= $anios . ' año' . ($anios > 1 ? 's' : '');
  }
  if ($meses > 0) {
    if ($tiempo_contrato !== '') $tiempo_contrato .= ' '; $tiempo_contrato .= $meses . ' mes' . ($meses > 1 ? 'es' : '');
  }
  if ($tiempo_contrato === '') {
    $tiempo_contrato = '0 meses';
  }

  $link_drive_input = $_POST['link_drive'] ?? '';
  $link_drive = filter_var($link_drive_input, FILTER_VALIDATE_URL) ? esc_url_raw($link_drive_input) : '';
 

  $datos = [
    'id_usuario'      => intval($usuario['id']),
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
    'duracion_anios'  => $anios,
    'duracion_meses'  => $meses,
    'tiempo_contrato' => $tiempo_contrato,
    'inicio'          => sanitize_text_field($_POST['inicio'] ?? ''),
    'fin'             => sanitize_text_field($_POST['fin'] ?? ''),
    'link_drive'      => $link_drive ? esc_url_raw($link_drive) : '',
    'tipo_reajuste'   => sanitize_text_field($_POST['tipo_reajuste'] ?? ''),
    'fecha_creacion'  => current_time('mysql')
  ];

  $resultado = $wpdb->insert($tabla, $datos);

  if ($resultado !== false){
    echo "<div class='thbr-exito'>Contrato registrado correctamente.</div>";
  } else {
    echo "<div class='thbr-error'>Error al registrar el contrato: " . esc_html($wpdb->last_error) . "</div>";
  }
  
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin: 30px 40px;">
  <!-- Botones a la izquierda -->
  <div>
    <a href="<?php echo home_url('/panel'); ?>" 
       style="margin-right: 12px; font-weight: 600; text-decoration: none; color: #1c35a5ff;">
       ⚙️ Panel


    </a>
    <a href="<?php echo home_url('/historial'); ?>" 
       style="font-weight: 600; text-decoration: none; color: #1c35a5ff;">
       📑 Historial

    </a>
  </div>

  <!-- Usuario activo a la derecha -->
  <div style="font-weight: 600; color: #1c35a5ff;">
    Usuario: <?php echo esc_html($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
  </div>
</div>

<div class="thbr-contrato">
  <h2>Nuevo Contrato</h2>

  <form method="post" class="thbr-form" autocomplete="off">

  <!-- Propiedad -->
<fieldset>
  <div class="thbr-legend"><legend>Propiedad</legend></div>

  <!-- Calle y N° de puerta -->
   <div class="thbr-doble">
    <div class="thbr-doble-item">
      <label for="calle">Calle</label>
      <input type="text" id="calle" name="calle" required>
    </div>
    <div class="thbr-doble-item">
      <label for="numero">N° de puerta</label>
      <input type="text" id="numero" name="numero">
    </div>
  </div>

  <!-- Manzana y Solar -->
  <div class="thbr-doble">
    <div class="thbr-doble-item">
      <label for="manzana">Manzana</label>
      <input type="text" id="manzana" name="manzana">
    </div>
    <div class="thbr-doble-item">
      <label for="solar">Solar</label>
      <input type="text" id="solar" name="solar">
    </div>
  </div>

  <!-- Barrio -->
   <div class="thbr-campo">
    <label for="barrio">Barrio / Localidad</label>
    <input type="text" id="barrio" name="barrio" required>
  </div>

  <!-- Departamento -->
  <div class="thbr-campo">
    <label for="departamento">Departamento</label>
    <select id="departamento" name="departamento" required>
      <option value="">Seleccione...</option>
      <option value="Artigas">Artigas</option>
      <option value="Canelones">Canelones</option>
      <option value="Cerro Largo">Cerro Largo</option>
      <option value="Colonia">Colonia</option>
      <option value="Durazno">Durazno</option>
      <option value="Flores">Flores</option>
      <option value="Florida">Florida</option>
      <option value="Lavalleja">Lavalleja</option>
      <option value="Maldonado">Maldonado</option>
      <option value="Montevideo">Montevideo</option>
      <option value="Paysandú">Paysandú</option>
      <option value="Río Negro">Río Negro</option>
      <option value="Rivera">Rivera</option>
      <option value="Rocha">Rocha</option>
      <option value="Salto">Salto</option>
      <option value="San José">San José</option>
      <option value="Soriano">Soriano</option>
      <option value="Tacuarembó">Tacuarembó</option>
      <option value="Treinta y Tres">Treinta y Tres</option>
    </select>
  </div>

  <!-- Apartamento y Garage -->
  <div class="thbr-doble">
    <div class="thbr-doble-item">
      <label for="apartamento">Apartamento</label>
      <input type="text" id="apartamento" name="apartamento">
    </div>
    <div class="thbr-doble-item">
      <label for="garage">Garage</label>
      <input type="text" id="garage" name="garage">
    </div>
  </div>
</fieldset>




    <!-- Propietario/a -->
    <fieldset>
      <div class="thbr-legend"><legend>Propietario/a</legend></div>

      <div class="thbr-doble">
        <div class="thbr-doble-item">
          <input type="text" name="prop_nombre" placeholder="Nombre" required>        
        </div>
        <div class="thbr-doble-item">
          <input type="text" name="prop_apellido" placeholder="Apellido" required>
        </div>
      </div>

      <div class="thbr-campo">
        <input type="tel" name="prop_telefono" placeholder="Teléfono de contacto" required>
      </div>

      <div class="thbr-campo">
        <input type="email" name="prop_mail" placeholder="Mail" required>
      </div>
    </fieldset>

    <!-- Inquilino/a -->
    <fieldset>
      <div class="thbr-legend"><legend>Inquilino/a</legend></div>

      <div class="thbr-doble">
        <div class="thbr-doble-item">
          <input type="text" name="inq_nombre" placeholder="Nombre" required>
        </div>
        <div class="thbr-doble-item">
          <input type="text" name="inq_apellido" placeholder="Apellido" required>
        </div>
      </div>
      
      <div class="thbr-campo">
        <input type="tel" name="inq_telefono" placeholder="Teléfono de contacto" required>
      </div>

      <div class="thbr-campo">
        <input type="email" name="inq_mail" placeholder="Mail" required>
      </div>
    </fieldset>

<!-- Condiciones -->
 
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
    <input type="number" id="precio_alquiler" name="precio_alquiler" step="1" min="0" value="0" required>
  </div>

  <!-- Tipo de reajuste -->
  <div class="thbr-fila">
    <label for="tipo_reajuste">Tipo de reajuste</label>
    <div class="thbr-opciones">
      <label><input type="radio" name="tipo_reajuste" value="IPC" required> IPC</label>
      <label><input type="radio" name="tipo_reajuste" value="URA"> URA</label>
      <label><input type="radio" name="tipo_reajuste" value="Ley 14.219"> Ley 14.219
</label>

    </div>
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
     <label for="duracion_anios" style="display:block; margin:20px 0 15px 22px; color: #1455bdff; font-weight:500; font-size:1rem; text-align:left;">Duración del contrato
     </label>
      
  <div class="thbr-doble">
    <div class="thbr-doble-item">
      <label for="duracion_anios">Años</label>
      <select id="duracion_anios" name="duracion_anios">
        <option value="0">0</option>
        <option value="1">1 año</option>
        <option value="2">2 años</option>
        <option value="3">3 años</option>
        <option value="4">4 años</option>
        <option value="5">5 años</option>
      </select>
    </div>
    <div class="thbr-doble-item">
      <label for="duracion_meses">Meses</label>
      <select id="duracion_meses" name="duracion_meses">
        <option value="0">0 meses</option>
        <option value="1">1 mes</option>
        <option value="2">2 meses</option>
        <option value="3">3 meses</option>
        <option value="4">4 meses</option>
        <option value="5">5 meses</option>
        <option value="6">6 meses</option>
        <option value="7">7 meses</option>
        <option value="8">8 meses</option>
        <option value="9">9 meses</option>
        <option value="10">10 meses</option>
        <option value="11">11 meses</option>
      </select>
    </div>
  </div>

<!-- Fechas -->
<div class="thbr-doble">
  <div class="thbr-doble-item">
    <label for="inicio">Fecha de inicio</label>
    <input type="date" id="inicio" name="inicio" required>
  </div>
  <div class="thbr-doble-item">
    <label for="fin">Fecha de término</label>
    <input type="date" id="fin" name="fin" readonly>
  </div>
</div>

  
  <!-- Link de carpeta Drive -->
  <div class="thbr-campo">
    <label for="link_drive">Link de carpeta Drive</label>
    <input type="url" id="link_drive" name="link_drive" placeholder="https://..." required>
  </div>  
</fieldset>    

    <!-- Botón -->
    <button type="submit">Guardar Contrato</button>
  </form>
</div>

<script> // Aquí va el script de cálculo automático de fechas 
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
