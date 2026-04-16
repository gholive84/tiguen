<?php
/**
 * Template Name: Serviços
 * Slug da página: servicos
 */
get_header(); ?>

<!-- HERO SERVIÇOS -->
<section class="page-hero page-hero--servicos">
    <div class="container">
        <span class="section-label">O que fazemos</span>
        <h1 class="page-hero__title">Nossos <span class="highlight">Serviços</span></h1>
        <p class="page-hero__sub">Soluções completas em engenharia e construção civil para projetos residenciais, comerciais e institucionais.</p>
    </div>
</section>

<!-- INTRO -->
<section class="section section--white">
    <div class="container">
        <div class="servicos-intro">
            <p>A Tiguen Engenharia atua com excelência em todas as etapas da construção civil — do projeto ao acabamento. Nossa equipe de engenheiros e arquitetos garante qualidade técnica, cumprimento de prazos e total transparência em cada obra.</p>
        </div>

        <!-- GRID DE SERVIÇOS -->
        <div class="servicos-full-grid">

            <div class="servico-full-card">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/servico-arquitetura.jpg' ); ?>" alt="Arquitetura" class="servico-full-card__img" loading="lazy">
                <div class="servico-full-card__body">
                    <div class="servico-full-card__icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <h3>Arquitetura</h3>
                    <p>Projetamos espaços únicos com funcionalidade e estética, do conceito ao detalhamento executivo, alinhados às necessidades e ao perfil de cada cliente.</p>
                    <ul>
                        <li>Projetos arquitetônicos residenciais e comerciais</li>
                        <li>Plantas, cortes, fachadas e detalhamentos</li>
                        <li>Aprovação em prefeitura e CREA</li>
                        <li>Compatibilização com projetos complementares</li>
                    </ul>
                </div>
            </div>

            <div class="servico-full-card">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/servico-inovacao.jpg' ); ?>" alt="Inovação e Planejamento" class="servico-full-card__img" loading="lazy">
                <div class="servico-full-card__body">
                    <div class="servico-full-card__icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h3>Inovação e Planejamento</h3>
                    <p>Utilizamos ferramentas tecnológicas de última geração para criar, planejar e gerenciar seu projeto do início ao fim com precisão e eficiência.</p>
                    <ul>
                        <li>Modelagem BIM e projetos 3D</li>
                        <li>Cronograma físico-financeiro detalhado</li>
                        <li>Gestão integrada de obra</li>
                        <li>Relatórios periódicos de acompanhamento</li>
                    </ul>
                </div>
            </div>

            <div class="servico-full-card">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/servico-reformas.jpg' ); ?>" alt="Reformas" class="servico-full-card__img" loading="lazy">
                <div class="servico-full-card__body">
                    <div class="servico-full-card__icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </div>
                    <h3>Reformas</h3>
                    <p>Reformas residenciais e comerciais planejadas com rigor técnico, mínima interferência na rotina e resultado que valoriza seu imóvel.</p>
                    <ul>
                        <li>Reformas completas e parciais</li>
                        <li>Ampliações e acréscimos</li>
                        <li>Retrofit e requalificação de ambientes</li>
                        <li>Impermeabilização e revestimentos</li>
                    </ul>
                </div>
            </div>

            <div class="servico-full-card">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/servico-empreendimentos.jpg' ); ?>" alt="Empreendimentos" class="servico-full-card__img" loading="lazy">
                <div class="servico-full-card__body">
                    <div class="servico-full-card__icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 20h20M6 20V10m6 10V4m6 16V14"/></svg>
                    </div>
                    <h3>Empreendimentos</h3>
                    <p>Desenvolvemos empreendimentos residenciais e comerciais do projeto à entrega, com controle total de qualidade e foco na valorização do produto final.</p>
                    <ul>
                        <li>Condomínios residenciais e loteamentos</li>
                        <li>Edifícios comerciais e mistos</li>
                        <li>Acompanhamento técnico em todas as fases</li>
                        <li>Gestão de fornecedores e equipes especializadas</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- DIFERENCIAIS -->
<section class="section section--dark">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-label" style="color:rgba(255,255,255,0.5)">Por que a Tiguen</span>
            <h2 class="section-title" style="color:#fff">Nosso compromisso com <span class="highlight">cada obra</span></h2>
        </div>
        <div class="diferenciais-grid">
            <div class="diferencial-item">
                <div class="diferencial-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h4>Qualidade Técnica</h4>
                <p>Engenheiros e arquitetos experientes em cada etapa, com controle de qualidade rigoroso.</p>
            </div>
            <div class="diferencial-item">
                <div class="diferencial-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h4>Entrega no Prazo</h4>
                <p>Cronogramas realistas e gestão eficiente para garantir a entrega conforme contratado.</p>
            </div>
            <div class="diferencial-item">
                <div class="diferencial-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h4>Segurança e Normas</h4>
                <p>Cumprimento integral das normas ABNT, NRs de segurança do trabalho e legislações vigentes.</p>
            </div>
            <div class="diferencial-item">
                <div class="diferencial-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h4>Transparência</h4>
                <p>Relatórios periódicos, acesso às notas fiscais e comunicação direta durante toda a obra.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA CONTATO -->
<section class="section section--white cta-section" style="background:var(--light)">
    <div class="container cta-inner">
        <div>
            <h2 class="cta-title" style="color:var(--dark)">Tem um projeto em mente?</h2>
            <p class="cta-sub" style="color:var(--text-muted)">Entre em contato e receba um orçamento sem compromisso.</p>
        </div>
        <div class="cta-actions">
            <a href="<?php echo esc_url( home_url('/contato') ); ?>" class="btn btn-primary">Solicitar orçamento</a>
            <a href="https://wa.me/5541305800377" target="_blank" rel="noopener" class="btn btn-whatsapp">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                WhatsApp
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
