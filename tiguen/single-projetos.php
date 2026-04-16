<?php
/**
 * Single: Projeto
 */
get_header();

while ( have_posts() ) :
    the_post();

    $area        = function_exists('get_field') ? get_field('area')        : get_post_meta( get_the_ID(), '_projeto_area', true );
    $ano         = function_exists('get_field') ? get_field('ano')         : get_post_meta( get_the_ID(), '_projeto_ano', true );
    $localizacao = function_exists('get_field') ? get_field('localizacao') : get_post_meta( get_the_ID(), '_projeto_localizacao', true );
    $video_url   = function_exists('get_field') ? get_field('video_url')   : get_post_meta( get_the_ID(), '_projeto_video_url', true );
    $tipos       = get_the_terms( get_the_ID(), 'tipo_obra' );

    // Galeria: IDs armazenados como string separada por vírgula no meta nativo
    $galeria     = [];
    $galeria_ids = get_post_meta( get_the_ID(), '_projeto_galeria', true );
    if ( $galeria_ids ) {
        foreach ( array_filter( explode( ',', $galeria_ids ) ) as $img_id ) {
            $img_id  = intval( $img_id );
            $full    = wp_get_attachment_image_url( $img_id, 'full' );
            $medium  = wp_get_attachment_image_url( $img_id, 'projeto-galeria' ) ?: $full;
            $alt     = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
            if ( $full ) {
                $galeria[] = [ 'url' => $full, 'medium' => $medium, 'alt' => $alt ];
            }
        }
    }
?>

<!-- HERO DO PROJETO -->
<section class="projeto-hero">
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="projeto-hero__bg">
            <?php the_post_thumbnail( 'projeto-hero', [ 'loading' => 'eager' ] ); ?>
        </div>
    <?php endif; ?>
    <div class="projeto-hero__overlay">
        <div class="container">
            <a href="<?php echo esc_url( home_url('/projetos/') ); ?>" class="back-link">← Todos os projetos</a>
            <?php if ( $tipos && ! is_wp_error( $tipos ) ) : ?>
                <span class="section-label"><?php echo esc_html( $tipos[0]->name ); ?></span>
            <?php endif; ?>
            <h1 class="projeto-hero__title"><?php the_title(); ?></h1>
            <div class="projeto-hero__meta">
                <?php if ( $area )        : ?><span><?php echo esc_html( $area ); ?> m²</span><?php endif; ?>
                <?php if ( $localizacao ) : ?><span><?php echo esc_html( $localizacao ); ?></span><?php endif; ?>
                <?php if ( $ano )         : ?><span><?php echo esc_html( $ano ); ?></span><?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CONTEÚDO DO PROJETO -->
<section class="section section--white">
    <div class="container projeto-layout">

        <div class="projeto-descricao">
            <h2>Sobre o Projeto</h2>
            <div class="prose">
                <?php the_content(); ?>
            </div>
        </div>

        <!-- GALERIA (ACF Gallery) -->
        <?php if ( ! empty( $galeria ) ) : ?>
            <div class="projeto-galeria">
                <h2>Galeria de Fotos</h2>
                <div class="galeria-grid" id="projeto-galeria">
                    <?php foreach ( $galeria as $img ) : ?>
                        <a href="<?php echo esc_url( $img['url'] ); ?>" class="galeria-item" data-lightbox="projeto">
                            <img src="<?php echo esc_url( $img['medium'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- VÍDEO -->
        <?php $embed = tiguen_get_video_embed( $video_url );
        if ( $embed ) : ?>
            <div class="projeto-video">
                <h2>Vídeo do Projeto</h2>
                <div class="video-wrapper">
                    <?php echo $embed; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- OUTROS PROJETOS -->
<section class="section section--light">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Outros Projetos</h2>
        </div>
        <div class="projetos-grid projetos-grid--small">
            <?php
            $outros = new WP_Query([
                'post_type'      => 'projetos',
                'posts_per_page' => 3,
                'post__not_in'   => [ get_the_ID() ],
                'orderby'        => 'rand',
            ]);
            while ( $outros->have_posts() ) :
                $outros->the_post();
                ?>
                <article class="projeto-card">
                    <a href="<?php the_permalink(); ?>" class="projeto-card__link">
                        <div class="projeto-card__thumb">
                            <?php the_post_thumbnail( 'projeto-thumb', [ 'loading' => 'lazy' ] ); ?>
                        </div>
                        <div class="projeto-card__body">
                            <h3 class="projeto-card__title"><?php the_title(); ?></h3>
                            <span class="projeto-card__cta">Ver projeto →</span>
                        </div>
                    </a>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>

<?php endwhile; get_footer(); ?>
