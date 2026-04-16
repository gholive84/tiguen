<?php
/**
 * Template Name: Projetos
 * Slug da página: projetos
 */
get_header(); ?>

<!-- HERO PROJETOS -->
<section class="page-hero page-hero--projetos">
    <div class="container">
        <span class="section-label">Portfólio</span>
        <h1 class="page-hero__title">Nossas <span class="highlight">Obras</span></h1>
        <p class="page-hero__sub">Projetos residenciais, comerciais e institucionais entregues com qualidade e no prazo.</p>
    </div>
</section>

<!-- FILTROS -->
<section class="section section--white projetos-section">
    <div class="container">

        <?php
        $tipos = get_terms([ 'taxonomy' => 'tipo_obra', 'hide_empty' => true ]);
        if ( ! is_wp_error( $tipos ) && ! empty( $tipos ) ) : ?>
            <div class="filtros-projetos">
                <button class="filtro-btn active" data-filter="*">Todos</button>
                <?php foreach ( $tipos as $tipo ) : ?>
                    <button class="filtro-btn" data-filter="<?php echo esc_attr( $tipo->slug ); ?>">
                        <?php echo esc_html( $tipo->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- GRID DE PROJETOS -->
        <div class="projetos-grid" id="projetos-grid">
            <?php
            $projetos = new WP_Query([
                'post_type'      => 'projetos',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if ( $projetos->have_posts() ) :
                while ( $projetos->have_posts() ) :
                    $projetos->the_post();
                    $area    = get_post_meta( get_the_ID(), '_projeto_area', true );
                    $ano     = get_post_meta( get_the_ID(), '_projeto_ano', true );
                    $loc     = get_post_meta( get_the_ID(), '_projeto_localizacao', true );
                    $tipos_p = get_the_terms( get_the_ID(), 'tipo_obra' );
                    $slug    = $tipos_p && ! is_wp_error( $tipos_p ) ? $tipos_p[0]->slug : '';
                    ?>
                    <article class="projeto-card" data-tipo="<?php echo esc_attr( $slug ); ?>">
                        <a href="<?php the_permalink(); ?>" class="projeto-card__link">
                            <div class="projeto-card__thumb">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'projeto-thumb', [ 'loading' => 'lazy' ] ); ?>
                                <?php else : ?>
                                    <div class="projeto-card__no-img"></div>
                                <?php endif; ?>
                                <?php if ( $tipos_p && ! is_wp_error( $tipos_p ) ) : ?>
                                    <span class="projeto-card__tipo"><?php echo esc_html( $tipos_p[0]->name ); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="projeto-card__body">
                                <h3 class="projeto-card__title"><?php the_title(); ?></h3>
                                <?php if ( has_excerpt() ) : ?>
                                    <p class="projeto-card__excerpt"><?php the_excerpt(); ?></p>
                                <?php endif; ?>
                                <div class="projeto-card__meta">
                                    <?php if ( $area ) : ?><span><?php echo esc_html( $area ); ?> m²</span><?php endif; ?>
                                    <?php if ( $loc )  : ?><span><?php echo esc_html( $loc ); ?></span><?php endif; ?>
                                    <?php if ( $ano )  : ?><span><?php echo esc_html( $ano ); ?></span><?php endif; ?>
                                </div>
                                <span class="projeto-card__cta">Ver projeto →</span>
                            </div>
                        </a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="no-content">
                    <p>Em breve nossos projetos serão publicados aqui.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
