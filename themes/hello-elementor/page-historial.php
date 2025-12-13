<?php
/*
Template Name: Historial
*/
get_header();
session_start();

$usuario = $_SESSION['thbr_usuario'] ?? null;

if ($usuario) {
  global $wpdb;
  $tabla = $wpdb->prefix . 'thbr_contratos';

if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && !empty($_GET['id'])) {
      $id = intval($_GET['id']);
      $resultado = $wpdb->delete($tabla, ['id' => $id]);

      if ($resultado !== false) {
          echo "<div class='thbr-exito'>Contrato con ID $id eliminado correctamente.</div>";
      } else {
          echo "<div class='thbr-error'>No se pudo eliminar el contrato con ID $id.</div>";
      }
  }

  $contratos = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $tabla WHERE id_usuario = %d ORDER BY fin ASC", $usuario['id'])
  );
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin: 30px 40px;">
  <!-- Botones a la izquierda -->
  <div>
    <a href="<?php echo home_url('/panel'); ?>" 
       style="margin-right: 12px; font-weight: 600; text-decoration: none; color: #2c3e50;">
       ⚙️ Panel
    </a>
  </div>

  <!-- Usuario activo a la derecha -->
  <div style="font-weight: 600; color: #2c3e50;">
    Usuario: <?php echo esc_html($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
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
            $color = 'vencido';
          } elseif ($diff > 45) {
            $color = 'verde';
          } elseif ($diff <= 45 && $diff > 30) {
            $color = 'amarillo';
          } elseif ($diff <= 30 && $diff > 15) {
            $color = 'naranja';
          } elseif ($diff <= 15) {
            $color = 'rojo';
          }

          // Dirección completa con apto y garage si existen
          $direccionCompleta = $c->direccion;
          if (!empty($c->apartamento)) {
            $direccionCompleta .= " - Apto: " . $c->apartamento;
          }
          if (!empty($c->garage)) {
            $direccionCompleta .= " - Garage: " . $c->garage;
          }

          // Monto y moneda
          $monto = '';
          if (!empty($c->precio_alquiler) && !empty($c->moneda)) {
            $monto = $c->precio_alquiler . ' ' . $c-> moneda;
          }

        ?>
          <tr class="estado-<?php echo esc_attr($color); ?>">
            <td><?php echo esc_html($c->id); ?></td>
            <td><?php echo esc_html($direccionCompleta); ?></td>
            <td><?php echo esc_html($c->prop_nombre . ' ' . $c->prop_apellido); ?></td>
            <td><?php echo esc_html($c->inq_nombre . ' ' . $c->inq_apellido); ?></td>
            <td><?php echo esc_html($monto); ?></td>
            <td><?php echo esc_html($c->garantia); ?></td>
            <td><?php echo esc_html($c->inicio); ?></td>
            <td><?php echo esc_html($c->fin); ?></td>
            <td>
              <div class="thbr-acciones">
                <a href="<?php echo site_url('/editarcontrato?id=' . $c->id); ?>">
                  <img src="<?php echo esc_url( content_url('plugins/thbr/assets/edit.png') ); ?>" 
                    alt="Editar" style="width:20px;">
                </a>
                <a href="?accion=eliminar&id=<?php echo intval($c->id); ?>"
                    onclick="return confirm('❌ ¿Querés eliminar el contrato  <?php echo addslashes($c->id);?> ?');"
                    class="thbr-eliminar" title="Eliminar contrato">
                    <img src="<?php echo esc_url( content_url('plugins/thbr/assets/eliminarcontrato.png') ); ?>" alt="Eliminar" style="width:20px;">
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
