<?php
/**
 * AJAX Handlers — Formulários de Contato e CV
 */

// Formulário de Contato
function tiguen_handle_contato() {
    check_ajax_referer( 'tiguen_contato_nonce', 'nonce' );

    $nome    = sanitize_text_field( $_POST['nome'] ?? '' );
    $email   = sanitize_email( $_POST['email'] ?? '' );
    $telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
    $mensagem = sanitize_textarea_field( $_POST['mensagem'] ?? '' );

    if ( ! $nome || ! $email || ! $mensagem ) {
        wp_send_json_error( [ 'message' => 'Preencha todos os campos obrigatórios.' ] );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'E-mail inválido.' ] );
    }

    $to      = 'projetos@tiguen.com';
    $subject = 'Novo contato pelo site — ' . $nome;
    $body    = "Nome: {$nome}\nE-mail: {$email}\nTelefone: {$telefone}\n\nMensagem:\n{$mensagem}";
    $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$email}" ];

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.' ] );
    } else {
        wp_send_json_error( [ 'message' => 'Erro ao enviar mensagem. Tente novamente ou entre em contato por telefone.' ] );
    }
}
add_action( 'wp_ajax_tiguen_contato',        'tiguen_handle_contato' );
add_action( 'wp_ajax_nopriv_tiguen_contato', 'tiguen_handle_contato' );

// Envio de Currículo
function tiguen_handle_curriculo() {
    check_ajax_referer( 'tiguen_curriculo_nonce', 'nonce' );

    $nome  = sanitize_text_field( $_POST['nome'] ?? '' );
    $email = sanitize_email( $_POST['email'] ?? '' );
    $cargo = sanitize_text_field( $_POST['cargo'] ?? '' );

    if ( ! $nome || ! $email ) {
        wp_send_json_error( [ 'message' => 'Nome e e-mail são obrigatórios.' ] );
    }

    $attachment = [];
    if ( ! empty( $_FILES['curriculo']['name'] ) ) {
        $file = $_FILES['curriculo'];

        // Validar tipo de arquivo
        $allowed_types = [ 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ];
        if ( ! in_array( $file['type'], $allowed_types, true ) ) {
            wp_send_json_error( [ 'message' => 'Formato de arquivo inválido. Envie PDF ou DOC/DOCX.' ] );
        }

        // Validar tamanho (5MB)
        if ( $file['size'] > 5 * 1024 * 1024 ) {
            wp_send_json_error( [ 'message' => 'Arquivo muito grande. Máximo 5MB.' ] );
        }

        $attachment = [ $file['tmp_name'] ];
    }

    $to      = 'projetos@tiguen.com';
    $subject = 'Currículo recebido — ' . $nome;
    $body    = "Nome: {$nome}\nE-mail: {$email}\nCargo desejado: {$cargo}";
    $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$email}" ];

    $sent = wp_mail( $to, $subject, $body, $headers, $attachment );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => 'Currículo enviado com sucesso! Entraremos em contato.' ] );
    } else {
        wp_send_json_error( [ 'message' => 'Erro ao enviar. Tente novamente.' ] );
    }
}
add_action( 'wp_ajax_tiguen_curriculo',        'tiguen_handle_curriculo' );
add_action( 'wp_ajax_nopriv_tiguen_curriculo', 'tiguen_handle_curriculo' );
