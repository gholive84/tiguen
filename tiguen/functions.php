<?php
/**
 * Tiguen Theme Functions
 */

// Includes
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
require_once get_template_directory() . '/inc/media-import.php';
require_once get_template_directory() . '/inc/content-seeder.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/gallery-metabox.php';
require_once get_template_directory() . '/inc/admin-submissions.php';

// Meta boxes nativos só carregam se ACF não estiver ativo
if ( ! function_exists('acf_add_local_field_group') ) {
    require_once get_template_directory() . '/inc/meta-boxes.php';
}

// Setup do tema
function tiguen_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support( 'menus' );

    // Tamanhos de imagem
    add_image_size( 'projeto-thumb',   600, 450, true );
    add_image_size( 'projeto-hero',   1200, 600, true );
    add_image_size( 'projeto-galeria', 800, 600, true );
    add_image_size( 'equipe-thumb',    400, 400, true );

    register_nav_menus([
        'primary' => 'Menu Principal',
        'footer'  => 'Menu Rodapé',
    ]);
}
add_action( 'after_setup_theme', 'tiguen_setup' );

// Enqueue
function tiguen_scripts() {
    wp_enqueue_style( 'tiguen-style', get_stylesheet_uri(), [], '1.0.0' );
    wp_enqueue_style( 'tiguen-main',  get_template_directory_uri() . '/assets/css/main.css', [], '1.4.0' );
    wp_enqueue_script( 'tiguen-main', get_template_directory_uri() . '/assets/js/main.js', [ 'jquery' ], '1.4.0', true );

    wp_localize_script( 'tiguen-main', 'tiguenData', [
        'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
        'contatoNonce'   => wp_create_nonce( 'tiguen_contato_nonce' ),
        'curriculoNonce' => wp_create_nonce( 'tiguen_curriculo_nonce' ),
        'whatsapp'       => '5541305800377',
    ]);
}
add_action( 'wp_enqueue_scripts', 'tiguen_scripts' );

// Enqueue admin — media uploader nos meta boxes
function tiguen_admin_scripts( $hook ) {
    global $post_type;
    if ( $post_type === 'projetos' ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'tiguen_admin_scripts' );

// Widgets
function tiguen_widgets_init() {
    register_sidebar([
        'name'          => 'Sidebar',
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => 'Rodapé',
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action( 'widgets_init', 'tiguen_widgets_init' );

// Flush rewrite rules ao ativar tema
function tiguen_activate() {
    tiguen_register_projetos();
    tiguen_register_equipe();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tiguen_activate' );

// Auto-flush quando a versão de rewrite mudar
add_action( 'init', function() {
    if ( get_option('tiguen_rewrite_version') !== '1.2' ) {
        flush_rewrite_rules();
        update_option('tiguen_rewrite_version', '1.2');
    }
}, 999 );

// Forçar template correto para páginas por slug
add_filter( 'template_include', function( $template ) {
    if ( is_page('projetos') ) {
        $t = locate_template('page-projetos.php');
        if ( $t ) return $t;
    }
    return $template;
});

// Helper: embed YouTube/Vimeo
function tiguen_get_video_embed( $url ) {
    if ( empty( $url ) ) return '';
    if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $m ) ) {
        return '<iframe width="100%" height="450" src="https://www.youtube.com/embed/' . esc_attr($m[1]) . '" frameborder="0" allowfullscreen loading="lazy"></iframe>';
    }
    if ( preg_match( '/vimeo\.com\/(\d+)/', $url, $m ) ) {
        return '<iframe width="100%" height="450" src="https://player.vimeo.com/video/' . esc_attr($m[1]) . '" frameborder="0" allowfullscreen loading="lazy"></iframe>';
    }
    return '';
}
