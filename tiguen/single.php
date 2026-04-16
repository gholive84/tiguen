<?php
/**
 * Single: Post do Blog
 */
get_header();
while ( have_posts() ) : the_post(); ?>

<!-- HERO DO POST -->
<section class="page-hero" style="padding:60px 0 48px;">
    <div class="container">
        <a href="<?php echo esc_url(home_url('/blog')); ?>" class="back-link">← Voltar ao blog</a>
        <?php $cats = get_the_category();
        if ( $cats ) : ?>
            <span class="section-label"><?php echo esc_html($cats[0]->name); ?></span>
        <?php endif; ?>
        <h1 class="page-hero__title" style="font-size:clamp(1.6rem,3.5vw,2.8rem);"><?php the_title(); ?></h1>
        <div style="display:flex;gap:16px;align-items:center;margin-top:16px;flex-wrap:wrap;">
            <time style="font-size:.85rem;color:rgba(255,255,255,.6);"><?php echo get_the_date('d \d\e F \d\e Y'); ?></time>
            <span style="color:rgba(255,255,255,.3);">•</span>
            <span style="font-size:.85rem;color:rgba(255,255,255,.6);"><?php echo get_the_author(); ?></span>
        </div>
    </div>
</section>

<!-- CONTEÚDO -->
<section class="section section--light">
    <div class="container blog-archive-grid">

        <!-- ARTIGO -->
        <article>
            <?php if ( has_post_thumbnail() ) : ?>
                <div style="margin-bottom:36px;border-radius:var(--radius);overflow:hidden;">
                    <?php the_post_thumbnail('large', ['loading' => 'eager', 'style' => 'width:100%;max-height:480px;object-fit:cover;']); ?>
                </div>
            <?php endif; ?>

            <div class="prose single-content">
                <?php the_content(); ?>
            </div>

            <!-- Tags -->
            <?php $tags = get_the_tags();
            if ( $tags ) : ?>
                <div style="margin-top:32px;padding-top:24px;border-top:2px solid var(--border);display:flex;flex-wrap:wrap;gap:8px;">
                    <?php foreach ( $tags as $tag ) : ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                           style="font-size:.78rem;background:var(--light);border:1px solid var(--border);padding:4px 12px;border-radius:20px;color:var(--text-muted);font-weight:600;">
                            #<?php echo esc_html($tag->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Navegação entre posts -->
            <div style="display:flex;justify-content:space-between;gap:16px;margin-top:40px;padding-top:32px;border-top:2px solid var(--border);">
                <?php
                $prev = get_previous_post();
                $next = get_next_post();
                if ( $prev ) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev)); ?>"
                       style="flex:1;padding:16px;border:1px solid var(--border);border-radius:var(--radius);background:var(--white);font-size:.88rem;">
                        <span style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px;">← Post anterior</span>
                        <strong><?php echo esc_html(get_the_title($prev)); ?></strong>
                    </a>
                <?php endif;
                if ( $next ) : ?>
                    <a href="<?php echo esc_url(get_permalink($next)); ?>"
                       style="flex:1;padding:16px;border:1px solid var(--border);border-radius:var(--radius);background:var(--white);font-size:.88rem;text-align:right;">
                        <span style="font-size:.75rem;color:var(--text-muted);display:block;margin-bottom:4px;">Próximo post →</span>
                        <strong><?php echo esc_html(get_the_title($next)); ?></strong>
                    </a>
                <?php endif; ?>
            </div>
        </article>

        <!-- SIDEBAR -->
        <aside class="blog-sidebar">

            <div class="sidebar-widget">
                <h4>Categorias</h4>
                <?php $cats_all = get_categories(['hide_empty' => true]);
                if ( $cats_all ) : ?>
                    <ul style="list-style:none;">
                        <?php foreach ( $cats_all as $cat ) : ?>
                            <li style="margin-bottom:8px;">
                                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                                   style="font-size:.9rem;color:var(--text-muted);">
                                    <?php echo esc_html($cat->name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <?php $recentes = new WP_Query(['post_type' => 'post', 'posts_per_page' => 5, 'post__not_in' => [get_the_ID()]]);
            if ( $recentes->have_posts() ) : ?>
                <div class="sidebar-widget">
                    <h4>Leia também</h4>
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

            <div class="sidebar-widget" style="background:var(--primary);border-color:var(--primary);text-align:center;">
                <h4 style="color:rgba(255,255,255,.6);">Tem um projeto?</h4>
                <p style="color:rgba(255,255,255,.85);font-size:.9rem;margin-bottom:16px;">Solicite um orçamento sem compromisso.</p>
                <a href="<?php echo esc_url(home_url('/contato')); ?>" class="btn btn-accent btn-block">Falar conosco</a>
            </div>

        </aside>
    </div>
</section>

<style>
.single-content p        { margin-bottom: 1.3em; color: var(--text-muted); line-height: 1.8; font-size: 1.02rem; }
.single-content h2       { font-size: 1.5rem; font-weight: 800; margin: 1.8em 0 .7em; color: var(--text); }
.single-content h3       { font-size: 1.2rem; font-weight: 700; margin: 1.5em 0 .6em; color: var(--text); }
.single-content ul, .single-content ol { padding-left: 1.5em; margin-bottom: 1.2em; color: var(--text-muted); }
.single-content li       { margin-bottom: .5em; line-height: 1.7; }
.single-content img      { border-radius: var(--radius); margin: 1.5em 0; box-shadow: var(--shadow); }
.single-content blockquote {
    border-left: 4px solid var(--primary); margin: 1.5em 0;
    padding: 16px 20px; background: var(--light); border-radius: 0 8px 8px 0;
    font-style: italic; color: var(--text);
}
.single-content a   { color: var(--primary); text-decoration: underline; }
.single-content strong { color: var(--text); }
</style>

<?php endwhile; get_footer(); ?>
