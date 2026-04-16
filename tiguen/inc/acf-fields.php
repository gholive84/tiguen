<?php
/**
 * ACF — Registro de campos via PHP
 * Os campos aparecem automaticamente no editor sem precisar configurar pelo admin.
 */

if ( ! function_exists('acf_add_local_field_group') ) return;

// ─── PROJETOS ────────────────────────────────────────────────────────────────

acf_add_local_field_group([
    'key'      => 'group_projetos',
    'title'    => 'Dados do Projeto',
    'position' => 'normal',
    'style'    => 'default',
    'location' => [[
        [ 'param' => 'post_type', 'operator' => '==', 'value' => 'projetos' ],
    ]],
    'fields' => [

        [
            'key'           => 'field_projeto_galeria',
            'label'         => 'Galeria de Fotos',
            'name'          => 'galeria',
            'type'          => 'gallery',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'insert'        => 'append',
            'min'           => 0,
            'max'           => 0,
            'instructions'  => 'Adicione as fotos do projeto. A primeira será usada como destaque na galeria.',
        ],

        [
            'key'          => 'field_projeto_area',
            'label'        => 'Área (m²)',
            'name'         => 'area',
            'type'         => 'text',
            'placeholder'  => 'Ex: 350',
            'instructions' => 'Área total construída em metros quadrados.',
            'wrapper'      => [ 'width' => '33' ],
        ],

        [
            'key'          => 'field_projeto_ano',
            'label'        => 'Ano de Conclusão',
            'name'         => 'ano',
            'type'         => 'number',
            'placeholder'  => date('Y'),
            'min'          => 1990,
            'max'          => 2099,
            'wrapper'      => [ 'width' => '33' ],
        ],

        [
            'key'          => 'field_projeto_localizacao',
            'label'        => 'Localização',
            'name'         => 'localizacao',
            'type'         => 'text',
            'placeholder'  => 'Ex: São José dos Pinhais, PR',
            'wrapper'      => [ 'width' => '34' ],
        ],

        [
            'key'          => 'field_projeto_video_url',
            'label'        => 'Vídeo do Projeto (YouTube ou Vimeo)',
            'name'         => 'video_url',
            'type'         => 'url',
            'placeholder'  => 'https://www.youtube.com/watch?v=...',
            'instructions' => 'Cole o link do YouTube ou Vimeo. O embed é gerado automaticamente.',
        ],

    ],
]);

// ─── EQUIPE ───────────────────────────────────────────────────────────────────

acf_add_local_field_group([
    'key'      => 'group_equipe',
    'title'    => 'Dados do Profissional',
    'position' => 'side',
    'style'    => 'default',
    'location' => [[
        [ 'param' => 'post_type', 'operator' => '==', 'value' => 'equipe' ],
    ]],
    'fields' => [

        [
            'key'         => 'field_equipe_cargo',
            'label'       => 'Cargo / Especialidade',
            'name'        => 'cargo',
            'type'        => 'text',
            'placeholder' => 'Ex: Engenheiro Civil',
            'wrapper'     => [ 'width' => '100' ],
        ],

        [
            'key'         => 'field_equipe_linkedin',
            'label'       => 'LinkedIn',
            'name'        => 'linkedin',
            'type'        => 'url',
            'placeholder' => 'https://linkedin.com/in/...',
        ],

    ],
]);
