<?php
/**
 * Template Name: Blog
 * Slug da página: blog
 */
get_header(); ?>

<!-- HERO BLOG -->
<section class="page-hero page-hero--blog">
    <div class="container">
        <span class="section-label">Conteúdo</span>
        <h1 class="page-hero__title">Blog e <span class="highlight">Notícias</span></h1>
        <p class="page-hero__sub">Informação sobre engenharia, construção civil e gestão de obras.</p>
    </div>
</section>

<!-- CONTEÚDO -->
<section class="section section--light">
    <div class="container blog-archive-grid">

        <!-- POSTS -->
        <div class="blog-posts-list">
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $posts = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 8,
                'paged'          => $paged,
                'post_status'    => 'publish',
            ]);

            if ( $posts->have_posts() ) :
                while ( $posts->have_posts() ) :
                    $posts->the_post(); ?>
                    <article class="blog-post-card" data-animate>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="blog-post-card__thumb">
                                <?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="blog-post-card__body">
                            <time class="blog-post-card__date"><?php echo get_the_date('d \d\e F \d\e Y'); ?></time>
                            <h2 class="blog-post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="blog-post-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                            <a href="<?php the_permalink(); ?>" class="blog-post-card__cta">Ler artigo completo →</a>
                        </div>
                    </article>
                <?php endwhile;

                // Paginação
                $total = $posts->max_num_pages;
                if ( $total > 1 ) : ?>
                    <nav class="pagination" aria-label="Paginação">
                        <?php echo paginate_links([
                            'total'   => $total,
                            'current' => $paged,
                            'prev_text' => '←',
                            'next_text' => '→',
                        ]); ?>
                    </nav>
                <?php endif;

                wp_reset_postdata();
            else : ?>
                <div class="no-content">
                    <p>Nenhum post publicado ainda. Em breve teremos conteúdo por aqui.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR -->
        <aside class="blog-sidebar">

            <!-- Busca -->
            <div class="sidebar-widget">
                <h4>Buscar</h4>
                <?php get_search_form(); ?>
            </div>

            <!-- Categorias -->
            <?php $cats = get_categories(['hide_empty' => true]);
            if ( $cats ) : ?>
                <div class="sidebar-widget">
                    <h4>Categorias</h4>
                    <ul style="list-style:none;">
                        <?php foreach ( $cats as $cat ) : ?>
                            <li style="margin-bottom:8px;">
                                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                                   style="font-size:.9rem;color:var(--text-muted);display:flex;justify-content:space-between;">
                                    <?php echo esc_html($cat->name); ?>
                                    <span style="color:var(--text-muted);font-size:.8rem;">(<?php echo $cat->count; ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Posts recentes -->
            <?php $recentes = new WP_Query(['post_type' => 'post', 'posts_per_page' => 5]);
            if ( $recentes->have_posts() ) : ?>
                <div class="sidebar-widget">
                    <h4>Posts recentes</h4>
                    <ul style="list-style:none;">
                        <?php while ( $recentes->have_posts() ) : $recentes->the_post(); ?>
                            <li style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border);">
                                <a href="<?php the_permalink(); ?>" style="font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4;display:block;">
                                    <?php the_title(); ?>
                                </a>
                                <time style="font-size:.75rem;color:var(--text-muted);"><?php echo get_the_date('d/m/Y'); ?></time>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- CTA -->
            <div class="sidebar-widget" style="background:var(--primary);border-color:var(--primary);text-align:center;">
                <h4 style="color:rgba(255,255,255,0.6);">Tem um projeto?</h4>
                <p style="color:rgba(255,255,255,0.85);font-size:.9rem;margin-bottom:16px;">Solicite um orçamento sem compromisso.</p>
                <a href="<?php echo esc_url(home_url('/contato')); ?>" class="btn btn-accent btn-block">
                    Falar conosco
                </a>
            </div>

        </aside>
    </div>
</section>

<?php get_footer(); ?>
