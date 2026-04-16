<?php
/**
 * Importador de Imagens — Tiguen
 * Importa imagens de assets/images/ para a Biblioteca de Mídia do WordPress
 */

// Registra página no menu Ferramentas
function tiguen_media_import_menu() {
    add_management_page(
        'Importar Imagens do Tema',
        'Importar Imagens',
        'manage_options',
        'tiguen-media-import',
        'tiguen_media_import_page'
    );
}
add_action( 'admin_menu', 'tiguen_media_import_menu' );

// Página de importação
function tiguen_media_import_page() {
    if ( ! current_user_can('manage_options') ) return;

    $images_dir  = get_template_directory() . '/assets/images/';
    $allowed_ext = [ 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' ];
    $results     = [];

    // Processar importação
    if ( isset($_POST['tiguen_import_nonce']) && wp_verify_nonce($_POST['tiguen_import_nonce'], 'tiguen_import') ) {

        $selected = $_POST['import_files'] ?? [];
        $force    = ! empty( $_POST['force_reimport'] );

        if ( empty($selected) ) {
            $results[] = [ 'status' => 'error', 'msg' => 'Nenhuma imagem selecionada.' ];
        } else {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            foreach ( $selected as $filename ) {
                $filename = basename( sanitize_file_name( $filename ) );
                $filepath = $images_dir . $filename;

                if ( ! file_exists($filepath) ) {
                    $results[] = [ 'status' => 'error', 'msg' => "{$filename}: arquivo não encontrado." ];
                    continue;
                }

                // Verifica se já foi importado
                $existing = get_posts([
                    'post_type'  => 'attachment',
                    'meta_key'   => '_tiguen_source_file',
                    'meta_value' => $filename,
                    'numberposts'=> 1,
                ]);

                if ( $existing && ! $force ) {
                    $results[] = [ 'status' => 'skip', 'msg' => "{$filename}: já importado (ID {$existing[0]->ID}). Use 'Forçar' para substituir." ];
                    continue;
                }

                // Forçar: apaga o attachment anterior e reimporta
                if ( $existing && $force ) {
                    wp_delete_attachment( $existing[0]->ID, true );
                    $results[] = [ 'status' => 'info', 'msg' => "{$filename}: attachment anterior removido, reimportando..." ];
                }

                // Copia para upload temporário
                $tmp = wp_tempnam($filename);
                copy($filepath, $tmp);

                $file_array = [
                    'name'     => $filename,
                    'tmp_name' => $tmp,
                ];

                $attachment_id = media_handle_sideload($file_array, 0, pathinfo($filename, PATHINFO_FILENAME));

                if ( is_wp_error($attachment_id) ) {
                    @unlink($tmp);
                    $results[] = [ 'status' => 'error', 'msg' => "{$filename}: " . $attachment_id->get_error_message() ];
                } else {
                    update_post_meta($attachment_id, '_tiguen_source_file', $filename);
                    $results[] = [
                        'status' => 'success',
                        'msg'    => "{$filename}: importado com sucesso (ID {$attachment_id}).",
                        'id'     => $attachment_id,
                        'url'    => wp_get_attachment_url($attachment_id),
                    ];
                }
            }
        }
    }

    // Listar arquivos disponíveis
    $files = [];
    if ( is_dir($images_dir) ) {
        foreach ( scandir($images_dir) as $file ) {
            if ( $file === '.' || $file === '..' || $file === '.gitkeep' ) continue;
            $ext = strtolower( pathinfo($file, PATHINFO_EXTENSION) );
            if ( in_array($ext, $allowed_ext, true) ) {
                $files[] = $file;
            }
        }
    }

    // Verificar quais já foram importados
    $already_imported = [];
    foreach ( $files as $file ) {
        $existing = get_posts([
            'post_type'  => 'attachment',
            'meta_key'   => '_tiguen_source_file',
            'meta_value' => $file,
            'numberposts'=> 1,
        ]);
        if ( $existing ) $already_imported[] = $file;
    }

    ?>
    <div class="wrap">
        <h1>Importar Imagens do Tema para a Mídia</h1>
        <p style="color:#666;">
            Imagens em <code><?php echo esc_html($images_dir); ?></code> serão importadas para a Biblioteca de Mídia do WordPress.
        </p>

        <?php if ( $results ) : ?>
            <div class="notice" style="padding:12px 16px;background:#fff;border-left:4px solid #2563EB;margin-bottom:20px;">
                <strong>Resultado da importação:</strong><br><br>
                <?php foreach ( $results as $r ) :
                    $color = $r['status'] === 'success' ? '#166534' : ( $r['status'] === 'skip' ? '#92400E' : '#991B1B' );
                    $icon  = $r['status'] === 'success' ? '✅' : ( $r['status'] === 'skip' ? '⚠️' : '❌' );
                ?>
                    <div style="margin-bottom:6px;color:<?php echo $color; ?>">
                        <?php echo $icon . ' ' . esc_html($r['msg']); ?>
                        <?php if ( isset($r['url']) ) : ?>
                            — <a href="<?php echo esc_url($r['url']); ?>" target="_blank" style="color:#2563EB">Ver imagem</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( empty($files) ) : ?>
            <div class="notice notice-warning inline"><p>
                Nenhuma imagem encontrada em <code>assets/images/</code>.<br>
                Adicione imagens na pasta <code>tiguen/assets/images/</code> do tema e faça deploy.
            </p></div>
        <?php else : ?>
            <form method="post">
                <?php wp_nonce_field('tiguen_import', 'tiguen_import_nonce'); ?>

                <table class="wp-list-table widefat fixed striped" style="max-width:900px;">
                    <thead>
                        <tr>
                            <td style="width:30px;"><input type="checkbox" id="check-all"></td>
                            <th>Preview</th>
                            <th>Arquivo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $files as $file ) :
                            $imported = in_array($file, $already_imported, true);
                            $file_url = get_template_directory_uri() . '/assets/images/' . rawurlencode($file);
                            $ext      = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $is_image = in_array($ext, ['jpg','jpeg','png','gif','webp'], true);
                        ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="import_files[]"
                                           value="<?php echo esc_attr($file); ?>"
                                           class="img-check"
                                           <?php checked(!$imported); ?>>
                                </td>
                                <td style="width:80px;">
                                    <?php if ( $is_image ) : ?>
                                        <img src="<?php echo esc_url($file_url); ?>"
                                             style="height:50px;width:auto;border-radius:4px;object-fit:cover;">
                                    <?php else : ?>
                                        <span style="font-size:1.5rem;">📄</span>
                                    <?php endif; ?>
                                </td>
                                <td><code><?php echo esc_html($file); ?></code></td>
                                <td>
                                    <?php if ( $imported ) : ?>
                                        <span style="color:#166534;font-weight:600;">✅ Já importado</span>
                                        <span style="color:#555;font-size:.8rem;display:block;">Selecione + marque "Forçar" para substituir</span>
                                    <?php else : ?>
                                        <span style="color:#92400E;">Pendente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p style="margin-top:16px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                    <button type="submit" class="button button-primary button-large">
                        ⬆️ Importar selecionadas para a Mídia
                    </button>
                    <label style="display:flex;align-items:center;gap:6px;font-size:.9rem;color:#555;cursor:pointer;">
                        <input type="checkbox" name="force_reimport" value="1">
                        <strong>Forçar re-importação</strong> (substitui imagens já importadas)
                    </label>
                </p>
            </form>

            <script>
            document.getElementById('check-all').addEventListener('change', function() {
                document.querySelectorAll('.img-check:not([disabled])').forEach(function(cb) {
                    cb.checked = document.getElementById('check-all').checked;
                });
            });
            </script>
        <?php endif; ?>

        <hr style="margin-top:32px;">
        <p style="color:#666;font-size:.9rem;">
            💡 <strong>Fluxo recomendado:</strong> Coloque as imagens em
            <code>tiguen/assets/images/</code> → commit + push → pull no servidor →
            clique em "Importar" aqui → use as imagens pelo ID ou URL na Mídia do WordPress.
        </p>
    </div>
    <?php
}
