<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header">
    <div class="container">
        <div class="site-branding">
            <?php
            if ( has_custom_logo() ) :
                the_custom_logo();
            else :
            ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title">
                    <?php bloginfo( 'name' ); ?>
                </a>
            <?php endif; ?>
        </div>

        <nav id="site-navigation" class="main-navigation" aria-label="Menu principal">
            <?php wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
            ]); ?>
        </nav>

        <button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="Abrir menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6"  x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
</header>
