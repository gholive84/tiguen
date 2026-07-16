<?php
/**
 * Gerenciador de Logos e Selos — Tiguen
 *
 * Duas páginas admin (Aparência → Logos de Clientes / Selos e Certificados)
 * onde o usuário seleciona imagens pela Biblioteca de Mídia do WordPress.
 * IDs dos anexos são salvos em options:
 *   - tiguen_clientes_logos (array de IDs)
 *   - tiguen_selos_logos    (array de IDs)
 */

// Menu (Aparência)
add_action( 'admin_menu', function() {
    add_theme_page(
        'Logos de Clientes',
        'Logos de Clientes',
        'edit_theme_options',
        'tiguen-clientes-logos',
        function() { tiguen_render_logos_page( 'clientes' ); }
    );
    add_theme_page(
        'Selos e Certificados',
        'Selos e Certificados',
        'edit_theme_options',
        'tiguen-selos-logos',
        function() { tiguen_render_logos_page( 'selos' ); }
    );
});

// Enfileira Media Library nas nossas páginas
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook === 'appearance_page_tiguen-clientes-logos' || $hook === 'appearance_page_tiguen-selos-logos' ) {
        wp_enqueue_media();
    }
});

/**
 * Retorna a lista de IDs de anexos salvos para o tipo (clientes|selos).
 */
function tiguen_get_logo_ids( $tipo ) {
    $opt = get_option( "tiguen_{$tipo}_logos", [] );
    if ( ! is_array( $opt ) ) $opt = [];
    return array_values( array_filter( array_map( 'absint', $opt ) ) );
}

/**
 * Renderiza uma página de gerenciamento (clientes ou selos).
 */
function tiguen_render_logos_page( $tipo ) {
    if ( ! current_user_can( 'edit_theme_options' ) ) return;

    $titulo   = $tipo === 'clientes' ? 'Logos de Clientes' : 'Selos e Certificados';
    $option   = "tiguen_{$tipo}_logos";
    $nonce    = "tiguen_{$tipo}_logos_nonce";
    $notice   = '';

    // Salvar
    if ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $option ) ) {
        $raw = sanitize_text_field( $_POST['logo_ids'] ?? '' );
        $ids = array_values( array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );
        update_option( $option, $ids );
        $notice = count( $ids ) . ' item(ns) salvo(s).';
    }

    $ids = tiguen_get_logo_ids( $tipo );

    ?>
    <div class="wrap">
        <h1><?php echo esc_html( $titulo ); ?></h1>
        <p style="color:#666;max-width:800px;">
            Selecione as imagens pela Biblioteca de Mídia do WordPress. Recomendado: <strong>PNG ou SVG</strong> com fundo transparente. As imagens aparecem em cinza no site e coloridas ao passar o mouse.
            <?php if ( $tipo === 'clientes' ) : ?>
                Aparecem no <strong>slider da home</strong>, logo abaixo do hero.
            <?php else : ?>
                Aparecem no bloco <strong>antes do rodapé</strong>, em todas as páginas.
            <?php endif; ?>
        </p>

        <?php if ( $notice ) : ?>
            <div class="notice notice-success"><p><?php echo esc_html( $notice ); ?></p></div>
        <?php endif; ?>

        <form method="post" id="tiguen-logos-form">
            <?php wp_nonce_field( $option, $nonce ); ?>
            <input type="hidden" name="logo_ids" id="tiguen-logo-ids" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">

            <div style="display:flex;gap:12px;margin:20px 0;flex-wrap:wrap;">
                <button type="button" class="button button-primary button-large" id="tiguen-add-logos">
                    ➕ Adicionar da Biblioteca de Mídia
                </button>
                <button type="submit" class="button button-large">💾 Salvar alterações</button>
                <?php if ( $ids ) : ?>
                    <button type="button" class="button button-large" id="tiguen-clear-all" style="color:#B91C1C;">🗑️ Remover todas</button>
                <?php endif; ?>
            </div>

            <div id="tiguen-logos-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-top:20px;">
                <?php if ( ! $ids ) : ?>
                    <div style="grid-column:1/-1;background:#F9FAFB;padding:40px;text-align:center;border:2px dashed #D1D5DB;border-radius:8px;color:#6B7280;">
                        <p style="margin:0;font-size:1rem;">Nenhum item ainda. Clique em <strong>"Adicionar da Biblioteca de Mídia"</strong> para começar.</p>
                        <?php if ( $tipo === 'clientes' || $tipo === 'selos' ) : ?>
                            <p style="margin:12px 0 0;font-size:.85rem;color:#9CA3AF;">Enquanto vazio, o site usa placeholders de demonstração da pasta do tema.</p>
                        <?php endif; ?>
                    </div>
                <?php else : foreach ( $ids as $id ) :
                    $url  = wp_get_attachment_image_url( $id, 'medium' );
                    $name = get_the_title( $id );
                    if ( ! $url ) continue;
                ?>
                    <div class="tiguen-logo-item" data-id="<?php echo $id; ?>" style="background:#fff;border:1px solid #E5E7EB;border-radius:8px;padding:16px;position:relative;">
                        <button type="button" class="tiguen-remove-logo" data-id="<?php echo $id; ?>" title="Remover" style="position:absolute;top:6px;right:6px;background:#B91C1C;color:#fff;border:0;border-radius:50%;width:24px;height:24px;cursor:pointer;line-height:1;">×</button>
                        <img src="<?php echo esc_url( $url ); ?>" alt="" style="width:100%;height:80px;object-fit:contain;">
                        <p style="margin:8px 0 0;font-size:.78rem;color:#6B7280;text-align:center;word-break:break-word;"><?php echo esc_html( $name ); ?></p>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </form>
    </div>

    <script>
    jQuery(function($){
        var frame;

        $('#tiguen-add-logos').on('click', function(e){
            e.preventDefault();
            if (frame) { frame.open(); return; }

            frame = wp.media({
                title: 'Selecionar <?php echo $tipo === "clientes" ? "logos" : "selos"; ?>',
                button: { text: 'Adicionar seleção' },
                library: { type: 'image' },
                multiple: true
            });

            frame.on('select', function(){
                var current = $('#tiguen-logo-ids').val().split(',').filter(Boolean).map(Number);
                var selection = frame.state().get('selection').toJSON();
                selection.forEach(function(att){
                    if (current.indexOf(att.id) === -1) current.push(att.id);
                });
                $('#tiguen-logo-ids').val(current.join(','));
                if (current.length) $('#tiguen-logos-form').submit();
            });

            frame.open();
        });

        $('#tiguen-logos-grid').on('click', '.tiguen-remove-logo', function(){
            var id = $(this).data('id');
            if (!confirm('Remover este item da lista?')) return;
            var current = $('#tiguen-logo-ids').val().split(',').filter(Boolean).map(Number).filter(function(x){ return x !== id; });
            $('#tiguen-logo-ids').val(current.join(','));
            $('#tiguen-logos-form').submit();
        });

        $('#tiguen-clear-all').on('click', function(){
            if (!confirm('Remover TODAS as imagens da lista? (não apaga da Mídia)')) return;
            $('#tiguen-logo-ids').val('');
            $('#tiguen-logos-form').submit();
        });
    });
    </script>
    <?php
}
