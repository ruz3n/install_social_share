<?php
/**
 * Plugin Name: Instant Social Share
 * Plugin URI: https://example.com/plugins/install-social-share/
 * Description: A simple plugin to add social sharing buttons to your WordPress posts.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: install-social-share
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('ISS_VERSION', '1.0.0');
define('ISS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ISS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Enqueue plugin styles and scripts
 */
function iss_enqueue_styles() {
    // Enqueue Font Awesome for icons
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
    
    // Enqueue plugin styles
    wp_enqueue_style('iss-styles', ISS_PLUGIN_URL . 'assets/css/install-social-share.css', array('font-awesome'), ISS_VERSION);
}
add_action('wp_enqueue_scripts', 'iss_enqueue_styles');

/**
 * Add social sharing buttons to the content
 */
function iss_add_social_buttons($content) {
    // Only add to single posts
    if (!is_singular('post')) {
        return $content;
    }

    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());

    $buttons = '<div class="iss-social-share">';
    $buttons .= '<h4>' . esc_html__('Share this post:', 'install-social-share') . '</h4>';

    // Facebook
    $buttons .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" class="iss-facebook">';
    $buttons .= '<i class="fab fa-facebook-f"></i> <span>' . esc_html__('Facebook', 'install-social-share') . '</span>';
    $buttons .= '</a>';

    // Twitter
    $buttons .= '<a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" class="iss-twitter">';
    $buttons .= '<i class="fab fa-twitter"></i> <span>' . esc_html__('Twitter', 'install-social-share') . '</span>';
    $buttons .= '</a>';

    // LinkedIn
    $buttons .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '" target="_blank" class="iss-linkedin">';
    $buttons .= '<i class="fab fa-linkedin-in"></i> <span>' . esc_html__('LinkedIn', 'install-social-share') . '</span>';
    $buttons .= '</a>';

    $buttons .= '</div>';

    // Append to content
    return $content . $buttons;
}
add_filter('the_content', 'iss_add_social_buttons');

/**
 * Register the settings page
 */
function iss_add_settings_page() {
    add_options_page(
        __('Social Share Settings', 'install-social-share'),
        __('Social Share', 'install-social-share'),
        'manage_options',
        'install-social-share',
        'iss_render_settings_page'
    );
}
add_action('admin_menu', 'iss_add_settings_page');

/**
 * Render the settings page
 */
function iss_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php esc_html_e('Configure your social sharing options.', 'install-social-share'); ?></p>
        <form action="options.php" method="post">
            <?php
            // Output security fields
            settings_fields('install-social-share');
            // Output setting sections
            do_settings_sections('install-social-share');
            // Submit button
            submit_button();
            ?>
        </form>
    </div>
    <?php
}