<?php
/*
Plugin Name: THBR
Description: Aplicación para la gestión de contratos de alquiler, mostrando un historial ordenado por nivel de urgencia según la proximidad de la fecha de vencimiento.
Version: 1.0.0
Author: Gabriel Solari
License: GPL2
*/

// Seguridad básica: evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Encolar estilos del plugin
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'thbr-estilos',
        plugins_url('thbr-estilos.css', __FILE__),
        [],
        '1.0.0'
    );
});

add_shortcode('thbr_inicio', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'page-inicio.php';
    return ob_get_clean();
});

add_shortcode('thbr_registro', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'page-registro.php';
    return ob_get_clean();
});

add_shortcode('thbr_ingresar', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'page-ingresar.php';
    return ob_get_clean();
});

add_shortcode('thbr_panel', function () {
    ob_start();

    $id_usuario = get_current_user_id();
    
    if ($id_usuario <= 0) {
        echo "<div class='thbr-error'>Acceso no autorizado. Iniciá sesión primero.</div>";
        echo "<script>setTimeout(() => window.location.href='" . home_url('/ingresar') . "', 1500);</script>";
        return ob_get_clean();
    }

    include plugin_dir_path(__FILE__) . 'page-panel.php';
    return ob_get_clean();
});

add_shortcode('thbr_nuevocontrato', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'page-nuevocontrato.php';
    return ob_get_clean();
});

add_shortcode('thbr_historial', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'page-historial.php';
    return ob_get_clean();
});

add_shortcode('thbr_editarcontrato', function () {
    ob_start();

    $id_usuario = get_current_user_id();
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    global $wpdb;
    $tabla_usuarios = $wpdb->prefix . 'thbr_usuarios';

    $usuario = $wpdb->get_row(
        $wpdb->prepare("SELECT nombre, apellido FROM $tabla_usuarios WHERE id_usuario = %d", $id_usuario)
        );
    
    if (!$usuario || $id <= 0 ) {
        echo "<div class='thbr-error'>Acceso no autorizado.</div>";
        return ob_get_clean();
    }

    include plugin_dir_path(__FILE__) . 'page-editarcontrato.php';
    return ob_get_clean();
});

add_shortcode('thbr_papelera', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'papeleracontratos.php';
    return ob_get_clean();
});

add_shortcode('thbr_cerrarsesion', function () {
    ob_start();
    include plugin_dir_path(__FILE__) . 'cerrarsesion.php';
    return ob_get_clean();
});