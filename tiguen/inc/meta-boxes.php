<?php
/**
 * Meta Boxes — Custom Fields para CPTs
 */

// ─── PROJETOS ────────────────────────────────────────────────────────────────

function tiguen_projetos_meta_boxes() {
    add_meta_box(
        'projeto_galeria',
        'Galeria de Fotos',
        'tiguen_render_galeria_meta_box',
        'projetos',
        'normal',
        'default'
    );

    add_meta_box(
        'projeto_detalhes',
        'Detalhes do Projeto',
        'tiguen_render_detalhes_meta_box',
        'projetos',
        'side',
        'default'
    );

    add_meta_box(
        'projeto_video',
        'Vídeo do Projeto',
        'tiguen_render_video_meta_box',
        'projetos',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'tiguen_projetos_meta_boxes' );

// Galeria de Fotos
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
                $ids = explode( ',', $gallery_ids );
                foreach ( $ids as $id ) {
                    $img = wp_get_attachment_image( intval( $id ), 'thumbnail' );
                    if ( $img ) echo '<div class="galeria-item" data-id="' . intval($id) . '" style="position:relative;">' . $img . '<span class="remove-img" style="position:absolute;top:2px;right:2px;background:#cc0000;color:#fff;cursor:pointer;padding:0 5px;font-size:14px;border-radius:2px;">×</span></div>';
                }
            }
            ?>
        </div>
        <button type="button" class="button" id="tiguen-galeria-btn">Adicionar / Gerenciar Fotos</button>
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
                    html += '<div class="galeria-item" data-id="'+a.id+'" style="position:relative;"><img src="'+a.sizes.thumbnail.url+'" style="width:80px;height:80px;object-fit:cover;"><span class="remove-img" style="position:absolute;top:2px;right:2px;background:#cc0000;color:#fff;cursor:pointer;padding:0 5px;font-size:14px;border-radius:2px;">×</span></div>';
                });
                $('#tiguen-galeria-preview').html(html);
            });
            frame.open();
        });
        $(document).on('click', '.remove-img', function(){
            var item = $(this).parent();
            var removedId = item.data('id').toString();
            item.remove();
            var ids = $('#projeto-galeria-ids').val().split(',').filter(function(i){ return i !== removedId; }).join(',');
            $('#projeto-galeria-ids').val(ids);
        });
    });
    </script>
    <?php
}

// Detalhes do Projeto
function tiguen_render_detalhes_meta_box( $post ) {
    wp_nonce_field( 'tiguen_detalhes_nonce', 'tiguen_detalhes_nonce_field' );
    $area       = get_post_meta( $post->ID, '_projeto_area', true );
    $ano        = get_post_meta( $post->ID, '_projeto_ano', true );
    $localizacao = get_post_meta( $post->ID, '_projeto_localizacao', true );
    ?>
    <p>
        <label for="projeto_area"><strong>Área (m²)</strong></label><br>
        <input type="text" name="projeto_area" id="projeto_area" value="<?php echo esc_attr($area); ?>" style="width:100%">
    </p>
    <p>
        <label for="projeto_ano"><strong>Ano de Conclusão</strong></label><br>
        <input type="number" name="projeto_ano" id="projeto_ano" value="<?php echo esc_attr($ano); ?>" style="width:100%" min="1990" max="2099">
    </p>
    <p>
        <label for="projeto_localizacao"><strong>Localização</strong></label><br>
        <input type="text" name="projeto_localizacao" id="projeto_localizacao" value="<?php echo esc_attr($localizacao); ?>" style="width:100%" placeholder="Cidade, Estado">
    </p>
    <?php
}

// Vídeo do Projeto
function tiguen_render_video_meta_box( $post ) {
    wp_nonce_field( 'tiguen_video_nonce', 'tiguen_video_nonce_field' );
    $video_url = get_post_meta( $post->ID, '_projeto_video_url', true );
    ?>
    <p>
        <label for="projeto_video_url"><strong>URL do Vídeo (YouTube ou Vimeo)</strong></label><br>
        <input type="url" name="projeto_video_url" id="projeto_video_url" value="<?php echo esc_attr($video_url); ?>" style="width:100%" placeholder="https://www.youtube.com/watch?v=...">
    </p>
    <?php
}

// Salvar meta boxes de Projetos
function tiguen_save_projetos_meta( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    // Galeria
    if ( isset($_POST['tiguen_galeria_nonce_field']) && wp_verify_nonce($_POST['tiguen_galeria_nonce_field'], 'tiguen_galeria_nonce') ) {
        $galeria = isset($_POST['projeto_galeria']) ? sanitize_text_field($_POST['projeto_galeria']) : '';
        update_post_meta( $post_id, '_projeto_galeria', $galeria );
    }

    // Detalhes
    if ( isset($_POST['tiguen_detalhes_nonce_field']) && wp_verify_nonce($_POST['tiguen_detalhes_nonce_field'], 'tiguen_detalhes_nonce') ) {
        update_post_meta( $post_id, '_projeto_area',        sanitize_text_field($_POST['projeto_area'] ?? '') );
        update_post_meta( $post_id, '_projeto_ano',         absint($_POST['projeto_ano'] ?? 0) );
        update_post_meta( $post_id, '_projeto_localizacao', sanitize_text_field($_POST['projeto_localizacao'] ?? '') );
    }

    // Vídeo
    if ( isset($_POST['tiguen_video_nonce_field']) && wp_verify_nonce($_POST['tiguen_video_nonce_field'], 'tiguen_video_nonce') ) {
        update_post_meta( $post_id, '_projeto_video_url', esc_url_raw($_POST['projeto_video_url'] ?? '') );
    }
}
add_action( 'save_post_projetos', 'tiguen_save_projetos_meta' );

// ─── EQUIPE ───────────────────────────────────────────────────────────────────

function tiguen_equipe_meta_boxes() {
    add_meta_box(
        'equipe_info',
        'Informações do Profissional',
        'tiguen_render_equipe_meta_box',
        'equipe',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'tiguen_equipe_meta_boxes' );

function tiguen_render_equipe_meta_box( $post ) {
    wp_nonce_field( 'tiguen_equipe_nonce', 'tiguen_equipe_nonce_field' );
    $cargo    = get_post_meta( $post->ID, '_equipe_cargo', true );
    $linkedin = get_post_meta( $post->ID, '_equipe_linkedin', true );
    ?>
    <p>
        <label for="equipe_cargo"><strong>Cargo / Especialidade</strong></label><br>
        <input type="text" name="equipe_cargo" id="equipe_cargo" value="<?php echo esc_attr($cargo); ?>" style="width:100%" placeholder="Ex: Engenheiro Civil">
    </p>
    <p>
        <label for="equipe_linkedin"><strong>LinkedIn</strong></label><br>
        <input type="url" name="equipe_linkedin" id="equipe_linkedin" value="<?php echo esc_attr($linkedin); ?>" style="width:100%">
    </p>
    <?php
}

function tiguen_save_equipe_meta( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;
    if ( ! isset($_POST['tiguen_equipe_nonce_field']) || ! wp_verify_nonce($_POST['tiguen_equipe_nonce_field'], 'tiguen_equipe_nonce') ) return;

    update_post_meta( $post_id, '_equipe_cargo',    sanitize_text_field($_POST['equipe_cargo'] ?? '') );
    update_post_meta( $post_id, '_equipe_linkedin', esc_url_raw($_POST['equipe_linkedin'] ?? '') );
}
add_action( 'save_post_equipe', 'tiguen_save_equipe_meta' );
