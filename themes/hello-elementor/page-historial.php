<?php
/*
Template Name: Historial
*/
get_header();
session_start();

$usuario = $_SESSION['thbr_usuario'] ?? null;

session_write_close();

if ($usuario) {
  global $wpdb;
  $tabla = $wpdb->prefix . 'thbr_contratos';

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
    $wpdb->prepare("SELECT * FROM $tabla WHERE id_usuario = %d AND papelera = 0 ORDER BY fin ASC", $usuario['id'])
  );
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin: 30px 40px;">
    <!-- Botones a la izquierda -->
  <div>
    <a href="<?php echo home_url('/panel'); ?>" 
       style="margin-right: 12px; font-weight: 600; text-decoration: none; color: #1c35a5ff;">
       ⚙️ Panel
    </a>
    <a href="<?php echo home_url('/papeleracontratos'); ?>"
    style="font-weight: 600; text-decoration: none; color: #1c35a5ff;">
    🗑️ Ver Papelera
    </a>
  </div>

    <!-- Usuario activo a la derecha -->
  <div style="font-weight: 600; color: #1c35a5ff;">
    <?php echo esc_html($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
  </div>
</div>

<div class="thbr-historial">
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
          $monto = '';
          if (!empty($c->precio_alquiler) && !empty($c->moneda)) {
            $monto = $c->precio_alquiler . ' ' . $c-> moneda;
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
                <a href="<?php echo site_url('/editarcontrato?id=' . $c->id); ?>">
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

<?php get_footer(); ?>