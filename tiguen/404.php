<?php get_header(); ?>

<main id="main" class="site-main">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title">404 — Página não encontrada</h1>
        </header>
        <div class="page-content">
            <p>A página que você está procurando não existe ou foi movida.</p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Voltar ao início</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
