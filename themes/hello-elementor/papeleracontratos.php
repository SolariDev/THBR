<?php
/*
Template Name: Papelera
*/
get_header();
session_start();

$usuario = $_SESSION['thbr_usuario'] ?? null;
session_write_close();

if (!$usuario) {
  wp_redirect(home_url('/login'));
  exit;
}

global $wpdb;
$tabla = $wpdb->prefix . 'thbr_contratos';

// Acción restaurar
if (isset($_GET['accion']) && $_GET['accion'] === 'restaurar' && !empty($_GET['id'])) {
  $id = intval($_GET['id']);
  $resultado = $wpdb->update($tabla, ['papelera' => 0], ['id' => $id]);

  if ($resultado !== false) {
    echo "<div class='thbr-exito'>Contrato con ID $id restaurado correctamente.</div>";
  } else {
    echo "<div class='thbr-error'>No se pudo restaurar el contrato con ID $id.</div>";
  }
}

// Acción eliminar definitiva
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && !empty($_GET['id'])) {
  $id = intval($_GET['id']);
  $resultado = $wpdb->delete($tabla, ['id' => $id, 'id_usuario' => $usuario['id']]);
  
  if ($resultado > 0) {
    echo "<div class='thbr-exito'>Contrato con ID $id eliminado definitivamente.</div>";
  } else {
    echo "<div class='thbr-error'>No se pudo eliminar el contrato con ID $id.</div>";
  }
}

// Traer contratos en papelera del usuario actual
$contratos = $wpdb->get_results(
  $wpdb->prepare("SELECT * FROM $tabla WHERE id_usuario = %d AND papelera = 1 ORDER BY fin ASC", $usuario['id'])
);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin: 30px 40px;">
  <div>
    <a href="<?php echo home_url('/panel'); ?>" 
       style="margin-right: 12px; font-weight: 600; text-decoration: none; color: #1c35a5ff;">
       ⚙️ Panel
    </a>
    <a href="<?php echo home_url('/historial'); ?>" 
       style="font-weight: 600; text-decoration: none; color: #1c35a5ff;">
       📂 Historial
    </a>
  </div>
  <div style="font-weight: 600; color: #1c35a5ff;">
    <?php echo esc_html($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
  </div>
</div>

<div class="thbr-historial">
  <h2 style="color: #1c35a5ff; margin-left:40px;">🗂️ Contratos en Papelera</h2>

  <?php if (!empty($contratos)): ?>
    <table class="thbr-tabla">
      <thead>
        <tr>
          <th>ID</th>
          <th>Dirección</th>
          <th>Propietario</th>
          <th>Inquilino</th>
          <th>Inicio</th>
          <th>Fin</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contratos as $c): 
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
        ?>
          <tr style="background-color:#eaeaea; color:#333;">
            <td><?php echo esc_html($c->id); ?></td>
            <td><?php echo esc_html($direccionCompleta); ?></td>
            <td><?php echo esc_html($c->prop_nombre . ' ' . $c->prop_apellido); ?></td>
            <td><?php echo esc_html($c->inq_nombre . ' ' . $c->inq_apellido); ?></td>
            <td><?php echo date('d/m/Y', strtotime($c->inicio)); ?></td>
            <td><?php echo date('d/m/Y', strtotime($c->fin)); ?></td>
            <td>
        <!-- Restaurar -->
              <a href="?accion=restaurar&id=<?php echo intval($c->id); ?>"
                 onclick="return confirm('♻️ ¿Querés restaurar el contrato <?php echo addslashes($c->id);?> al historial?');"
                 title="Restaurar contrato">
                <img src="<?php echo esc_url( content_url('plugins/thbr/assets/devolver.png') ); ?>" alt="Restaurar" style="width:20px;">
              </a>
        <!-- Eliminar -->
              <a href="?accion=eliminar&id=<?php echo intval($c->id); ?>"
                 onclick="return confirm('❌ ¿Seguro que querés eliminar definitivamente el contrato <?php echo addslashes($c->id);?>? Esta acción no se puede deshacer.');"
                 title="Eliminar definitivamente">
                 <img src="<?php echo esc_url( content_url('plugins/thbr/assets/eliminarcontrato.png') ); ?>" alt="Eliminar" style="width:20px;">
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="margin-left:40px;">No hay contratos en papelera.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>

