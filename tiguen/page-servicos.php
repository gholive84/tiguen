<?php
/**
 * Template Name: Serviços
 * Slug da página: servicos
 */
get_header(); ?>

<!-- HERO SERVIÇOS -->
<section class="page-hero page-hero--servicos">
    <div class="container">
        <span class="section-label">O que fazemos</span>
        <h1 class="page-hero__title">Nossos <span class="highlight">Serviços</span></h1>
        <p class="page-hero__sub">Soluções completas em engenharia e construção civil para projetos residenciais, comerciais e institucionais.</p>
    </div>
</section>

<!-- LISTA DE SERVIÇOS -->
<section class="section section--white">
    <div class="container">
        <div class="servicos-grid">
            <?php
            $servicos = new WP_Query([
                'post_type'      => 'servicos',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);

            if ( $servicos->have_posts() ) :
                while ( $servicos->have_posts() ) :
                    $servicos->the_post(); ?>
                    <div class="servico-card" data-animate>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="servico-card__thumb">
                                <?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="servico-card__body">
                            <h3 class="servico-card__title"><?php the_title(); ?></h3>
                            <div class="servico-card__content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Serviços padrão enquanto não há CPT cadastrado
                $servicos_default = [
                    [ 'icon' => 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z', 'titulo' => 'Construção Residencial', 'texto' => 'Casas e edifícios residenciais construídos com qualidade e atenção a cada detalhe, do projeto à entrega.' ],
                    [ 'icon' => 'M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zm-9 5H7m6 0h4', 'titulo' => 'Construção Comercial', 'texto' => 'Projetos comerciais e corporativos com soluções sob medida para cada tipo de negócio.' ],
                    [ 'icon' => 'M2 20h20M6 20V10m6 10V4m6 16V14', 'titulo' => 'Obras Institucionais', 'texto' => 'Escolas, creches, unidades de saúde e obras públicas com excelência técnica e cumprimento de normas.' ],
                    [ 'icon' => 'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z', 'titulo' => 'Reformas', 'texto' => 'Reformas e renovações com planejamento detalhado, minimizando impacto no dia a dia do cliente.' ],
                    [ 'icon' => 'M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 0 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 0-2-2V9m0 0h18', 'titulo' => 'Inovação e Planejamento', 'texto' => 'Uso de tecnologias modernas de gestão e planejamento para garantir precisão no orçamento e no prazo.' ],
                    [ 'icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z', 'titulo' => 'Arquitetura', 'texto' => 'Projetos arquitetônicos únicos desenvolvidos por profissionais especializados, alinhados às suas necessidades.' ],
                ];
                foreach ( $servicos_default as $s ) : ?>
                    <div class="servico-card" data-animate>
                        <div class="servico-card__icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="<?php echo esc_attr( $s['icon'] ); ?>"/>
                            </svg>
                        </div>
                        <div class="servico-card__body">
                            <h3 class="servico-card__title"><?php echo esc_html( $s['titulo'] ); ?></h3>
                            <p class="servico-card__content"><?php echo esc_html( $s['texto'] ); ?></p>
                        </div>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- CTA CONTATO -->
<section class="section section--dark cta-section">
    <div class="container cta-inner">
        <div>
            <h2 class="cta-title">Tem um projeto em mente?</h2>
            <p class="cta-sub">Entre em contato e receba um orçamento sem compromisso.</p>
        </div>
        <div class="cta-actions">
            <a href="<?php echo esc_url( get_permalink( get_page_by_path('contato') ) ); ?>" class="btn btn-primary">Solicitar orçamento</a>
            <a href="https://wa.me/5541305800377" target="_blank" rel="noopener" class="btn btn-whatsapp">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                WhatsApp
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
