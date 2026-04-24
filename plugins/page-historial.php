<?php
// shortcode: [thbr_historial]

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['thbr_usuario'] ?? 0;

if ($id_usuario > 0) {
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

  // Acción para Notas
  if (isset($_GET['accion']) && $_GET['accion'] === 'notas' && !empty($_GET['id'])) {
      $contrato_id = intval($_GET['id']);
      $contrato = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabla WHERE id = %d", $contrato_id));

      if (isset($_POST['guardar_nota'])) {
          $resultado = $wpdb->update(
            $tabla,
            ['notas' => $_POST['notas']],
            ['id' => $contrato_id]
          );
          if ($resultado !== false) {
            echo "<script>alert('✅ Nota guardada correctamente');window.location='" . site_url('/historial') . "';</script>";
          } else {
            echo "<div class='thbr-error'>No se pudo guardar la nota.</div>";
          }
        }
        ?>
        <!-- Generar Modal de Notas con estilos -->
        <div id="modalNotas" class="modal" style="display:none;position:fixed;z-index:999;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.4);"> 
          <div class="modal-contenido" style="background: #fff url('/wordpress/wp-content/plugins/thbr/assets/logothbr.png') no-repeat right bottom; background-size:80px auto;margin:5% auto;padding: 30px;border-radius: 8px;width:70%;max-width:800px;box-shadow:0 0 15px rgba(0,0,0,0.3);text-align:left;font-family:'Montserrat',sans-serif;">
          <h2 style="font-size:20px;font-weight:600;margin-bottom:16px;color: #0056B3;">Notas del contrato #<?php echo      $contrato_id; ?>
          </h2>
            <form method="post">
              <textarea name="notas" rows="3" cols="40" style="width:100%;font-family:'Montserrat',sans-serif;font-size:14px;padding:10px;border:1px solid #ddd;border-radius:8px;resize:vertical;min-height:120px;background-color:rgba(255,255,255,0.9);color: #333;"><?php echo esc_textarea($contrato->notas); ?></textarea>
              <br>
              <button type="submit" name="guardar_nota" style="background-color: #0056B3;color: #fff;border:none;border-radius:8px;padding:8px 14px;font-size:16px;font-weight:500;font-family:'Montserrat',sans-serif;cursor:pointer;margin-top:12px;margin-right:10px;">Guardar</button>
              <button type="button" onclick="cerrarModal()" style="background-color: #0056B3;color: #fff;border:none;border-radius:8px;padding:8px 14px;font-size:16px;font-weight:500;font-family:'Montserrat',sans-serif;cursor:pointer;margin-top:12px;margin-right:10px;">Cerrar</button>
            </form>
          </div>
        </div>
        <script>
        function cerrarModal() {
          document.getElementById('modalNotas').style.display = 'none';
        }
        document.getElementById('modalNotas').style.display = 'block';
        </script>
        <?php
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

  <!-- Formulario de búsqueda -->
  <form method="get" action="" style="margin-bottom: 15px; text-align:center;">
    <input type="text" name="q"
             placeholder="Buscar por dirección, propietario o inquilino"
             style="padding:6px 10px; width:300px;">
    <button type="submit"
            style="padding:6px 12px; margin-left:6px; background:#1c35a5ff; color:#fff; border:none; border-radius:4px; font-weight:600;">
      🔍 Buscar Contratos
    </button>

  <!-- Botón para volver al historial completo -->
  <a href="<?php echo site_url('/historial'); ?>" 
     style="padding:6px 12px; margin-left:6px; background:#1c35a5ff; color:#fff; border:none; border-radius:4px; font-weight:600; text-decoration:none;">
     📁 Ver todos
  </a>
  </form>

  <!-- Lógica de filtrado -->
  <?php
  $q = isset($_GET['q']) ? trim($_GET['q']) : '';
  $contratosFiltrados = [];

  if (!empty($contratos)) {
      foreach ($contratos as $c) {
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
              $direccionCompleta .= ' - Garage ' . $c->garage;
          }

          // Propietario e inquilino
          $propietario = $c->prop_nombre . ' ' . $c->prop_apellido;
          $inquilino   = $c->inq_nombre . ' ' . $c->inq_apellido;

          // Filtrado
          if ($q === ''
              || stripos($direccionCompleta, $q) !== false
              || stripos($propietario, $q) !== false
              || stripos($inquilino, $q) !== false) {
              $contratosFiltrados[] = $c;
          }
      }
  }
  ?>

  <?php if (!empty($contratosFiltrados)): ?>
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
          <th>Notas</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contratosFiltrados as $c): 
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

          // Dirección completa
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
            $direccionCompleta .= ' - Garage ' . $c->garage;
          }

          // Monto y moneda
          $monto = number_format($c->precio_alquiler, 0, ',', '.');

          if ($c->moneda === 'UYU') {
            $monto = '$U ' . $monto;
          } elseif ($c->moneda === 'USD') {
            $monto = '$USD ' . $monto;
        }
        ?>
          <tr style="<?php echo $estilo; ?>">
            <td><?php echo esc_html($c->id); ?></td>
            <td class="td-direccion" title="<?php echo esc_html($direccionCompleta); ?>">
              <?php 
                $direccionResumida = mb_substr($direccionCompleta, 0, 40);
                $direccionResumida = preg_replace('/\s+\S*$/u', '', $direccionResumida);
                echo esc_html($direccionResumida . '...');
              ?>
            </td>
            <td><?php echo esc_html($c->prop_nombre . ' ' . $c->prop_apellido); ?></td>
            <td><?php echo esc_html($c->inq_nombre . ' ' . $c->inq_apellido); ?></td>
            <td class="td-monto"><?php echo esc_html($monto); ?></td>
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

            <td>
              <a href="<?php echo add_query_arg(['accion' => 'notas', 'id'=> $c->id]); ?>" title="Notas">
                <img src="<?php echo esc_url( content_url('plugins/thbr/assets/notas.png') ); ?>"
                  alt="Notas" style="width: 20px;">
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <?php if (empty($contratos)): ?>
        <p>No hay contratos registrados.</p>
    <?php else: ?>
        <p>No se encontraron resultados para la búsqueda.</p>
    <?php endif; ?>
    <?php endif; ?>
</div>