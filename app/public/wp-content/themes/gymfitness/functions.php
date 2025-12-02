<?php
// se inyecta automaticamente en el head  de la pagina

function gymfitness_setup() {
    // Imagenes destacadas
    add_theme_support('post-thumbnails');

    // Títulos para SEO
    add_theme_support('title-tag');

}
add_action('after_setup_theme', 'gymfitness_setup');

// Agregar soporte para menus
function gymfitness_menus() {
    register_nav_menus( array(
        'menu-principal' => __('Menu Principal', 'gymfitness')
    ) );
}

// Hook para inicializar los menus
add_action('init', 'gymfitness_menus');

// Cargar la hoja de estilos
function  gymfitness_scripts_styles() {
    $normalize_url = 'https://necolas.github.io/normalize.css/8.0.1/normalize.css';
    $normalize_version = '8.0.1';
    wp_enqueue_style('normalize', $normalize_url, array(), $normalize_version);
    wp_enqueue_style('style', get_stylesheet_uri(), array('normalize'), '1.0.0');
}

add_action('wp_enqueue_scripts', 'gymfitness_scripts_styles');

?>