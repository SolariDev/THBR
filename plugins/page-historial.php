<?php
// shortcode: [thbr_historial]
$id_usuario = get_current_user_id();

if ($id_usuario >= 0) {
  global $wpdb;
  $tabla = $wpdb->prefix . 'thbr_contratos';
  $tabla_usuarios = $wpdb->prefix . 'thbr_usuarios';

  $usuario = $wpdb->get_row(
      $wpdb->prepare("SELECT nombre, apellido FROM $tabla_usuarios WHERE id_usuario = %d", $id_usuario)
  );

if (isset($_GET['accion']) && $_GET['accion'] === 'papelera' && !empty($_GET['id'])) {
      $id = intval($_GET['id']);
      $resultado = $wpdb->update(
        $tabla,
        ['papelera' => 1], //guardado en papelera
        ['id' => $id]
      );

      if ($resultado !== false) {
          echo "<div class='thbr-exito'>Contrato con ID $id enviado a papelera correctamente.</div>";
      } else {
          echo "<div class='thbr-error'>No se pudo enviar el contrato con ID $id a la papelera.</div>";
      }
  }

  $contratos = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $tabla WHERE id_usuario = %d AND papelera = 0 ORDER BY fin ASC", $id_usuario)
  );
}
?>

<div style="max-width:960px; margin:0 auto; padding:20px 0; display:grid; grid-template-columns:1fr auto 1fr; 
    align-items:center; box-sizing:border-box;">
    <!-- Botones a la izquierda -->
  <div style="justify-self:start;">
    <a href="<?php echo home_url('/panel'); ?>" 
       style="margin-right: 12px; font-weight: 600; text-decoration: none; color: #1c35a5ff;">
       ⚙️ Panel
    </a>
    <a href="<?php echo home_url('/papelera'); ?>"
    style="font-weight: 600; text-decoration: none; color: #1c35a5ff;">
    🗑️ Ver Papelera
    </a>
  </div>

  <div style="justify-self:center;">
  <img src="<?php echo plugins_url( 'assets/logothbr.png', WP_PLUGIN_DIR . '/thbr/index.php' ); ?>" 
       alt="Logo TreeHouse" 
       style="max-width:120px; height:auto;" />
  </div>

    <!-- Usuario activo a la derecha -->
  <div style="justify-self:right; font-weight: 600; color: #1c35a5ff;">
    <?php echo $usuario ? esc_html($usuario->nombre . ' ' . $usuario->apellido) : 'No hay usuario registrado'; ?>
  </div>
</div>

<div class="thbr-historial" style="padding-top:0;">
  <h2>Historial de Contratos</h2>

  <?php if (!empty($contratos)): ?>
    <table class="thbr-tabla">
      <thead>
        <tr>
          <th>ID</th>
          <th>Dirección</th>
          <th>Propietario</th>
          <th>Inquilino</th>
          <th>Monto</th>
          <th>Reajuste</th>
          <th>Garantía</th>
          <th>Inicio</th>
          <th>Fin</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contratos as $c): 
          $hoy = new DateTime();
          $fin = new DateTime($c->fin);
          $diff = $hoy->diff($fin)->days;
          $color = '';

          if ($fin < $hoy) {
            $color = 'rojo';
          } elseif ($diff <= 90) {
            $color = 'amarillo';
          } else {
            $color = 'verde';
          } 

          // Definir estilo inline según color
          $estilo = '';
          if ($color === 'rojo') {
            $estilo = 'background-color:#C85C5C;
            color:#fff;';
          } elseif ($color === 'amarillo') {
            $estilo = 'background-color:#E6B84A;
            color:#000;';
          } elseif ($color === 'verde') {
            $estilo = 'background-color:#6DAE4F;
            color:#fff;';
          } 

          // Dirección completa con apto y garage si existen
          $direccionCompleta = $c->calle;

          if (!empty($c->numero)) {
            $direccionCompleta .= ' Nº ' . $c->numero;
          }

          if (!empty($c->manzana)) {
            $direccionCompleta .= ' M.' . $c->manzana;
          }

          if (!empty($c->solar)) {
            $direccionCompleta .= ' S.' . $c->solar;
          }

          $direccionCompleta .= ', ' . $c->barrio . ', ' . $c->departamento;

          if (!empty($c->apartamento)) {
            $direccionCompleta .= ' - Apto: ' . $c->apartamento;
          }

          if (!empty($c->garage)) {
            $direccionCompleta .= ' - Garage: ' . $c->garage;
          }


          // Monto y moneda
          $monto = number_format($c->precio_alquiler, 0, ',', '.');
          if ($c->moneda === 'UYU') {
            $monto .= ' $U';
          } elseif ($c->moneda === 'USD') {
            $monto .= ' $USD';
        }
        ?>
          <tr style="<?php echo $estilo; ?>">
            <td><?php echo esc_html($c->id); ?></td>
            <td><?php echo esc_html($direccionCompleta); ?></td>
            <td><?php echo esc_html($c->prop_nombre . ' ' . $c->prop_apellido); ?></td>
            <td><?php echo esc_html($c->inq_nombre . ' ' . $c->inq_apellido); ?></td>
            <td><?php echo esc_html($monto); ?></td>
            <td><?php echo esc_html($c->tipo_reajuste); ?></td>
            <td><?php echo esc_html($c->garantia); ?></td>
            <td><?php echo date('d/m/Y', strtotime($c->inicio)); ?></td>
            <td><?php echo date('d/m/Y', strtotime($c->fin)); ?></td>

            <td>
              <div class="thbr-acciones">
                <a href="<?php echo site_url('/editarcontrato?id=' . $c->id); ?>" title="Editar contrato">
                  <img src="<?php echo esc_url( content_url('plugins/thbr/assets/edit.png') ); ?>" 
                    alt="Editar" style="width:20px;">
                </a>
                <a href="?accion=papelera&id=<?php echo intval($c->id); ?>"
                  onclick="return confirm('🗑️ ¿Querés enviar el contrato <?php echo addslashes($c->id);?> a la papelera?');"
                  class="thbr-papelera" title="Enviar a papelera">
                  <img src="<?php echo esc_url( content_url('plugins/thbr/assets/basura.png') ); ?>" alt="Papelera" style="width:20px;">
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No hay contratos registrados.</p>
  <?php endif; ?>
</div>