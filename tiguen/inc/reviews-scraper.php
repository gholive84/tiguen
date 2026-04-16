<?php
/**
 * Tiguen — Scraper de Avaliações do Google
 *
 * Busca avaliações via Google Search e salva no WP transient/option.
 * Acionar via: Admin > Ferramentas > Avaliações Google
 */

// ─── Admin page ────────────────────────────────────────────────────────────
add_action( 'admin_menu', function () {
    add_management_page(
        'Avaliações Google',
        'Avaliações Google',
        'manage_options',
        'tiguen-reviews',
        'tiguen_reviews_admin_page'
    );
} );

function tiguen_reviews_admin_page() {
    $message = '';

    if ( isset( $_POST['tiguen_scrape'] ) && check_admin_referer( 'tiguen_scrape_reviews' ) ) {
        $data = tiguen_fetch_google_reviews();
        if ( $data && ! empty( $data['reviews'] ) ) {
            update_option( 'tiguen_google_reviews', $data, false );
            $message = '<div class="notice notice-success"><p>✅ ' . count( $data['reviews'] ) . ' avaliações salvas com sucesso.</p></div>';
        } else {
            $message = '<div class="notice notice-warning"><p>⚠️ Não foi possível extrair avaliações. O cache anterior foi mantido.</p></div>';
        }
    }

    $saved = get_option( 'tiguen_google_reviews', [] );
    ?>
    <div class="wrap">
        <h1>Avaliações Google — Tiguen</h1>
        <?php echo $message; ?>
        <p>Clique no botão para buscar as avaliações mais recentes do Google e atualizar o carrossel da homepage.</p>
        <form method="post">
            <?php wp_nonce_field( 'tiguen_scrape_reviews' ); ?>
            <input type="hidden" name="tiguen_scrape" value="1">
            <?php submit_button( 'Atualizar avaliações agora', 'primary' ); ?>
        </form>

        <?php if ( ! empty( $saved['reviews'] ) ) : ?>
            <h2>Avaliações salvas (<?php echo count( $saved['reviews'] ); ?>)</h2>
            <p style="color:#666">Última atualização: <?php echo esc_html( $saved['last_updated'] ?? '—' ); ?></p>
            <?php if ( $saved['rating'] ) : ?>
                <p><strong>Nota geral:</strong> <?php echo number_format( (float) $saved['rating'], 1 ); ?> / 5,0
                <?php if ( $saved['total_ratings'] ) echo '(' . $saved['total_ratings'] . ' avaliações)'; ?></p>
            <?php endif; ?>
            <table class="widefat striped" style="margin-top:12px">
                <thead><tr><th>Autor</th><th>Nota</th><th>Texto</th></tr></thead>
                <tbody>
                    <?php foreach ( $saved['reviews'] as $r ) : ?>
                    <tr>
                        <td><?php echo esc_html( $r['author'] ); ?></td>
                        <td><?php echo esc_html( $r['rating'] ); ?> ★</td>
                        <td><?php echo esc_html( mb_substr( $r['text'], 0, 120 ) ); ?>…</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><em>Nenhuma avaliação salva ainda. Clique em "Atualizar avaliações agora".</em></p>
        <?php endif; ?>
    </div>
    <?php
}

// ─── Scraper ───────────────────────────────────────────────────────────────
function tiguen_fetch_google_reviews(): ?array {
    $search_query = urlencode( 'Tiguen Construtora São José dos Pinhais' );
    $url          = "https://www.google.com/search?q={$search_query}&hl=pt-BR&gl=br&num=10";

    $ch = curl_init( $url );
    curl_setopt_array( $ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
        CURLOPT_HTTPHEADER     => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
            'Accept-Encoding: identity',
        ],
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_COOKIEJAR      => sys_get_temp_dir() . '/tiguen_cookies.txt',
        CURLOPT_COOKIEFILE     => sys_get_temp_dir() . '/tiguen_cookies.txt',
    ] );

    $html      = curl_exec( $ch );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    curl_close( $ch );

    if ( ! $html || $http_code !== 200 ) {
        return null;
    }

    $reviews = [];
    $rating  = null;
    $total   = null;

    // ── Extrai JSON-LD (structured data do Knowledge Panel) ──
    preg_match_all( '/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $ld_matches );

    foreach ( $ld_matches[1] as $raw ) {
        $data = json_decode( html_entity_decode( $raw, ENT_QUOTES | ENT_HTML5, 'UTF-8' ), true );
        if ( ! $data ) continue;

        $items = isset( $data['@graph'] ) ? $data['@graph'] : [ $data ];
        foreach ( $items as $item ) {
            // Rating agregado
            if ( isset( $item['aggregateRating'] ) ) {
                $ar     = $item['aggregateRating'];
                $rating = $ar['ratingValue'] ?? $rating;
                $total  = $ar['reviewCount'] ?? $ar['ratingCount'] ?? $total;
            }
            // Reviews individuais
            if ( ! empty( $item['review'] ) ) {
                foreach ( (array) $item['review'] as $r ) {
                    $author_name = '';
                    if ( is_array( $r['author'] ?? null ) ) {
                        $author_name = $r['author']['name'] ?? '';
                    } elseif ( is_string( $r['author'] ?? null ) ) {
                        $author_name = $r['author'];
                    }

                    $text = $r['reviewBody'] ?? $r['description'] ?? '';
                    if ( ! $text ) continue;

                    $reviews[] = [
                        'author' => $author_name ?: 'Cliente',
                        'avatar' => strtoupper( mb_substr( $author_name ?: 'C', 0, 1 ) ),
                        'rating' => (int) ( $r['reviewRating']['ratingValue'] ?? 5 ),
                        'text'   => $text,
                        'date'   => $r['datePublished'] ?? '',
                    ];
                }
            }
        }
    }

    // ── Fallback: snippets do Knowledge Panel no HTML ──
    if ( empty( $reviews ) ) {
        preg_match_all( '/"([^"]{40,500})"\s*—\s*([A-ZÀ-ÿ][^\n"]{2,40})/u', $html, $snippet_matches );
        foreach ( $snippet_matches[1] as $i => $text ) {
            $author    = trim( $snippet_matches[2][ $i ] ?? 'Cliente' );
            $reviews[] = [
                'author' => $author,
                'avatar' => strtoupper( mb_substr( $author, 0, 1 ) ),
                'rating' => 5,
                'text'   => $text,
                'date'   => '',
            ];
        }
    }

    // Remove duplicatas pelo texto
    $seen   = [];
    $unique = [];
    foreach ( $reviews as $r ) {
        $key = md5( $r['text'] );
        if ( ! isset( $seen[ $key ] ) ) {
            $seen[ $key ] = true;
            $unique[]     = $r;
        }
    }

    // Limita a 9 avaliações
    $unique = array_slice( $unique, 0, 9 );

    return [
        'place_name'    => 'Tiguen Construtora',
        'rating'        => $rating ? (float) $rating : null,
        'total_ratings' => $total  ? (int)   $total  : null,
        'last_updated'  => gmdate( 'c' ),
        'source'        => 'Google',
        'reviews'       => $unique,
    ];
}
