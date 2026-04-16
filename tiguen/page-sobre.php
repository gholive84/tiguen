<?php
/**
 * Template Name: Sobre Nós
 * Slug da página: sobre
 */
get_header(); ?>

<!-- HERO SOBRE -->
<section class="page-hero page-hero--sobre">
    <div class="container">
        <span class="section-label">Quem somos</span>
        <h1 class="page-hero__title">Construindo com <span class="highlight">propósito</span> e precisão</h1>
        <p class="page-hero__sub">Engenharia e construção civil com qualidade, compromisso e transparência em cada etapa da obra.</p>
    </div>
</section>

<!-- SOBRE: TEXTO PRINCIPAL -->
<section class="section section--white">
    <div class="container sobre-grid">
        <div class="sobre-content">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
        <div class="sobre-image">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'large', [ 'class' => 'sobre-img', 'loading' => 'lazy' ] );
            }
            ?>
        </div>
    </div>
</section>

<!-- MISSÃO VISÃO VALORES -->
<section class="section section--light">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Nossa essência</span>
            <h2 class="section-title">Missão, Visão e Valores</h2>
        </div>
        <div class="mvv-grid">
            <div class="mvv-card">
                <div class="mvv-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3>Missão</h3>
                <p>Construir obras com excelência técnica, cumprindo prazos e orçamentos, gerando valor para nossos clientes e para a sociedade.</p>
            </div>
            <div class="mvv-card">
                <div class="mvv-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <h3>Visão</h3>
                <p>Ser referência em engenharia e construção civil no Paraná, reconhecida pela qualidade, inovação e confiança em cada projeto entregue.</p>
            </div>
            <div class="mvv-card">
                <div class="mvv-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <h3>Valores</h3>
                <p>Integridade, transparência, compromisso com qualidade, respeito às pessoas e responsabilidade socioambiental em tudo que fazemos.</p>
            </div>
        </div>
    </div>
</section>

<!-- EQUIPE -->
<section class="section section--white">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Nosso time</span>
            <h2 class="section-title">Equipe de Profissionais</h2>
            <p class="section-sub">Engenheiros, arquitetos e técnicos especializados prontos para o seu projeto.</p>
        </div>
        <div class="team-grid">
            <?php
            $equipe = new WP_Query([
                'post_type'      => 'equipe',
                'posts_per_page' => 12,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);
            if ( $equipe->have_posts() ) :
                while ( $equipe->have_posts() ) :
                    $equipe->the_post();
                    $cargo    = get_post_meta( get_the_ID(), '_equipe_cargo', true );
                    $linkedin = get_post_meta( get_the_ID(), '_equipe_linkedin', true );
                    ?>
                    <div class="team-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="team-card__photo">
                                <?php the_post_thumbnail( 'equipe-thumb', [ 'loading' => 'lazy' ] ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="team-card__info">
                            <h3 class="team-card__name"><?php the_title(); ?></h3>
                            <?php if ( $cargo ) : ?>
                                <p class="team-card__cargo"><?php echo esc_html( $cargo ); ?></p>
                            <?php endif; ?>
                            <?php if ( $linkedin ) : ?>
                                <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener" class="team-card__linkedin" aria-label="LinkedIn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                                    LinkedIn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else : ?>
                <p class="no-content">Em breve apresentaremos nossa equipe.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- SELOS E CERTIFICAÇÕES -->
<section class="section section--dark">
    <div class="container">
        <div class="section-header section-header--light">
            <span class="section-label">Qualidade garantida</span>
            <h2 class="section-title">Instituições, Selos e Certificações</h2>
        </div>
        <div class="selos-grid" id="selos-grid">
            <!-- Adicionar logos via WordPress (Aparência > Widgets ou diretamente aqui) -->
            <div class="selos-placeholder">
                <p>Logotipos das certificações serão exibidos aqui.</p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
