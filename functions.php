<?php
/**
 * Tiguen Theme Functions
 */

// Suporte a recursos do tema
function tiguen_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'menus' );

    register_nav_menus([
        'primary' => __( 'Menu Principal', 'tiguen' ),
        'footer'  => __( 'Menu Rodapé', 'tiguen' ),
    ]);
}
add_action( 'after_setup_theme', 'tiguen_setup' );

// Enqueue de estilos e scripts
function tiguen_scripts() {
    wp_enqueue_style( 'tiguen-style', get_stylesheet_uri(), [], '1.0.0' );
    wp_enqueue_style( 'tiguen-main', get_template_directory_uri() . '/assets/css/main.css', [], '1.0.0' );
    wp_enqueue_script( 'tiguen-main', get_template_directory_uri() . '/assets/js/main.js', [], '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'tiguen_scripts' );

// Custom Post Types — adicionar aqui
// include get_template_directory() . '/inc/post-types.php';

// Widgets
function tiguen_widgets_init() {
    register_sidebar([
        'name'          => __( 'Sidebar', 'tiguen' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action( 'widgets_init', 'tiguen_widgets_init' );
