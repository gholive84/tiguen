<?php
/**
 * Single: Projeto
 */
get_header();

while ( have_posts() ) :
    the_post();

    $area        = get_post_meta( get_the_ID(), '_projeto_area', true );
    $ano         = get_post_meta( get_the_ID(), '_projeto_ano', true );
    $localizacao = get_post_meta( get_the_ID(), '_projeto_localizacao', true );
    $galeria_ids = get_post_meta( get_the_ID(), '_projeto_galeria', true );
    $video_url   = get_post_meta( get_the_ID(), '_projeto_video_url', true );
    $tipos       = get_the_terms( get_the_ID(), 'tipo_obra' );
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
            <a href="<?php echo esc_url( get_post_type_archive_link('projetos') ); ?>" class="back-link">← Todos os projetos</a>
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

        <!-- GALERIA -->
        <?php if ( $galeria_ids ) :
            $ids = array_filter( array_map( 'intval', explode( ',', $galeria_ids ) ) );
            if ( ! empty( $ids ) ) : ?>
                <div class="projeto-galeria">
                    <h2>Galeria de Fotos</h2>
                    <div class="galeria-grid" id="projeto-galeria">
                        <?php foreach ( $ids as $img_id ) :
                            $img_url  = wp_get_attachment_image_url( $img_id, 'projeto-galeria' );
                            $img_full = wp_get_attachment_image_url( $img_id, 'full' );
                            $img_alt  = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
                            if ( ! $img_url ) continue;
                        ?>
                            <a href="<?php echo esc_url( $img_full ); ?>" class="galeria-item" data-lightbox="projeto">
                                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif;
        endif; ?>

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
