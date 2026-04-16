<?php
/**
 * Seeder de Conteúdo — Tiguen
 * Cria posts de exemplo para Projetos e Equipe usando imagens já importadas na Mídia.
 * Ferramentas > Criar Conteúdo de Exemplo
 */

function tiguen_seeder_menu() {
    add_management_page(
        'Criar Conteúdo de Exemplo',
        'Conteúdo de Exemplo',
        'manage_options',
        'tiguen-seeder',
        'tiguen_seeder_page'
    );
}
add_action( 'admin_menu', 'tiguen_seeder_menu' );

// Helper: busca attachment pelo nome do arquivo
function tiguen_get_attachment_by_filename( $filename ) {
    $posts = get_posts([
        'post_type'  => 'attachment',
        'meta_key'   => '_tiguen_source_file',
        'meta_value' => $filename,
        'numberposts'=> 1,
    ]);
    return $posts ? $posts[0]->ID : null;
}

function tiguen_seeder_page() {
    if ( ! current_user_can('manage_options') ) return;

    $results = [];

    if ( isset($_POST['tiguen_seeder_nonce']) && wp_verify_nonce($_POST['tiguen_seeder_nonce'], 'tiguen_seeder') ) {

        $tipo = sanitize_text_field( $_POST['seed_tipo'] ?? '' );

        if ( $tipo === 'projetos' ) {
            $results = tiguen_seed_projetos();
        } elseif ( $tipo === 'equipe' ) {
            $results = tiguen_seed_equipe();
        } elseif ( $tipo === 'all' ) {
            $results = array_merge( tiguen_seed_projetos(), tiguen_seed_equipe() );
        }
    }

    // Status das imagens
    $imagens_check = [
        'hero-obra.jpg'            => 'Hero / Capa',
        'projeto-residencial.jpg'  => 'Projeto Residencial',
        'projeto-casa.jpg'         => 'Projeto Casa',
        'projeto-comercial.jpg'    => 'Projeto Comercial',
        'projeto-institucional.jpg'=> 'Projeto Institucional',
        'projeto-obra-civil.jpg'   => 'Projeto Obra Civil',
        'sobre-equipe.jpg'         => 'Sobre — Equipe',
        'equipe-engenheiro-01.jpg' => 'Engenheiro 01',
        'equipe-arquiteta-02.jpg'  => 'Arquiteta 02',
        'equipe-engenheiro-03.jpg' => 'Engenheiro 03',
    ];

    ?>
    <div class="wrap">
        <h1>Criar Conteúdo de Exemplo</h1>
        <p style="color:#666;">Cria posts de Projetos e Equipe com conteúdo real usando as imagens importadas na Mídia.</p>

        <?php if ( $results ) : ?>
            <div class="notice" style="padding:12px 16px;background:#fff;border-left:4px solid #2563EB;margin:16px 0;">
                <?php foreach ( $results as $r ) :
                    $icon = $r['status'] === 'created' ? '✅' : ($r['status'] === 'skip' ? '⚠️' : '❌');
                    $color = $r['status'] === 'created' ? '#166534' : ($r['status'] === 'skip' ? '#92400E' : '#991B1B');
                ?>
                    <div style="margin-bottom:6px;color:<?php echo $color; ?>">
                        <?php echo $icon . ' ' . esc_html($r['msg']); ?>
                        <?php if ( !empty($r['url']) ) : ?>
                            — <a href="<?php echo esc_url($r['url']); ?>" target="_blank">Ver post</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- STATUS DAS IMAGENS -->
        <h2>Status das Imagens na Mídia</h2>
        <table class="wp-list-table widefat fixed striped" style="max-width:700px;margin-bottom:32px;">
            <thead><tr><th>Imagem</th><th>Uso</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ( $imagens_check as $file => $uso ) :
                    $id = tiguen_get_attachment_by_filename($file);
                ?>
                    <tr>
                        <td><code><?php echo esc_html($file); ?></code></td>
                        <td><?php echo esc_html($uso); ?></td>
                        <td>
                            <?php if ( $id ) : ?>
                                <span style="color:#166534;font-weight:600;">✅ Na Mídia (ID <?php echo $id; ?>)</span>
                            <?php else : ?>
                                <span style="color:#991B1B;">❌ Não importada ainda</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- AÇÕES -->
        <h2>Criar Posts</h2>
        <p style="color:#666;">Posts com o mesmo título não serão duplicados.</p>
        <form method="post" style="display:flex;gap:12px;flex-wrap:wrap;">
            <?php wp_nonce_field('tiguen_seeder', 'tiguen_seeder_nonce'); ?>
            <button type="submit" name="seed_tipo" value="projetos" class="button button-primary button-large">
                🏗️ Criar Projetos de Exemplo
            </button>
            <button type="submit" name="seed_tipo" value="equipe" class="button button-primary button-large">
                👥 Criar Equipe de Exemplo
            </button>
            <button type="submit" name="seed_tipo" value="all" class="button button-large" style="background:#0E1B2C;color:#fff;border-color:#0E1B2C;">
                ⚡ Criar Tudo
            </button>
        </form>
    </div>
    <?php
}

// ─── SEED: PROJETOS ──────────────────────────────────────────────────────────

function tiguen_seed_projetos() {
    $results = [];

    // Garante que os tipos de obra existem
    $tipos = [
        'residencial'   => 'Residencial',
        'comercial'     => 'Comercial',
        'institucional' => 'Institucional',
    ];
    foreach ( $tipos as $slug => $name ) {
        if ( ! term_exists($slug, 'tipo_obra') ) {
            wp_insert_term( $name, 'tipo_obra', [ 'slug' => $slug ] );
        }
    }

    $projetos = [
        [
            'titulo'     => 'CMEI Professora Maria da Piedade',
            'excerpt'    => 'Reforma completa do Centro Municipal de Educação Infantil, incluindo adequação de acessibilidade e modernização das instalações.',
            'content'    => '<p>Projeto de reforma completa do CMEI Professora Maria da Piedade Souza Cortes, em São José dos Pinhais. A intervenção contemplou adequação de toda a infraestrutura de acessibilidade, modernização das instalações elétricas e hidráulicas, pintura geral e reforma das áreas externas.</p><p>A obra foi executada em etapas para minimizar o impacto nas atividades da unidade, garantindo a continuidade do atendimento às crianças durante todo o período de reforma.</p>',
            'area'       => '1.200',
            'ano'        => '2023',
            'local'      => 'São José dos Pinhais, PR',
            'tipo'       => 'institucional',
            'imagem'     => 'projeto-institucional.jpg',
        ],
        [
            'titulo'     => 'Residência Casa 160m²',
            'excerpt'    => 'Construção de residência unifamiliar de alto padrão com acabamento completo, em condomínio fechado.',
            'content'    => '<p>Construção de residência unifamiliar de 160m² em condomínio fechado em São José dos Pinhais. O projeto combina linhas modernas com funcionalidade, integrando sala de estar e jantar, cozinha gourmet, 3 suítes e área de lazer com piscina.</p><p>A obra foi entregue dentro do prazo e orçamento acordados, com acabamento de alto padrão e supervisão técnica em todas as etapas.</p>',
            'area'       => '160',
            'ano'        => '2023',
            'local'      => 'São José dos Pinhais, PR',
            'tipo'       => 'residencial',
            'imagem'     => 'projeto-casa.jpg',
        ],
        [
            'titulo'     => 'Lojas Barão — Cerro Azul',
            'excerpt'    => 'Construção de complexo comercial com 4 lojas, estacionamento e área de serviço.',
            'content'    => '<p>Construção de complexo comercial composto por 4 unidades de loja, área de estacionamento para 20 veículos e espaço de serviço. O projeto priorizou fachada atrativa, fluxo eficiente de clientes e estrutura robusta para suportar cargas comerciais.</p><p>A obra foi concluída em 8 meses, com entrega antecipada em relação ao cronograma inicial.</p>',
            'area'       => '480',
            'ano'        => '2022',
            'local'      => 'Cerro Azul, PR',
            'tipo'       => 'comercial',
            'imagem'     => 'projeto-comercial.jpg',
        ],
        [
            'titulo'     => 'Edifício Residencial Panorama',
            'excerpt'    => 'Construção de edifício residencial de 4 pavimentos com 16 apartamentos.',
            'content'    => '<p>Construção de edifício residencial de 4 pavimentos com 16 unidades de 2 quartos cada, área de lazer no térreo com piscina e salão de festas, e 2 vagas de garagem por apartamento.</p><p>Projeto desenvolvido com foco em eficiência energética, ventilação natural e aproveitamento da iluminação solar.</p>',
            'area'       => '2.400',
            'ano'        => '2022',
            'local'      => 'São José dos Pinhais, PR',
            'tipo'       => 'residencial',
            'imagem'     => 'projeto-residencial.jpg',
        ],
        [
            'titulo'     => 'Galpão Industrial TechPar',
            'excerpt'    => 'Construção de galpão industrial com área de escritórios e docas de carga.',
            'content'    => '<p>Construção de galpão industrial metálico de 3.500m², com área de escritórios de 200m², 4 docas de carga, piso industrial reforçado e sistema de iluminação zenital.</p><p>Obra executada utilizando estrutura metálica pré-fabricada, reduzindo o prazo de execução em 40% em relação à construção convencional.</p>',
            'area'       => '3.700',
            'ano'        => '2021',
            'local'      => 'São José dos Pinhais, PR',
            'tipo'       => 'comercial',
            'imagem'     => 'projeto-obra-civil.jpg',
        ],
    ];

    foreach ( $projetos as $p ) {

        // Evita duplicata
        $existing = get_posts(['post_type' => 'projetos', 'title' => $p['titulo'], 'numberposts' => 1]);
        if ( $existing ) {
            $results[] = [ 'status' => 'skip', 'msg' => "Projeto '{$p['titulo']}': já existe." ];
            continue;
        }

        $post_id = wp_insert_post([
            'post_title'   => $p['titulo'],
            'post_excerpt' => $p['excerpt'],
            'post_content' => $p['content'],
            'post_type'    => 'projetos',
            'post_status'  => 'publish',
        ]);

        if ( is_wp_error($post_id) ) {
            $results[] = [ 'status' => 'error', 'msg' => "Erro ao criar '{$p['titulo']}': " . $post_id->get_error_message() ];
            continue;
        }

        // Tipo de obra
        wp_set_object_terms( $post_id, $p['tipo'], 'tipo_obra' );

        // ACF fields
        if ( function_exists('update_field') ) {
            update_field( 'area',        $p['area'],  $post_id );
            update_field( 'ano',         $p['ano'],   $post_id );
            update_field( 'localizacao', $p['local'], $post_id );
        } else {
            update_post_meta( $post_id, '_projeto_area',        $p['area'] );
            update_post_meta( $post_id, '_projeto_ano',         $p['ano'] );
            update_post_meta( $post_id, '_projeto_localizacao', $p['local'] );
        }

        // Imagem destacada
        $img_id = tiguen_get_attachment_by_filename( $p['imagem'] );
        if ( $img_id ) set_post_thumbnail( $post_id, $img_id );

        $results[] = [
            'status' => 'created',
            'msg'    => "Projeto '{$p['titulo']}' criado" . ($img_id ? ' com imagem' : ' (importe a imagem primeiro)'),
            'url'    => get_edit_post_link($post_id),
        ];
    }

    return $results;
}

// ─── SEED: EQUIPE ────────────────────────────────────────────────────────────

function tiguen_seed_equipe() {
    $results = [];

    $membros = [
        [
            'nome'     => 'Carlos Eduardo Tiguen',
            'cargo'    => 'Engenheiro Civil — Diretor Técnico',
            'bio'      => '<p>Engenheiro Civil com mais de 20 anos de experiência em obras residenciais, comerciais e institucionais. Responsável pela direção técnica de todos os projetos da Tiguen Engenharia.</p>',
            'linkedin' => 'https://linkedin.com/company/tiguen-engenharia-e-construcoes',
            'imagem'   => 'equipe-engenheiro-01.jpg',
        ],
        [
            'nome'     => 'Ana Paula Ferreira',
            'cargo'    => 'Arquiteta — Projetos e Design',
            'bio'      => '<p>Arquiteta e Urbanista especializada em projetos residenciais e comerciais. Responsável pelo desenvolvimento de projetos arquitetônicos e acompanhamento de obras.</p>',
            'linkedin' => '',
            'imagem'   => 'equipe-arquiteta-02.jpg',
        ],
        [
            'nome'     => 'Rafael Martins',
            'cargo'    => 'Engenheiro Civil — Obras e Orçamentos',
            'bio'      => '<p>Engenheiro Civil especializado em gestão de obras e elaboração de orçamentos. Responsável pelo planejamento e controle de projetos de construção e reforma.</p>',
            'linkedin' => '',
            'imagem'   => 'equipe-engenheiro-03.jpg',
        ],
    ];

    foreach ( $membros as $m ) {

        $existing = get_posts(['post_type' => 'equipe', 'title' => $m['nome'], 'numberposts' => 1]);
        if ( $existing ) {
            $results[] = [ 'status' => 'skip', 'msg' => "Membro '{$m['nome']}': já existe." ];
            continue;
        }

        $post_id = wp_insert_post([
            'post_title'   => $m['nome'],
            'post_content' => $m['bio'],
            'post_type'    => 'equipe',
            'post_status'  => 'publish',
        ]);

        if ( is_wp_error($post_id) ) {
            $results[] = [ 'status' => 'error', 'msg' => "Erro ao criar '{$m['nome']}': " . $post_id->get_error_message() ];
            continue;
        }

        // ACF fields
        if ( function_exists('update_field') ) {
            update_field( 'cargo',    $m['cargo'],    $post_id );
            update_field( 'linkedin', $m['linkedin'], $post_id );
        } else {
            update_post_meta( $post_id, '_equipe_cargo',    $m['cargo'] );
            update_post_meta( $post_id, '_equipe_linkedin', $m['linkedin'] );
        }

        // Foto (imagem destacada)
        $img_id = tiguen_get_attachment_by_filename( $m['imagem'] );
        if ( $img_id ) set_post_thumbnail( $post_id, $img_id );

        $results[] = [
            'status' => 'created',
            'msg'    => "Membro '{$m['nome']}' criado" . ($img_id ? ' com foto' : ' (importe a imagem primeiro)'),
            'url'    => get_edit_post_link($post_id),
        ];
    }

    return $results;
}
