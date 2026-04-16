<?php
/**
 * AJAX Handlers — Formulários de Contato e Currículo
 * Salva submissões no WP (CPTs internos) e envia e-mail.
 */

// ─── FORMULÁRIO DE CONTATO ────────────────────────────────────────────────────

function tiguen_handle_contato() {
    check_ajax_referer( 'tiguen_contato_nonce', 'nonce' );

    $nome     = sanitize_text_field( $_POST['nome'] ?? '' );
    $email    = sanitize_email( $_POST['email'] ?? '' );
    $telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
    $mensagem = sanitize_textarea_field( $_POST['mensagem'] ?? '' );

    if ( ! $nome || ! $email || ! $mensagem ) {
        wp_send_json_error( [ 'message' => 'Preencha todos os campos obrigatórios.' ] );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'E-mail inválido.' ] );
    }

    // Salvar no WP como CPT interno
    $post_id = wp_insert_post([
        'post_type'   => 'contato_recebido',
        'post_title'  => $nome . ' — ' . wp_date( 'd/m/Y H:i' ),
        'post_status' => 'publish',
    ]);

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_contato_nome',     $nome );
        update_post_meta( $post_id, '_contato_email',    $email );
        update_post_meta( $post_id, '_contato_telefone', $telefone );
        update_post_meta( $post_id, '_contato_mensagem', $mensagem );
        update_post_meta( $post_id, '_contato_lido',     '0' );
    }

    // Enviar e-mail
    $to      = 'projetos@tiguen.com';
    $subject = 'Novo contato pelo site — ' . $nome;
    $body    = "Nome: {$nome}\nE-mail: {$email}\nTelefone: {$telefone}\n\nMensagem:\n{$mensagem}";
    $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$email}" ];

    wp_mail( $to, $subject, $body, $headers );

    wp_send_json_success( [ 'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.' ] );
}
add_action( 'wp_ajax_tiguen_contato',        'tiguen_handle_contato' );
add_action( 'wp_ajax_nopriv_tiguen_contato', 'tiguen_handle_contato' );


// ─── FORMULÁRIO DE CURRÍCULO ──────────────────────────────────────────────────

function tiguen_handle_curriculo() {
    check_ajax_referer( 'tiguen_curriculo_nonce', 'nonce' );

    $nome  = sanitize_text_field( $_POST['nome'] ?? '' );
    $email = sanitize_email( $_POST['email'] ?? '' );
    $cargo = sanitize_text_field( $_POST['cargo'] ?? '' );

    if ( ! $nome || ! $email ) {
        wp_send_json_error( [ 'message' => 'Nome e e-mail são obrigatórios.' ] );
    }

    // Processar upload do arquivo
    $arquivo_url  = '';
    $arquivo_path = '';
    $attachment   = [];

    if ( ! empty( $_FILES['curriculo']['name'] ) ) {
        $file = $_FILES['curriculo'];

        $allowed_types = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        if ( ! in_array( $file['type'], $allowed_types, true ) ) {
            wp_send_json_error( [ 'message' => 'Formato inválido. Envie PDF ou DOC/DOCX.' ] );
        }

        if ( $file['size'] > 5 * 1024 * 1024 ) {
            wp_send_json_error( [ 'message' => 'Arquivo muito grande. Máximo 5MB.' ] );
        }

        // Salvar em /uploads/curriculos/YYYY/MM/
        $upload_dir = wp_upload_dir();
        $subdir     = '/curriculos/' . date('Y/m');
        $dest_dir   = $upload_dir['basedir'] . $subdir;

        if ( ! file_exists( $dest_dir ) ) {
            wp_mkdir_p( $dest_dir );
        }

        // Nome seguro: timestamp + nome original sanitizado
        $ext       = pathinfo( $file['name'], PATHINFO_EXTENSION );
        $safe_name = sanitize_file_name( $nome ) . '_' . time() . '.' . $ext;
        $dest_path = $dest_dir . '/' . $safe_name;

        if ( move_uploaded_file( $file['tmp_name'], $dest_path ) ) {
            $arquivo_path = $dest_path;
            $arquivo_url  = $upload_dir['baseurl'] . $subdir . '/' . $safe_name;
            $attachment   = [ $dest_path ];
        }
    }

    // Salvar no WP como CPT interno
    $post_id = wp_insert_post([
        'post_type'   => 'curriculo_recebido',
        'post_title'  => $nome . ' — ' . wp_date( 'd/m/Y H:i' ),
        'post_status' => 'publish',
    ]);

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_curriculo_nome',    $nome );
        update_post_meta( $post_id, '_curriculo_email',   $email );
        update_post_meta( $post_id, '_curriculo_cargo',   $cargo );
        update_post_meta( $post_id, '_curriculo_arquivo_url',  $arquivo_url );
        update_post_meta( $post_id, '_curriculo_arquivo_path', $arquivo_path );
        update_post_meta( $post_id, '_curriculo_lido',    '0' );
    }

    // Enviar e-mail com anexo
    $to      = 'projetos@tiguen.com';
    $subject = 'Currículo recebido — ' . $nome;
    $body    = "Nome: {$nome}\nE-mail: {$email}\nCargo desejado: {$cargo}";
    $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$email}" ];

    wp_mail( $to, $subject, $body, $headers, $attachment );

    wp_send_json_success( [ 'message' => 'Currículo enviado com sucesso! Entraremos em contato.' ] );
}
add_action( 'wp_ajax_tiguen_curriculo',        'tiguen_handle_curriculo' );
add_action( 'wp_ajax_nopriv_tiguen_curriculo', 'tiguen_handle_curriculo' );
