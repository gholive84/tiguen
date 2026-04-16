<?php
/**
 * Template Name: Principal (Home)
 * Slug da página: principal
 */
get_header(); ?>

<!-- ═══════════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════════ -->
<section class="hero">
    <div class="hero__bg">
        <?php
        // Imagem de fundo — definida como destaque da página "principal"
        if ( has_post_thumbnail() ) :
            the_post_thumbnail( 'full', [ 'class' => 'hero__bg-img', 'loading' => 'eager' ] );
        endif;
        ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <span class="section-label">Engenharia e Construção Civil</span>
        <h1 class="hero__title">
            Construímos com<br>
            <span class="highlight">qualidade</span> e no prazo
        </h1>
        <p class="hero__sub">
            Da fundação ao acabamento, realizamos obras residenciais, comerciais e institucionais em São José dos Pinhais e região.
        </p>
        <div class="hero__actions">
            <a href="<?php echo esc_url( home_url('/contato') ); ?>" class="btn btn-accent">
                Solicitar orçamento
            </a>
            <a href="<?php echo esc_url( home_url('/projetos') ); ?>" class="btn btn-ghost">
                Ver projetos
            </a>
        </div>
    </div>
    <div class="hero__scroll">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     CONTADORES DE OBRA
════════════════════════════════════════════════════════════ -->
<section class="section section--dark counters-section">
    <div class="container">
        <div class="counters-grid">
            <div class="counter-item" data-animate>
                <span class="counter-number" data-counter="120">0</span>
                <span class="counter-label">Obras entregues</span>
            </div>
            <div class="counter-item" data-animate>
                <span class="counter-number" data-counter="45000">0</span>
                <span class="counter-suffix">m²</span>
                <span class="counter-label">Construídos</span>
            </div>
            <div class="counter-item" data-animate>
                <span class="counter-number" data-counter="15">0</span>
                <span class="counter-label">Anos de experiência</span>
            </div>
            <div class="counter-item" data-animate>
                <span class="counter-number" data-counter="100">0</span>
                <span class="counter-suffix">%</span>
                <span class="counter-label">Comprometidos com o prazo</span>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SERVIÇOS
════════════════════════════════════════════════════════════ -->
<section class="section section--light">
    <div class="container">
        <div class="section-header">
            <span class="section-label">O que fazemos</span>
            <h2 class="section-title">Nossas especialidades</h2>
            <p class="section-sub">Soluções completas em engenharia para cada tipo de projeto.</p>
        </div>
        <div class="home-servicos-grid">
            <?php
            $servicos = new WP_Query([
                'post_type'      => 'servicos',
                'posts_per_page' => 6,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);

            if ( $servicos->have_posts() ) :
                while ( $servicos->have_posts() ) :
                    $servicos->the_post(); ?>
                    <div class="home-servico-card" data-animate>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="home-servico-card__thumb">
                                <?php the_post_thumbnail('medium', ['loading' => 'lazy']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="home-servico-card__body">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words( get_the_content(), 20 ); ?></p>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else :
                $items = [
                    ['icon' => 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z',                           'titulo' => 'Construção Residencial',   'texto' => 'Casas e edifícios do projeto à entrega, com qualidade e atenção ao detalhe.'],
                    ['icon' => 'M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z', 'titulo' => 'Construção Comercial',    'texto' => 'Projetos corporativos e comerciais com soluções sob medida para cada negócio.'],
                    ['icon' => 'M2 20h20M6 20V10m6 10V4m6 16V14',                                            'titulo' => 'Obras Institucionais',    'texto' => 'Escolas, creches e unidades de saúde com excelência técnica.'],
                    ['icon' => 'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z', 'titulo' => 'Reformas',                'texto' => 'Reformas planejadas com mínimo impacto, máxima eficiência.'],
                    ['icon' => 'M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 0 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 0-2-2V9m0 0h18', 'titulo' => 'Inovação e Planejamento', 'texto' => 'Gestão e tecnologia para garantir precisão no orçamento e prazo.'],
                    ['icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',                               'titulo' => 'Arquitetura',             'texto' => 'Projetos arquitetônicos únicos alinhados às suas necessidades.'],
                ];
                foreach ( $items as $item ) : ?>
                    <div class="home-servico-card" data-animate>
                        <div class="home-servico-card__icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="<?php echo esc_attr($item['icon']); ?>"/>
                            </svg>
                        </div>
                        <div class="home-servico-card__body">
                            <h3><?php echo esc_html($item['titulo']); ?></h3>
                            <p><?php echo esc_html($item['texto']); ?></p>
                        </div>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
        <div class="section-cta">
            <a href="<?php echo esc_url( home_url('/servicos') ); ?>" class="btn btn-outline">
                Ver todos os serviços
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SOBRE — STRIP
════════════════════════════════════════════════════════════ -->
<section class="section section--white">
    <div class="container sobre-strip">
        <div class="sobre-strip__content" data-animate>
            <span class="section-label">Quem somos</span>
            <h2 class="section-title" style="text-align:left;">Engenharia com <span class="highlight">propósito</span></h2>
            <p>Com mais de 15 anos de atuação na região de São José dos Pinhais, a Tiguen Engenharia entrega obras residenciais, comerciais e institucionais com rigor técnico, transparência e foco no resultado do cliente.</p>
            <p>Nossa equipe de engenheiros e arquitetos especializados garante que cada projeto seja executado dentro do prazo e do orçamento acordados.</p>
            <a href="<?php echo esc_url( home_url('/sobre') ); ?>" class="btn btn-primary" style="margin-top:16px;">
                Conheça a Tiguen
            </a>
        </div>
        <div class="sobre-strip__image" data-animate>
            <?php
            // Tenta pegar imagem da página "sobre" ou usa placeholder
            $sobre_page = get_page_by_path('sobre');
            if ( $sobre_page && has_post_thumbnail($sobre_page->ID) ) {
                echo get_the_post_thumbnail($sobre_page->ID, 'large', ['class' => 'sobre-img', 'loading' => 'lazy']);
            } else { ?>
                <div class="sobre-img sobre-img--placeholder">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     PROJETOS EM DESTAQUE
════════════════════════════════════════════════════════════ -->
<section class="section section--light">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Portfólio</span>
            <h2 class="section-title">Projetos em destaque</h2>
            <p class="section-sub">Conheça algumas das obras que entregamos com excelência.</p>
        </div>
        <div class="projetos-grid">
            <?php
            $projetos = new WP_Query([
                'post_type'      => 'projetos',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if ( $projetos->have_posts() ) :
                while ( $projetos->have_posts() ) :
                    $projetos->the_post();
                    $area    = get_post_meta( get_the_ID(), '_projeto_area', true );
                    $loc     = get_post_meta( get_the_ID(), '_projeto_localizacao', true );
                    $tipos   = get_the_terms( get_the_ID(), 'tipo_obra' );
                    ?>
                    <article class="projeto-card" data-animate>
                        <a href="<?php the_permalink(); ?>" class="projeto-card__link">
                            <div class="projeto-card__thumb">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail('projeto-thumb', ['loading' => 'lazy']); ?>
                                <?php else : ?>
                                    <div class="projeto-card__no-img"></div>
                                <?php endif; ?>
                                <?php if ( $tipos && !is_wp_error($tipos) ) : ?>
                                    <span class="projeto-card__tipo"><?php echo esc_html($tipos[0]->name); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="projeto-card__body">
                                <h3 class="projeto-card__title"><?php the_title(); ?></h3>
                                <div class="projeto-card__meta">
                                    <?php if ( $area ) : ?><span><?php echo esc_html($area); ?> m²</span><?php endif; ?>
                                    <?php if ( $loc )  : ?><span><?php echo esc_html($loc); ?></span><?php endif; ?>
                                </div>
                                <span class="projeto-card__cta">Ver projeto →</span>
                            </div>
                        </a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="no-content" style="grid-column:1/-1">
                    <p>Em breve nossos projetos serão publicados aqui.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="section-cta">
            <a href="<?php echo esc_url( home_url('/projetos') ); ?>" class="btn btn-outline">
                Ver todos os projetos
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     DIFERENCIAIS
════════════════════════════════════════════════════════════ -->
<section class="section section--dark">
    <div class="container">
        <div class="section-header section-header--light">
            <span class="section-label">Por que a Tiguen</span>
            <h2 class="section-title" style="color:#fff;">Nossos diferenciais</h2>
        </div>
        <div class="diferenciais-grid">
            <div class="diferencial-item" data-animate>
                <div class="diferencial-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3>Qualidade certificada</h3>
                <p>Processos rigorosos de controle de qualidade em todas as etapas da obra.</p>
            </div>
            <div class="diferencial-item" data-animate>
                <div class="diferencial-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3>Cumprimento de prazos</h3>
                <p>Planejamento detalhado e gestão eficiente para entrega no prazo contratado.</p>
            </div>
            <div class="diferencial-item" data-animate>
                <div class="diferencial-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Orçamento transparente</h3>
                <p>Sem surpresas financeiras. Orçamento detalhado e compromisso com o valor acordado.</p>
            </div>
            <div class="diferencial-item" data-animate>
                <div class="diferencial-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3>Equipe especializada</h3>
                <p>Engenheiros, arquitetos e técnicos experientes dedicados ao seu projeto.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     BLOG — ÚLTIMAS NOTÍCIAS
════════════════════════════════════════════════════════════ -->
<?php
$posts = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
]);

if ( $posts->have_posts() ) : ?>
<section class="section section--white">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Blog</span>
            <h2 class="section-title">Últimas notícias</h2>
            <p class="section-sub">Informação e conteúdo sobre engenharia e construção civil.</p>
        </div>
        <div class="blog-grid">
            <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
                <article class="blog-card" data-animate>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="blog-card__thumb">
                            <?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
                        </a>
                    <?php endif; ?>
                    <div class="blog-card__body">
                        <time class="blog-card__date"><?php echo get_the_date('d \d\e F \d\e Y'); ?></time>
                        <h3 class="blog-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="blog-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                        <a href="<?php the_permalink(); ?>" class="blog-card__cta">Ler mais →</a>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <div class="section-cta">
            <a href="<?php echo esc_url( home_url('/blog') ); ?>" class="btn btn-outline">Ver todos os posts</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     CTA FINAL
════════════════════════════════════════════════════════════ -->
<section class="cta-final">
    <div class="cta-final__bg">
        <?php
        $projetos_bg = new WP_Query(['post_type' => 'projetos', 'posts_per_page' => 1]);
        if ( $projetos_bg->have_posts() ) {
            $projetos_bg->the_post();
            if ( has_post_thumbnail() ) the_post_thumbnail('projeto-hero', ['loading' => 'lazy']);
            wp_reset_postdata();
        }
        ?>
    </div>
    <div class="cta-final__overlay"></div>
    <div class="container cta-final__content">
        <h2 class="cta-final__title">Pronto para construir seu projeto?</h2>
        <p class="cta-final__sub">Entre em contato e receba um orçamento detalhado sem compromisso.</p>
        <div class="cta-final__actions">
            <a href="<?php echo esc_url( home_url('/contato') ); ?>" class="btn btn-accent">
                Solicitar orçamento
            </a>
            <a href="https://wa.me/5541305800377" target="_blank" rel="noopener" class="btn btn-ghost">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                Falar no WhatsApp
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
