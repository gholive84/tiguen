<?php
/**
 * Custom Post Types — Tiguen
 */

// CPT: Projetos
function tiguen_register_projetos() {
    register_post_type( 'projetos', [
        'labels' => [
            'name'               => 'Projetos',
            'singular_name'      => 'Projeto',
            'add_new'            => 'Adicionar Novo',
            'add_new_item'       => 'Adicionar Novo Projeto',
            'edit_item'          => 'Editar Projeto',
            'new_item'           => 'Novo Projeto',
            'view_item'          => 'Ver Projeto',
            'search_items'       => 'Buscar Projetos',
            'not_found'          => 'Nenhum projeto encontrado',
            'not_found_in_trash' => 'Nenhum projeto na lixeira',
            'menu_name'          => 'Projetos',
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
