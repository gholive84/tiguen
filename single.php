<?php get_header(); ?>

<main id="main" class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/content', get_post_type() );
        the_post_navigation();
    endwhile;
    ?>
</main>

<?php get_footer(); ?>
