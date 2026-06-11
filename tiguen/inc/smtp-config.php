<?php
/**
 * SMTP Config — Tiguen
 *
 * Lê as credenciais SMTP de constantes definidas em wp-config.php
 * (fora do repositório git, por segurança).
 *
 * Defina no wp-config.php:
 *
 *   define( 'TIGUEN_SMTP_HOST',      'mail.tiguen.com' );
 *   define( 'TIGUEN_SMTP_PORT',       465 );
 *   define( 'TIGUEN_SMTP_SECURE',    'ssl' );           // ssl ou tls
 *   define( 'TIGUEN_SMTP_USER',      'smtp@tiguen.com' );
 *   define( 'TIGUEN_SMTP_PASS',      'SENHA_AQUI' );
 *   define( 'TIGUEN_SMTP_FROM',      'smtp@tiguen.com' );
 *   define( 'TIGUEN_SMTP_FROM_NAME', 'Tiguen Construtora' );
 */

// Configura o PHPMailer interno do WordPress
add_action( 'phpmailer_init', function( $phpmailer ) {
    if ( ! defined( 'TIGUEN_SMTP_HOST' ) || ! defined( 'TIGUEN_SMTP_USER' ) || ! defined( 'TIGUEN_SMTP_PASS' ) ) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = TIGUEN_SMTP_HOST;
    $phpmailer->Port       = defined( 'TIGUEN_SMTP_PORT' )   ? TIGUEN_SMTP_PORT   : 465;
    $phpmailer->SMTPSecure = defined( 'TIGUEN_SMTP_SECURE' ) ? TIGUEN_SMTP_SECURE : 'ssl';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = TIGUEN_SMTP_USER;
    $phpmailer->Password   = TIGUEN_SMTP_PASS;

    if ( defined( 'TIGUEN_SMTP_FROM' ) ) {
        $phpmailer->From     = TIGUEN_SMTP_FROM;
        $phpmailer->FromName = defined( 'TIGUEN_SMTP_FROM_NAME' ) ? TIGUEN_SMTP_FROM_NAME : get_bloginfo( 'name' );
    }
});

// Garante que o "From" bata com o usuário SMTP (evita SPF/DMARC fails)
add_filter( 'wp_mail_from', function( $email ) {
    return defined( 'TIGUEN_SMTP_FROM' ) ? TIGUEN_SMTP_FROM : $email;
}, 99 );

add_filter( 'wp_mail_from_name', function( $name ) {
    return defined( 'TIGUEN_SMTP_FROM_NAME' ) ? TIGUEN_SMTP_FROM_NAME : $name;
}, 99 );

// ─── ADMIN: STATUS E TESTE DE ENVIO ─────────────────────────────────────────

add_action( 'admin_menu', function() {
    add_management_page(
        'SMTP — Tiguen',
        'SMTP (Email)',
        'manage_options',
        'tiguen-smtp',
        'tiguen_smtp_admin_page'
    );
});

function tiguen_smtp_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $configured = defined( 'TIGUEN_SMTP_HOST' ) && defined( 'TIGUEN_SMTP_USER' ) && defined( 'TIGUEN_SMTP_PASS' );
    $sent       = null;
    $error      = null;

    if ( isset( $_POST['tiguen_smtp_test_nonce'] ) && wp_verify_nonce( $_POST['tiguen_smtp_test_nonce'], 'tiguen_smtp_test' ) ) {
        $to = sanitize_email( $_POST['test_to'] ?? '' );
        if ( ! $to ) {
            $error = 'Informe um e-mail de destino válido.';
        } elseif ( ! $configured ) {
            $error = 'SMTP não configurado. Defina as constantes no wp-config.php primeiro.';
        } else {
            // Captura erros do PHPMailer
            add_action( 'wp_mail_failed', function( $wp_error ) use ( &$error ) {
                $error = $wp_error->get_error_message();
            });

            $ok = wp_mail(
                $to,
                'Teste SMTP — Tiguen (' . current_time( 'd/m/Y H:i' ) . ')',
                "Este é um teste de envio do servidor SMTP da Tiguen.\n\nSe você recebeu este e-mail, a configuração está funcionando."
            );

            if ( $ok ) {
                $sent = "E-mail de teste enviado para {$to}. Verifique a caixa de entrada (e o spam).";
            } elseif ( ! $error ) {
                $error = 'wp_mail() retornou falso, mas nenhuma mensagem de erro foi capturada. Verifique os logs do servidor.';
            }
        }
    }

    ?>
    <div class="wrap">
        <h1>SMTP — Tiguen</h1>

        <?php if ( $sent ) : ?>
            <div class="notice notice-success"><p><?php echo esc_html( $sent ); ?></p></div>
        <?php endif; ?>
        <?php if ( $error ) : ?>
            <div class="notice notice-error"><p><strong>Erro:</strong> <?php echo esc_html( $error ); ?></p></div>
        <?php endif; ?>

        <h2>Status da configuração</h2>
        <table class="wp-list-table widefat striped" style="max-width:700px;">
            <tbody>
                <tr>
                    <td><strong>Host (SMTP)</strong></td>
                    <td><?php echo defined('TIGUEN_SMTP_HOST') ? '<code>' . esc_html(TIGUEN_SMTP_HOST) . '</code>' : '<span style="color:#991B1B">❌ não definido</span>'; ?></td>
                </tr>
                <tr>
                    <td><strong>Porta</strong></td>
                    <td><?php echo defined('TIGUEN_SMTP_PORT') ? '<code>' . esc_html(TIGUEN_SMTP_PORT) . '</code>' : '<em>465 (padrão)</em>'; ?></td>
                </tr>
                <tr>
                    <td><strong>Criptografia</strong></td>
                    <td><?php echo defined('TIGUEN_SMTP_SECURE') ? '<code>' . esc_html(TIGUEN_SMTP_SECURE) . '</code>' : '<em>ssl (padrão)</em>'; ?></td>
                </tr>
                <tr>
                    <td><strong>Usuário</strong></td>
                    <td><?php echo defined('TIGUEN_SMTP_USER') ? '<code>' . esc_html(TIGUEN_SMTP_USER) . '</code>' : '<span style="color:#991B1B">❌ não definido</span>'; ?></td>
                </tr>
                <tr>
                    <td><strong>Senha</strong></td>
                    <td><?php echo defined('TIGUEN_SMTP_PASS') ? '✅ definida (oculta)' : '<span style="color:#991B1B">❌ não definida</span>'; ?></td>
                </tr>
                <tr>
                    <td><strong>From</strong></td>
                    <td><?php echo defined('TIGUEN_SMTP_FROM') ? '<code>' . esc_html(TIGUEN_SMTP_FROM) . '</code>' : '<em>(igual ao usuário SMTP)</em>'; ?></td>
                </tr>
                <tr>
                    <td><strong>From name</strong></td>
                    <td><?php echo defined('TIGUEN_SMTP_FROM_NAME') ? '<code>' . esc_html(TIGUEN_SMTP_FROM_NAME) . '</code>' : '<em>' . esc_html(get_bloginfo('name')) . '</em>'; ?></td>
                </tr>
            </tbody>
        </table>

        <?php if ( ! $configured ) : ?>
            <div class="notice notice-warning" style="margin-top:20px;">
                <p><strong>SMTP ainda não está configurado.</strong> Adicione as constantes abaixo no arquivo <code>wp-config.php</code> do seu servidor (antes da linha <code>/* That's all, stop editing! */</code>) e recarregue esta página.</p>
                <pre style="background:#1F2937;color:#F9FAFB;padding:16px;border-radius:6px;overflow:auto;font-size:.85rem;">
define( 'TIGUEN_SMTP_HOST',      'mail.tiguen.com' );
define( 'TIGUEN_SMTP_PORT',       465 );
define( 'TIGUEN_SMTP_SECURE',    'ssl' );
define( 'TIGUEN_SMTP_USER',      'smtp@tiguen.com' );
define( 'TIGUEN_SMTP_PASS',      'SUA_SENHA_AQUI' );
define( 'TIGUEN_SMTP_FROM',      'smtp@tiguen.com' );
define( 'TIGUEN_SMTP_FROM_NAME', 'Tiguen Construtora' );</pre>
            </div>
        <?php endif; ?>

        <h2 style="margin-top:32px;">Enviar e-mail de teste</h2>
        <form method="post" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <?php wp_nonce_field( 'tiguen_smtp_test', 'tiguen_smtp_test_nonce' ); ?>
            <input type="email" name="test_to" placeholder="seu-email@dominio.com" required style="min-width:280px;" class="regular-text">
            <button type="submit" class="button button-primary"<?php disabled( ! $configured ); ?>>
                ✉️ Enviar teste
            </button>
        </form>
    </div>
    <?php
}
