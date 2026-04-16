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
        'has_archive'       => true,
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

// CPT: Serviços
function tiguen_register_servicos() {
    register_post_type( 'servicos', [
        'labels' => [
            'name'          => 'Serviços',
            'singular_name' => 'Serviço',
            'add_new_item'  => 'Adicionar Serviço',
            'edit_item'     => 'Editar Serviço',
        ],
        'public'       => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-hammer',
        'menu_position'=> 7,
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'has_archive'  => false,
        'rewrite'      => [ 'slug' => 'servicos' ],
        'show_in_rest' => true,
    ]);
}
add_action( 'init', 'tiguen_register_servicos' );
