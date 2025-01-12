<?php
/*
Plugin Name: Spiral Text
Description: This WordPress plugin dynamically animates text into a captivating spiral form, with customizable options for text content, color, height, and animation speed directly from the admin panel.
Version: 1.0
Author: Berger Maximilian
*/

defined('ABSPATH') or die('No script kiddies please!');

function load_scripts() {
    $version = time(); // Uses the current time as version number to prevent caching
    wp_enqueue_style('animation-plugin-css', plugins_url('style.css', __FILE__), array(), $version);
    wp_enqueue_script('animation-plugin-js', plugins_url('app.js', __FILE__), array('jquery'), $version, true);
}

add_action('wp_enqueue_scripts', 'load_scripts');

function shortcode() {
    $text = get_option('animation_plugin_text', 'Default text');
    $color = get_option('animation_plugin_text_color', '#000000');
    $spiral_height = get_option('animation_plugin_spiral_height', '100');
    $animation_duration = get_option('animation_plugin_animation_duration', '5');
    ob_start();
    ?>
    <div class="main" style="--spiral-height: <?php echo esc_attr($spiral_height); ?>px; height: calc(<?php echo esc_attr($spiral_height); ?>px * 2 + 1em); color: <?php echo esc_attr($color); ?>; --animation-duration: <?php echo esc_attr($animation_duration); ?>s;">
        <span class="animated-span-2"><?php echo esc_html($text); ?></span>
        <span class="animated-span"><?php echo esc_html($text); ?></span>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('spiralstring', 'shortcode');

function settings() {
    add_option('animation_plugin_text', 'Default text');
    add_option('animation_plugin_text_color', '#000000');
    add_option('animation_plugin_spiral_height', '100');
    add_option('animation_plugin_animation_duration', '5'); // Default duration for the animation
    register_setting('animation_plugin_options', 'animation_plugin_text');
    register_setting('animation_plugin_options', 'animation_plugin_text_color');
    register_setting('animation_plugin_options', 'animation_plugin_spiral_height');
    register_setting('animation_plugin_options', 'animation_plugin_animation_duration');
}

add_action('admin_init', 'settings');

function settings_page() {
    add_menu_page('Animation Plugin Settings', 'Spiral Text Settings', 'manage_options', 'animation_plugin', 'settings_page_markup', 'dashicons-admin-generic', 100);
}

function settings_page_markup() {
    ?>
    <div class="wrap">
        <h1>Spiral Text Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('animation_plugin_options');
            do_settings_sections('animation_plugin_options');
            ?>
            <label for="animation_plugin_text">Text for the Spiral:</label>
            <input type="text" id="animation_plugin_text" name="animation_plugin_text" value="<?php echo esc_attr(get_option('animation_plugin_text')); ?>"><br>
            <label for="animation_plugin_text_color">Text Color:</label>
            <input type="color" id="animation_plugin_text_color" name="animation_plugin_text_color" value="<?php echo esc_attr(get_option('animation_plugin_text_color')); ?>"><br>
            <label for="animation_plugin_spiral_height">Spiral Height (in px):</label>
            <input type="number" id="animation_plugin_spiral_height" name="animation_plugin_spiral_height" value="<?php echo esc_attr(get_option('animation_plugin_spiral_height')); ?>"><br>
            <label for="animation_plugin_animation_duration">Animation Duration (in seconds):</label>
            <input type="number" id="animation_plugin_animation_duration" name="animation_plugin_animation_duration" value="<?php echo esc_attr(get_option('animation_plugin_animation_duration')); ?>" step="0.1"><br>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'settings_page');
