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
  $contratos = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $tabla WHERE id_usuario = %d ORDER BY fin ASC", $usuario['id'])
  );
}
?>

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
          if (!empty($c->precio_pesos)) {
            $monto = $c->precio_pesos . " Pesos";
          } elseif (!empty($c->precio_dolares)) {
            $monto = $c->precio_dolares . " USD";
          }
        ?>
          <tr class="estado-<?php echo $color; ?>">
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
                <a href="<?php echo site_url('/editar-contrato?id=' . $c->id); ?>">
                  <img src="<?php echo get_template_directory_uri(); ?>/thbr/assets/editar.png" alt="Editar">
                </a>
                <a href="<?php echo site_url('/eliminar-contrato?id=' . $c->id); ?>" onclick="return confirm('¿Seguro que deseas eliminar este contrato?');">
                  <img src="<?php echo get_template_directory_uri(); ?>/thbr/assets/eliminarcontrato.png" alt="Eliminar">
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
