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
        } elseif ( $tipo === 'blog' ) {
            $results = tiguen_seed_blog();
        } elseif ( $tipo === 'all' ) {
            $results = array_merge( tiguen_seed_projetos(), tiguen_seed_equipe(), tiguen_seed_blog() );
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
        'sobre-equipe.jpg'          => 'Sobre — Equipe',
        'max.jpeg'                  => 'Equipe — Max Gustavo (Diretor Comercial)',
        'allane.jpeg'               => 'Equipe — Allane Kellen (Diretora Administrativa)',
        'michel.jpeg'               => 'Equipe — Michel Cortes (Engenheiro Civil)',
        'vanessa.jpeg'              => 'Equipe — Vanessa Cristina (Engenheira Civil)',
        'Waitter.jpeg'              => 'Equipe — Waitter Assis (Compras)',
        'Danielle.jpeg'             => 'Equipe — Danielle de Sena (Financeiro)',
        'Pamela.jpeg'               => 'Equipe — Pamela da Silva (Aux. Administrativo)',
        'blog-reforma-interior.jpg' => 'Blog — Reforma Interior',
        'blog-construcao-civil.jpg' => 'Blog — Construção Civil',
        'blog-engenharia-projeto.jpg'=> 'Blog — Engenharia Projeto',
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
            <button type="submit" name="seed_tipo" value="blog" class="button button-primary button-large">
                📝 Criar Posts de Blog
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
            'nome'     => 'Max Gustavo Cristóvão',
            'cargo'    => 'Diretor Comercial',
            'bio'      => '<p>Diretor Comercial da Tiguen Construtora, responsável pela área de negócios, relacionamento com clientes e desenvolvimento comercial da empresa.</p>',
            'linkedin' => '',
            'imagem'   => 'max.jpeg',
        ],
        [
            'nome'     => 'Allane Kellen Sinja',
            'cargo'    => 'Diretora Administrativa',
            'bio'      => '<p>Diretora Administrativa da Tiguen Construtora, responsável pela gestão administrativa, processos internos e estratégia organizacional da empresa.</p>',
            'linkedin' => '',
            'imagem'   => 'allane.jpeg',
        ],
        [
            'nome'     => 'Michel Cortes Ferracine',
            'cargo'    => 'Engenheiro Civil',
            'bio'      => '<p>Engenheiro Civil responsável pelo planejamento, execução e acompanhamento técnico das obras da Tiguen Construtora.</p>',
            'linkedin' => '',
            'imagem'   => 'michel.jpeg',
        ],
        [
            'nome'     => 'Vanessa Cristina Placedes',
            'cargo'    => 'Engenheira Civil',
            'bio'      => '<p>Engenheira Civil atuando no planejamento, execução e controle de qualidade dos projetos e obras da Tiguen Construtora.</p>',
            'linkedin' => '',
            'imagem'   => 'vanessa.jpeg',
        ],
        [
            'nome'     => 'Waitter Assis Abdala Schmidt',
            'cargo'    => 'Departamento de Compras',
            'bio'      => '<p>Responsável pelo Departamento de Compras da Tiguen Construtora, cuidando da aquisição de materiais, gestão de fornecedores e controle de suprimentos das obras.</p>',
            'linkedin' => '',
            'imagem'   => 'Waitter.jpeg',
        ],
        [
            'nome'     => 'Danielle de Sena',
            'cargo'    => 'Departamento Financeiro',
            'bio'      => '<p>Responsável pelo Departamento Financeiro da Tiguen Construtora, cuidando da gestão financeira, controle orçamentário e fluxo de caixa da empresa.</p>',
            'linkedin' => '',
            'imagem'   => 'Danielle.jpeg',
        ],
        [
            'nome'     => 'Pamela da Silva Machado',
            'cargo'    => 'Auxiliar Administrativo',
            'bio'      => '<p>Auxiliar Administrativo da Tiguen Construtora, atuando no suporte às rotinas administrativas e atendimento interno e externo.</p>',
            'linkedin' => '',
            'imagem'   => 'Pamela.jpeg',
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

// ─── SEED: BLOG ──────────────────────────────────────────────────────────────

function tiguen_seed_blog() {
    $results = [];

    if ( ! term_exists('Construção Civil', 'category') ) {
        wp_insert_term('Construção Civil', 'category', ['slug' => 'construcao-civil']);
    }
    if ( ! term_exists('Dicas e Planejamento', 'category') ) {
        wp_insert_term('Dicas e Planejamento', 'category', ['slug' => 'dicas-planejamento']);
    }

    $posts = [
        [
            'titulo'   => 'Como planejar a reforma da sua casa sem surpresas no orçamento',
            'excerpt'  => 'Reformar pode ser mais tranquilo do que parece. Descubra como planejar cada etapa e evitar os erros mais comuns que encarecem as obras.',
            'content'  => '<p>Uma das maiores preocupações de quem decide reformar a casa é a falta de controle sobre o orçamento. Com planejamento, é possível evitar a maior parte dessas surpresas.</p><h2>1. Levantamento completo antes de começar</h2><p>Antes de qualquer trabalho, contrate um engenheiro para realizar uma vistoria completa do imóvel. Problemas elétricos, hidráulicos ou estruturais não identificados no início são a principal causa de estouro de orçamento em reformas.</p><h2>2. Projeto detalhado e especificações claras</h2><p>Um projeto bem elaborado define exatamente o que será executado, quais materiais serão utilizados e qual é a sequência de trabalho. Com isso, o orçamento fica muito mais preciso e os imprevistos são minimizados.</p><h2>3. Reserve uma margem para imprevistos</h2><p>Mesmo com o melhor planejamento, obras sempre apresentam algum imprevisto. O ideal é reservar entre 10% e 15% do valor total como fundo de contingência.</p><h2>4. Contrate profissionais qualificados</h2><p>O preço mais baixo nem sempre é a melhor escolha. Profissionais sem qualificação geram retrabalho, atrasos e custos adicionais que superam qualquer economia inicial.</p>',
            'category' => 'dicas-planejamento',
            'imagem'   => 'blog-reforma-interior.jpg',
            'data'     => '2024-03-15',
        ],
        [
            'titulo'   => 'Construção civil em 2024: tendências que estão transformando as obras',
            'excerpt'  => 'Da sustentabilidade ao uso de tecnologia BIM, saiba quais são as principais inovações que chegaram ao mercado da construção civil.',
            'content'  => '<p>O setor de construção civil passou por uma transformação significativa nos últimos anos. Novas tecnologias, práticas sustentáveis e metodologias de gestão estão mudando a forma como as obras são planejadas e executadas.</p><h2>Tecnologia BIM</h2><p>O BIM permite criar um modelo digital completo da edificação antes mesmo de iniciar a obra. Isso possibilita identificar conflitos entre projetos, simular o comportamento estrutural e prever com precisão os custos de execução.</p><h2>Construção sustentável</h2><p>O mercado exige cada vez mais obras com menor impacto ambiental. Isso inclui uso de materiais reciclados, sistemas de captação de água da chuva, painéis solares e isolamento térmico eficiente.</p><h2>Industrialização e pré-fabricados</h2><p>Estruturas metálicas e painéis pré-fabricados reduzem o tempo de obra em até 40% e garantem maior controle de qualidade.</p>',
            'category' => 'construcao-civil',
            'imagem'   => 'blog-construcao-civil.jpg',
            'data'     => '2024-02-20',
        ],
        [
            'titulo'   => 'Por que contratar uma empresa de engenharia para sua obra?',
            'excerpt'  => 'Muitos optam por contratar diretamente pedreiros e mestres de obras. Entenda por que contar com uma empresa de engenharia pode ser mais econômico a longo prazo.',
            'content'  => '<p>Quando o assunto é construção ou reforma, a decisão de contratar ou não uma empresa de engenharia pode fazer toda a diferença no resultado final.</p><h2>Responsabilidade técnica e legal</h2><p>Toda obra necessita de um responsável técnico habilitado pelo CREA. Uma empresa de engenharia assume a responsabilidade técnica pela obra, protegendo o proprietário juridicamente e garantindo que as normas técnicas sejam seguidas.</p><h2>Planejamento e controle de custos</h2><p>Com um engenheiro responsável, a obra tem projeto executivo, cronograma detalhado e orçamento preciso. Isso evita o "efeito bola de neve", onde pequenos problemas se transformam em grandes despesas.</p><h2>Cumprimento de prazos</h2><p>A gestão profissional garante que as equipes trabalhem de forma coordenada, os materiais cheguem no tempo certo e o cronograma seja respeitado.</p>',
            'category' => 'dicas-planejamento',
            'imagem'   => 'blog-engenharia-projeto.jpg',
            'data'     => '2024-01-10',
        ],
    ];

    foreach ( $posts as $p ) {

        $existing = get_posts(['post_type' => 'post', 'title' => $p['titulo'], 'numberposts' => 1]);
        if ( $existing ) {
            $results[] = [ 'status' => 'skip', 'msg' => "Post '{$p['titulo']}': já existe." ];
            continue;
        }

        $cat    = get_term_by('slug', $p['category'], 'category');
        $cat_id = $cat ? [ $cat->term_id ] : [];

        $post_id = wp_insert_post([
            'post_title'    => $p['titulo'],
            'post_excerpt'  => $p['excerpt'],
            'post_content'  => $p['content'],
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'post_category' => $cat_id,
            'post_date'     => $p['data'] . ' 09:00:00',
        ]);

        if ( is_wp_error($post_id) ) {
            $results[] = [ 'status' => 'error', 'msg' => "Erro: " . $post_id->get_error_message() ];
            continue;
        }

        $img_id = tiguen_get_attachment_by_filename( $p['imagem'] );
        if ( $img_id ) set_post_thumbnail( $post_id, $img_id );

        $results[] = [
            'status' => 'created',
            'msg'    => "Post '{$p['titulo']}' criado" . ($img_id ? ' com imagem' : ' (importe a imagem primeiro)'),
            'url'    => get_edit_post_link($post_id),
        ];
    }

    return $results;
}
