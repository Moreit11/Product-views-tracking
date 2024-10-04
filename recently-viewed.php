<?php
/**
 * Plugin Name: Complianz Recently Viewed Products
 * Description: A custom plugin to track recently viewed products with Complianz GDPR integration.
 * Version: 1.0
 * Author: Your Name
 */

// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue the JavaScript tracking file
function crvp_enqueue_scripts() {
    wp_enqueue_script(
        'crvp-tracking-script',
        plugin_dir_url( __FILE__ ) . 'js/recently-viewed-products.js', // Path to JS file
        array('jquery'),
        null,
        true
    );

    // Localize WooCommerce product data for product pages
    if (is_product()) {
        global $post;
        wp_localize_script('crvp-tracking-script', 'myProduct', array(
            'productId' => intval($post->ID), // Sanitize product ID
        ));
    }
}
add_action('wp_enqueue_scripts', 'crvp_enqueue_scripts');

// Add shortcode to display recently viewed products
function crvp_display_recently_viewed_products() {
    if (hasConsentForStatisticsOrMarketing()) {
        $viewedProducts = json_decode(stripslashes($_COOKIE['recently_viewed_products']), true);

        if (!empty($viewedProducts)) {
            // Sanitize product IDs
            $viewedProductsEscaped = array_map('intval', $viewedProducts);
            
            $productQuery = new WP_Query(array(
                'post_type' => 'product',
                'post__in' => $viewedProductsEscaped,
                'orderby' => 'post__in', // Keep order as per viewing
            ));

            if ($productQuery->have_posts()) {
                echo '<ul class="recently-viewed-products">';
                while ($productQuery->have_posts()) {
                    $productQuery->the_post();
                    wc_get_template_part('content', 'product'); // Display product using WooCommerce template
                }
                echo '</ul>';
                wp_reset_postdata();
            } else {
                echo 'No recently viewed products available.';
            }
        }
    } else {
        echo 'Consent required to display recently viewed products.';
    }
}
add_shortcode('recently_viewed_products', 'crvp_display_recently_viewed_products');

// Check for statistics or marketing consent
function hasConsentForStatisticsOrMarketing() {
    $statisticsConsent = isset($_COOKIE['cmplz_statistics']) ? $_COOKIE['cmplz_statistics'] : '';
    $marketingConsent = isset($_COOKIE['cmplz_marketing']) ? $_COOKIE['cmplz_marketing'] : '';

    return ($statisticsConsent === 'allow' || $marketingConsent === 'allow');
}

