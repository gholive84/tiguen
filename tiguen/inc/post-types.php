<?php
/**
 * Custom Post Types — Tiguen
 */

// CPT: Projetos
function tiguen_register_projetos() {
    register_post_type( 'projetos', [
        'labels' => [
            'name'               => 'Obras',
            'singular_name'      => 'Obra',
            'add_new'            => 'Adicionar Nova',
            'add_new_item'       => 'Adicionar Nova Obra',
            'edit_item'          => 'Editar Obra',
            'new_item'           => 'Nova Obra',
            'view_item'          => 'Ver Obra',
            'search_items'       => 'Buscar Obras',
            'not_found'          => 'Nenhuma obra encontrada',
            'not_found_in_trash' => 'Nenhuma obra na lixeira',
            'menu_name'          => 'Obras',
        ],
        'public'            => true,
        'show_in_menu'      => true,
        'menu_icon'         => 'dashicons-building',
        'menu_position'     => 5,
        'supports'          => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'has_archive'       => false,
        'rewrite'           => [ 'slug' => 'projetos' ],
        'show_in_rest'      => true,
    ]);

    // Taxonomia: Tipo de Obra
    register_taxonomy( 'tipo_obra', 'projetos', [
        'labels' => [
            'name'          => 'Tipos de Obra',
            'singular_name' => 'Tipo de Obra',
            'add_new_item'  => 'Adicionar Tipo',
            'edit_item'     => 'Editar Tipo',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'tipo-obra' ],
        'show_in_rest' => true,
    ]);
}
add_action( 'init', 'tiguen_register_projetos' );

// CPT: Equipe
function tiguen_register_equipe() {
    register_post_type( 'equipe', [
        'labels' => [
            'name'          => 'Equipe',
            'singular_name' => 'Membro',
            'add_new_item'  => 'Adicionar Membro',
            'edit_item'     => 'Editar Membro',
        ],
        'public'       => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-groups',
        'menu_position'=> 6,
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'has_archive'  => false,
        'rewrite'      => [ 'slug' => 'equipe' ],
        'show_in_rest' => true,
    ]);
}
add_action( 'init', 'tiguen_register_equipe' );

// Serviços removido — conteúdo gerenciado diretamente na página de Serviços

// CPT: Contatos recebidos (submissões do formulário)
function tiguen_register_contatos() {
    register_post_type( 'contato_recebido', [
        'labels' => [
            'name'               => 'Contatos',
            'singular_name'      => 'Contato',
            'menu_name'          => 'Contatos',
            'all_items'          => 'Todos os Contatos',
            'edit_item'          => 'Ver Contato',
            'not_found'          => 'Nenhum contato recebido.',
            'not_found_in_trash' => 'Nenhum contato na lixeira.',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-email-alt',
        'menu_position'       => 25,
        'supports'            => [ 'title' ],
        'capability_type'     => 'post',
        'capabilities'        => [ 'create_posts' => 'do_not_allow' ],
        'map_meta_cap'        => true,
    ]);
}
add_action( 'init', 'tiguen_register_contatos' );

// CPT: Currículos recebidos (submissões do formulário)
function tiguen_register_curriculos() {
    register_post_type( 'curriculo_recebido', [
        'labels' => [
            'name'               => 'Currículos',
            'singular_name'      => 'Currículo',
            'menu_name'          => 'Currículos',
            'all_items'          => 'Todos os Currículos',
            'edit_item'          => 'Ver Currículo',
            'not_found'          => 'Nenhum currículo recebido.',
            'not_found_in_trash' => 'Nenhum currículo na lixeira.',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-id-alt',
        'menu_position'       => 26,
        'supports'            => [ 'title' ],
        'capability_type'     => 'post',
        'capabilities'        => [ 'create_posts' => 'do_not_allow' ],
        'map_meta_cap'        => true,
    ]);
}
add_action( 'init', 'tiguen_register_curriculos' );
