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

<!-- SOBRE: TEXTO PRINCIPAL + VÍDEO -->
<section class="section section--white">
    <div class="container sobre-intro-grid">

        <div class="sobre-intro-content" data-animate>
            <span class="section-label">Quem somos</span>
            <h2 class="section-title" style="text-align:left;">Engenharia com <span class="highlight">propósito</span></h2>

            <p>Com mais de 15 anos de atuação em São José dos Pinhais e na região metropolitana de Curitiba, a Tiguen Engenharia se consolidou como referência em execução de obras residenciais, comerciais e institucionais.</p>

            <p>Nossa história nasceu de uma missão clara: oferecer o que o mercado raramente entrega junto — <strong>qualidade técnica e transparência total</strong>. Acreditamos que cada obra é muito mais do que concreto e aço. É a realização de um sonho, a expansão de um negócio ou o lar de uma família.</p>

            <p>Somos especializados na <strong>execução de projetos prontos</strong>, atuando como construtora responsável por transformar projetos de engenharia em obras concluídas com rigor técnico, dentro do prazo e do orçamento acordados. Do movimento de terra à entrega das chaves, nossa equipe está presente em cada detalhe.</p>

            <p>Ao longo desses anos, entregamos mais de <strong>120 obras</strong>, firmamos parcerias sólidas com fornecedores e profissionais da região, e conquistamos a confiança de centenas de famílias e empresas que realizaram seus projetos conosco.</p>

            <div class="sobre-intro-nums">
                <div class="sobre-intro-num">
                    <strong>+15</strong>
                    <span>anos de mercado</span>
                </div>
                <div class="sobre-intro-num">
                    <strong>+120</strong>
                    <span>obras entregues</span>
                </div>
                <div class="sobre-intro-num">
                    <strong>100%</strong>
                    <span>compromisso com prazo</span>
                </div>
            </div>
        </div>

        <div class="sobre-intro-video" data-animate>
            <div class="sobre-video-wrapper">
                <div class="video-cover video-cover--small" data-video-id="HuXfe3ePKa0" role="button" aria-label="Reproduzir vídeo institucional">
                    <img
                        src="https://img.youtube.com/vi/HuXfe3ePKa0/maxresdefault.jpg"
                        alt="Vídeo institucional Tiguen Engenharia"
                        class="video-cover__thumb">
                    <div class="video-cover__overlay"></div>
                    <button class="video-play-btn video-play-btn--sm" aria-hidden="true">
                        <span class="video-play-btn__ring"></span>
                        <svg class="video-play-btn__icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5.14v14l11-7-11-7z"/>
                        </svg>
                    </button>
                    <span class="video-cover__label">Assistir vídeo</span>
                </div>
            </div>
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

<?php get_footer(); ?>
