<?php
/**
 * Header institucional para TREEHOUSE BIENES RAICES
 * Carga <head>, abre <body> y aplica estilos visuales sin dependencias externas. .
 *
 * @package HelloElementor
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$viewport_content = 'width=device-width, initial-scale=1';
$skip_link_url = '#content';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	 <!-- Estilos institucionales -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/thbr-estilos.css?v=1.0.0">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<!-- Accesibilidad -->
<a class="skip-link screen-reader-text" href="<?php echo esc_url( $skip_link_url ); ?>">Saltar al contenido</a>

<!-- Header institucional -->
<?php get_template_part( 'template-parts/header' ); ?>

