<?php
/**
 * Plugin Name: Reviews Coverflow Slider
 * Description: Custom post type "Reviews" with a Swiper.js coverflow slider shortcode.
 * Version: 1.0
 * Author: Mahim Zaman
 * Plugin URI: https://condixia.com
 * Author URI: https://mahimzaman.com
 * Text Domain: reviews-coverflow
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Register Custom Post Type
function rcs_register_reviews_post_type() {
    register_post_type( 'reviews', [
        'labels' => [
            'name' => __( 'Reviews', 'reviews-coverflow' ),
            'singular_name' => __( 'Review', 'reviews-coverflow' ),
        ],
        'public' => true,
        'supports' => [ 'title', 'thumbnail' ],
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-gallery',
    ] );
}
add_action( 'init', 'rcs_register_reviews_post_type' );

// Enqueue SwiperJS
function rcs_enqueue_assets() {
    wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css' );
    wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true );

    wp_enqueue_style( 'rcs-style', plugin_dir_url( __FILE__ ) . 'assets/style.css' );
    wp_enqueue_script( 'rcs-init', plugin_dir_url( __FILE__ ) . 'assets/slider.js', [ 'swiper-js' ], null, true );
}
add_action( 'wp_enqueue_scripts', 'rcs_enqueue_assets' );

// Shortcode to Display Reviews Slider
function rcs_reviews_carousel_shortcode() {
    $args = [
        'post_type' => 'reviews',
        'posts_per_page' => -1,
    ];
    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) : ?>
        <div class="swiper reviews-swiper">
            <div class="swiper-wrapper">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="swiper-slide">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium_large' ); ?>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    <?php endif;
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'reviews_carousel', 'rcs_reviews_carousel_shortcode' );
