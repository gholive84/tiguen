// Tiguen — Main JavaScript

document.addEventListener('DOMContentLoaded', function () {

    // ─── VIDEO COVER ────────────────────────────────────────────
    var videoCover = document.querySelector('.video-cover');
    if (videoCover) {
        videoCover.addEventListener('click', function () {
            var videoId = this.dataset.videoId;
            var wrapper = this.parentElement;
            var iframe  = document.createElement('iframe');
            iframe.src         = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
            iframe.title       = 'Vídeo institucional Tiguen Engenharia';
            iframe.frameBorder = '0';
            iframe.allow       = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            iframe.allowFullscreen = true;
            iframe.style.cssText   = 'position:absolute;top:0;left:0;width:100%;height:100%;border-radius:16px;';
            wrapper.replaceChild(iframe, this);
        });
    }

    // ─── MOBILE MENU ────────────────────────────────────────────
    var toggle = document.querySelector('.menu-toggle');
    var nav    = document.querySelector('.main-navigation');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
            var expanded = nav.classList.contains('open');
            toggle.setAttribute('aria-expanded', expanded);
        });
    }

    // ─── SCROLL ANIMATIONS ──────────────────────────────────────
    var animEls = document.querySelectorAll('[data-animate]');
    if (animEls.length && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            // Agrupa entradas visíveis e aplica stagger só dentro do grupo
            var visible = entries.filter(function(e) { return e.isIntersecting; });
            visible.forEach(function (entry, i) {
                var el = entry.target;
                el.style.transitionDelay = (i * 0.06) + 's';
                el.classList.add('animated');
                observer.unobserve(el);
            });
        }, {
            threshold: 0,
            rootMargin: '0px 0px -40px 0px'
        });

        animEls.forEach(function (el) {
            el.style.transitionDelay = '0s';
            observer.observe(el);
        });
    } else {
        animEls.forEach(function (el) { el.classList.add('animated'); });
    }

    // ─── CONTADOR DE OBRAS ──────────────────────────────────────
    var counters = document.querySelectorAll('[data-counter]');
    if (counters.length && 'IntersectionObserver' in window) {
        var counterObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(function (el) { counterObs.observe(el); });
    }

    function animateCounter(el) {
        var target   = parseInt(el.getAttribute('data-counter'), 10);
        var duration = 2000;
        var start    = performance.now();
        function step(now) {
            var elapsed  = now - start;
            var progress = Math.min(elapsed / duration, 1);
            var ease     = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(ease * target).toLocaleString('pt-BR');
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    // ─── CARROSSEL DE REVIEWS ───────────────────────────────────
    var carousel = document.getElementById('reviews-carousel');
    var dotsWrap = document.getElementById('reviews-dots');
    if (carousel) {
        var cards   = carousel.querySelectorAll('.review-card');
        var prevBtn = document.querySelector('.reviews-prev');
        var nextBtn = document.querySelector('.reviews-next');
        var total   = cards.length;
        var current = 0; // página atual
        var autoTimer;

        var gap = 24;

        function getPerView() {
            return window.innerWidth >= 769 ? 3 : window.innerWidth >= 481 ? 2 : 1;
        }
        function getPages(pv) { return Math.ceil(total / pv); }

        function setCardWidths() {
            var pv    = getPerView();
            var wrapW = carousel.parentElement.offsetWidth; // .reviews-track
            var cardW = Math.floor((wrapW - gap * (pv - 1)) / pv);
            Array.from(cards).forEach(function(c) {
                c.style.width    = cardW + 'px';
                c.style.minWidth = cardW + 'px';
            });
        }

        function buildDots(pages) {
            dotsWrap.innerHTML = '';
            for (var d = 0; d < pages; d++) {
                var dot = document.createElement('button');
                dot.className = 'reviews-dot' + (d === 0 ? ' active' : '');
                dot.setAttribute('aria-label', 'Página ' + (d + 1));
                (function(idx){ dot.addEventListener('click', function(){ goTo(idx); }); })(d);
                dotsWrap.appendChild(dot);
            }
        }

        function goTo(pageIdx) {
            var pv    = getPerView();
            var pages = getPages(pv);
            current   = Math.max(0, Math.min(pageIdx, pages - 1));
            var cardIdx = Math.min(current * pv, total - pv);
            var cardW   = cards[0].offsetWidth + gap;
            carousel.style.transform = 'translateX(-' + (cardIdx * cardW) + 'px)';
            dotsWrap.querySelectorAll('.reviews-dot').forEach(function(d, i) {
                d.classList.toggle('active', i === current);
            });
        }

        function startAuto() {
            var pages = getPages(getPerView());
            autoTimer = setInterval(function(){ goTo(current >= pages - 1 ? 0 : current + 1); }, 5000);
        }
        function stopAuto() { clearInterval(autoTimer); }

        setCardWidths();
        buildDots(getPages(getPerView()));

        if (prevBtn) prevBtn.addEventListener('click', function(){ stopAuto(); goTo(current - 1); startAuto(); });
        if (nextBtn) nextBtn.addEventListener('click', function(){ stopAuto(); goTo(current + 1); startAuto(); });

        carousel.addEventListener('mouseenter', stopAuto);
        carousel.addEventListener('mouseleave', startAuto);

        // Touch swipe
        var touchX = 0;
        carousel.addEventListener('touchstart', function(e){ touchX = e.touches[0].clientX; }, {passive:true});
        carousel.addEventListener('touchend', function(e){
            var diff = touchX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) { stopAuto(); goTo(diff > 0 ? current + 1 : current - 1); startAuto(); }
        }, {passive:true});

        window.addEventListener('resize', function(){
            setCardWidths();
            buildDots(getPages(getPerView()));
            goTo(current);
        });

        startAuto();
    }

    // ─── FILTRO PROJETOS ────────────────────────────────────────
    var filtros  = document.querySelectorAll('.filtro-btn');
    var projetos = document.querySelectorAll('.projeto-card');

    filtros.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var filter = this.getAttribute('data-filter');
            filtros.forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');

            projetos.forEach(function (card) {
                if (filter === '*' || card.getAttribute('data-tipo') === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ─── LIGHTBOX ────────────────────────────────────────────────
    var lbItems = Array.from(document.querySelectorAll('[data-lightbox]'));
    if (lbItems.length) {
        var overlay  = document.createElement('div');
        overlay.className = 'lightbox-overlay';
        overlay.innerHTML =
            '<div class="lightbox-inner">' +
                '<img id="lb-img" src="" alt="">' +
            '</div>' +
            '<button class="lightbox-close" aria-label="Fechar">×</button>' +
            '<button class="lightbox-prev" aria-label="Anterior">‹</button>' +
            '<button class="lightbox-next" aria-label="Próximo">›</button>';
        document.body.appendChild(overlay);

        var lbImg   = overlay.querySelector('#lb-img');
        var lbClose = overlay.querySelector('.lightbox-close');
        var lbPrev  = overlay.querySelector('.lightbox-prev');
        var lbNext  = overlay.querySelector('.lightbox-next');
        var lbCurrent = 0;

        function lbOpen(index) {
            lbCurrent = index;
            lbImg.src = lbItems[index].getAttribute('href');
            lbImg.alt = lbItems[index].querySelector('img') ? lbItems[index].querySelector('img').alt : '';
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function lbClose_() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(function(){ lbImg.src = ''; }, 250);
        }

        lbItems.forEach(function(item, i) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                lbOpen(i);
            });
        });

        lbClose.addEventListener('click', lbClose_);
        lbPrev.addEventListener('click', function() {
            lbOpen((lbCurrent - 1 + lbItems.length) % lbItems.length);
        });
        lbNext.addEventListener('click', function() {
            lbOpen((lbCurrent + 1) % lbItems.length);
        });
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) lbClose_();
        });
        document.addEventListener('keydown', function(e) {
            if (!overlay.classList.contains('active')) return;
            if (e.key === 'Escape')     lbClose_();
            if (e.key === 'ArrowLeft')  lbPrev.click();
            if (e.key === 'ArrowRight') lbNext.click();
        });
    }

    // ─── FORMULÁRIO DE CONTATO ───────────────────────────────────
    var formContato = document.getElementById('form-contato');
    if (formContato) {
        formContato.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn       = document.getElementById('btn-contato');
            var msgEl     = document.getElementById('contato-msg');
            var btnText   = btn.querySelector('.btn-text');
            var btnLoad   = btn.querySelector('.btn-loading');

            btnText.hidden = true;
            btnLoad.hidden = false;
            btn.disabled   = true;

            var data = new FormData(formContato);
            data.append('action', 'tiguen_contato');
            data.append('nonce', tiguenData.contatoNonce);

            fetch(tiguenData.ajaxUrl, { method: 'POST', body: data })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    msgEl.hidden    = false;
                    msgEl.className = 'form-feedback ' + (res.success ? 'success' : 'error');
                    msgEl.textContent = res.data.message;
                    if (res.success) formContato.reset();
                })
                .catch(function () {
                    msgEl.hidden    = false;
                    msgEl.className = 'form-feedback error';
                    msgEl.textContent = 'Erro de conexão. Tente novamente.';
                })
                .finally(function () {
                    btnText.hidden = false;
                    btnLoad.hidden = true;
                    btn.disabled   = false;
                });
        });
    }

    // ─── FORMULÁRIO DE CURRÍCULO ─────────────────────────────────
    var formCurriculo = document.getElementById('form-curriculo');
    if (formCurriculo) {
        formCurriculo.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn     = document.getElementById('btn-curriculo');
            var msgEl   = document.getElementById('curriculo-msg');
            var btnText = btn.querySelector('.btn-text');
            var btnLoad = btn.querySelector('.btn-loading');

            btnText.hidden = true;
            btnLoad.hidden = false;
            btn.disabled   = true;

            var data = new FormData(formCurriculo);
            data.append('action', 'tiguen_curriculo');
            data.append('nonce', tiguenData.curriculoNonce);

            fetch(tiguenData.ajaxUrl, { method: 'POST', body: data })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    msgEl.hidden    = false;
                    msgEl.className = 'form-feedback ' + (res.success ? 'success' : 'error');
                    msgEl.textContent = res.data.message;
                    if (res.success) formCurriculo.reset();
                })
                .catch(function () {
                    msgEl.hidden    = false;
                    msgEl.className = 'form-feedback error';
                    msgEl.textContent = 'Erro de conexão. Tente novamente.';
                })
                .finally(function () {
                    btnText.hidden = false;
                    btnLoad.hidden = true;
                    btn.disabled   = false;
                });
        });
    }

});
