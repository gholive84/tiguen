<?php
/**
 * Meta Box — Galeria de Fotos (Projetos)
 * Sempre carregado, independente do ACF.
 * ACF Free não suporta o campo Gallery (é Pro).
 */

add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'projeto_galeria',
        'Galeria de Fotos',
        'tiguen_render_galeria_meta_box',
        'projetos',
        'normal',
        'default'
    );
});

function tiguen_render_galeria_meta_box( $post ) {
    wp_nonce_field( 'tiguen_galeria_nonce', 'tiguen_galeria_nonce_field' );
    $gallery_ids = get_post_meta( $post->ID, '_projeto_galeria', true );
    $gallery_ids = $gallery_ids ? $gallery_ids : '';
    ?>
    <div id="tiguen-galeria-container">
        <input type="hidden" name="projeto_galeria" id="projeto-galeria-ids" value="<?php echo esc_attr( $gallery_ids ); ?>">
        <div id="tiguen-galeria-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
            <?php
            if ( $gallery_ids ) {
                foreach ( explode( ',', $gallery_ids ) as $id ) {
                    $img = wp_get_attachment_image( intval( $id ), 'thumbnail' );
                    if ( $img ) {
                        echo '<div class="galeria-item" data-id="' . intval( $id ) . '" style="position:relative;">'
                            . $img
                            . '<span class="remove-img" style="position:absolute;top:2px;right:2px;background:#cc0000;color:#fff;cursor:pointer;padding:0 5px;font-size:14px;border-radius:2px;line-height:1.4;">×</span>'
                            . '</div>';
                    }
                }
            }
            ?>
        </div>
        <button type="button" class="button" id="tiguen-galeria-btn">Adicionar / Gerenciar Fotos</button>
        <p class="description" style="margin-top:8px;">Selecione múltiplas imagens pela biblioteca de mídia. A primeira imagem é usada como destaque na listagem.</p>
    </div>
    <script>
    jQuery(function($){
        var frame;
        $('#tiguen-galeria-btn').on('click', function(e){
            e.preventDefault();
            if ( frame ) { frame.open(); return; }
            frame = wp.media({
                title: 'Selecionar fotos da galeria',
                button: { text: 'Usar estas fotos' },
                multiple: true
            });
            frame.on('select', function(){
                var attachments = frame.state().get('selection').toJSON();
                var ids = attachments.map(function(a){ return a.id; }).join(',');
                $('#projeto-galeria-ids').val(ids);
                var html = '';
                attachments.forEach(function(a){
                    var thumb = a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail.url : a.url;
                    html += '<div class="galeria-item" data-id="'+a.id+'" style="position:relative;">'
                          + '<img src="'+thumb+'" style="width:80px;height:80px;object-fit:cover;">'
                          + '<span class="remove-img" style="position:absolute;top:2px;right:2px;background:#cc0000;color:#fff;cursor:pointer;padding:0 5px;font-size:14px;border-radius:2px;line-height:1.4;">×</span>'
                          + '</div>';
                });
                $('#tiguen-galeria-preview').html(html);
            });
            frame.open();
        });

        $(document).on('click', '.remove-img', function(){
            var item   = $(this).parent();
            var rmId   = item.data('id').toString();
            item.remove();
            var ids = $('#projeto-galeria-ids').val()
                        .split(',')
                        .filter(function(i){ return i !== rmId; })
                        .join(',');
            $('#projeto-galeria-ids').val(ids);
        });
    });
    </script>
    <?php
}

add_action( 'save_post_projetos', function( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;
    if ( ! isset($_POST['tiguen_galeria_nonce_field']) ) return;
    if ( ! wp_verify_nonce($_POST['tiguen_galeria_nonce_field'], 'tiguen_galeria_nonce') ) return;

    $galeria = isset($_POST['projeto_galeria']) ? sanitize_text_field($_POST['projeto_galeria']) : '';
    update_post_meta( $post_id, '_projeto_galeria', $galeria );
});
