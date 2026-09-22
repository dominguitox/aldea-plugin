<?php
// Usar estilos del padre
add_action('wp_enqueue_scripts', 'onepress_child_enqueue_styles');
function onepress_child_enqueue_styles()
{
    wp_enqueue_style('onepress-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aldea-plugin-style', get_stylesheet_uri(), ['onepress-parent-style'], wp_get_theme()->get('Version'));

}
// ELIMINAR LA PALABRA "ARCHIVO:" DE LOS TÍTULOS
add_filter('get_the_archive_title_prefix', '__return_empty_string');

