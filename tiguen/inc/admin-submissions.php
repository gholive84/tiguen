<?php
/**
 * Admin — Colunas e visualização das submissões de Contatos e Currículos
 */

// ─── CONTATOS — COLUNAS ──────────────────────────────────────────────────────

add_filter( 'manage_contato_recebido_posts_columns', function( $cols ) {
    return [
        'cb'                  => '<input type="checkbox">',
        'title'               => 'Nome / Data',
        'contato_email'       => 'E-mail',
        'contato_telefone'    => 'Telefone',
        'contato_mensagem'    => 'Mensagem',
        'contato_lido'        => 'Status',
        'date'                => 'Recebido em',
    ];
});

add_action( 'manage_contato_recebido_posts_custom_column', function( $col, $post_id ) {
    switch ( $col ) {
        case 'contato_email':
            $v = get_post_meta( $post_id, '_contato_email', true );
            echo $v ? '<a href="mailto:' . esc_attr($v) . '">' . esc_html($v) . '</a>' : '—';
            break;
        case 'contato_telefone':
            echo esc_html( get_post_meta( $post_id, '_contato_telefone', true ) ?: '—' );
            break;
        case 'contato_mensagem':
            $msg = get_post_meta( $post_id, '_contato_mensagem', true );
            echo '<span title="' . esc_attr($msg) . '">' . esc_html( wp_trim_words( $msg, 12 ) ) . '</span>';
            break;
        case 'contato_lido':
            $lido = get_post_meta( $post_id, '_contato_lido', true );
            if ( $lido ) {
                echo '<span style="color:#16a34a;font-weight:600;">✓ Lido</span>';
            } else {
                echo '<span style="color:#dc2626;font-weight:600;">● Novo</span>';
            }
            break;
    }
}, 10, 2 );

// Marcar como lido ao abrir
add_action( 'load-post.php', function() {
    if ( isset($_GET['post']) && get_post_type( intval($_GET['post']) ) === 'contato_recebido' ) {
        update_post_meta( intval($_GET['post']), '_contato_lido', '1' );
    }
});

// Meta box de detalhes do contato
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'contato_detalhes',
        'Dados do Contato',
        'tiguen_render_contato_detail_box',
        'contato_recebido',
        'normal',
        'high'
    );
});

function tiguen_render_contato_detail_box( $post ) {
    $nome     = get_post_meta( $post->ID, '_contato_nome',     true );
    $email    = get_post_meta( $post->ID, '_contato_email',    true );
    $telefone = get_post_meta( $post->ID, '_contato_telefone', true );
    $mensagem = get_post_meta( $post->ID, '_contato_mensagem', true );
    ?>
    <table class="form-table" style="margin:0">
        <tr>
            <th style="width:120px">Nome</th>
            <td><?php echo esc_html( $nome ); ?></td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td>
        </tr>
        <tr>
            <th>Telefone</th>
            <td><?php echo esc_html( $telefone ?: '—' ); ?></td>
        </tr>
        <tr>
            <th>Mensagem</th>
            <td style="white-space:pre-wrap"><?php echo esc_html( $mensagem ); ?></td>
        </tr>
    </table>
    <?php
    // Marcar como lido ao visualizar
    update_post_meta( $post->ID, '_contato_lido', '1' );
}


// ─── CURRÍCULOS — COLUNAS ────────────────────────────────────────────────────

add_filter( 'manage_curriculo_recebido_posts_columns', function( $cols ) {
    return [
        'cb'               => '<input type="checkbox">',
        'title'            => 'Nome / Data',
        'curriculo_email'  => 'E-mail',
        'curriculo_cargo'  => 'Cargo desejado',
        'curriculo_arquivo'=> 'Currículo',
        'curriculo_lido'   => 'Status',
        'date'             => 'Recebido em',
    ];
});

add_action( 'manage_curriculo_recebido_posts_custom_column', function( $col, $post_id ) {
    switch ( $col ) {
        case 'curriculo_email':
            $v = get_post_meta( $post_id, '_curriculo_email', true );
            echo $v ? '<a href="mailto:' . esc_attr($v) . '">' . esc_html($v) . '</a>' : '—';
            break;
        case 'curriculo_cargo':
            echo esc_html( get_post_meta( $post_id, '_curriculo_cargo', true ) ?: '—' );
            break;
        case 'curriculo_arquivo':
            $url = get_post_meta( $post_id, '_curriculo_arquivo_url', true );
            if ( $url ) {
                $ext = strtoupper( pathinfo( $url, PATHINFO_EXTENSION ) );
                echo '<a href="' . esc_url($url) . '" target="_blank" style="font-weight:600">⬇ Baixar ' . esc_html($ext) . '</a>';
            } else {
                echo '—';
            }
            break;
        case 'curriculo_lido':
            $lido = get_post_meta( $post_id, '_curriculo_lido', true );
            if ( $lido ) {
                echo '<span style="color:#16a34a;font-weight:600;">✓ Lido</span>';
            } else {
                echo '<span style="color:#dc2626;font-weight:600;">● Novo</span>';
            }
            break;
    }
}, 10, 2 );

// Meta box de detalhes do currículo
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'curriculo_detalhes',
        'Dados do Candidato',
        'tiguen_render_curriculo_detail_box',
        'curriculo_recebido',
        'normal',
        'high'
    );
});

function tiguen_render_curriculo_detail_box( $post ) {
    $nome    = get_post_meta( $post->ID, '_curriculo_nome',        true );
    $email   = get_post_meta( $post->ID, '_curriculo_email',       true );
    $cargo   = get_post_meta( $post->ID, '_curriculo_cargo',       true );
    $arq_url = get_post_meta( $post->ID, '_curriculo_arquivo_url', true );
    ?>
    <table class="form-table" style="margin:0">
        <tr>
            <th style="width:140px">Nome</th>
            <td><?php echo esc_html( $nome ); ?></td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td>
        </tr>
        <tr>
            <th>Cargo desejado</th>
            <td><?php echo esc_html( $cargo ?: '—' ); ?></td>
        </tr>
        <tr>
            <th>Currículo</th>
            <td>
                <?php if ( $arq_url ) : ?>
                    <a href="<?php echo esc_url($arq_url); ?>" target="_blank" class="button button-primary">
                        ⬇ Baixar arquivo
                    </a>
                <?php else : ?>
                    Nenhum arquivo enviado.
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
    update_post_meta( $post->ID, '_curriculo_lido', '1' );
}

// Remover meta box de publicação (são só leitura)
add_action( 'admin_menu', function() {
    remove_meta_box( 'submitdiv', 'contato_recebido',   'side' );
    remove_meta_box( 'submitdiv', 'curriculo_recebido', 'side' );
});
