<?php
/**
 * Plugin Name: My Custom Block
 */
function my_custom_block_enqueue() {
    wp_enqueue_script(
        'my-custom-block',
        plugins_url( 'build/index.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-media-utils' ), // Add wp-media-utils if not already included
        filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )
    );
}

add_action( 'enqueue_block_editor_assets', 'my_custom_block_enqueue' );

function my_custom_block_enqueue_assets() {
    wp_enqueue_style(
        'my-custom-block-style',
        plugins_url('build/style.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'build/style.css')
    );
}

add_action('wp_enqueue_scripts', 'my_custom_block_enqueue_assets');

function my_custom_block_enqueue_editor_assets() {
    wp_enqueue_style(
        'my-custom-block-editor-style',
        plugins_url('build/editor.css', __FILE__),
        array('wp-edit-blocks'),
        filemtime(plugin_dir_path(__FILE__) . 'build/editor.css')
    );
}

add_action('enqueue_block_editor_assets', 'my_custom_block_enqueue_editor_assets');

// Register block server-side to use render callback
function my_custom_block_register() {
  register_block_type('my-plugin/my-custom-block', array(
      'attributes' => array(
          'content' => array(
              'type' => 'string',
              'default' => '',
          ),
          'pdfUrl' => array(
              'type' => 'string',
              'default' => '',
          ),
          'thumbnailUrl' => array(
              'type' => 'string',
              'default' => '',
          ),
          // Add other attributes here
      ),
      'render_callback' => 'my_custom_block_render_callback',
));
}

add_action( 'init', 'my_custom_block_register' );

function my_custom_block_render_callback($attributes, $content) {
    // Output the saved content directly. This should include the PDF link.
    // Get the PDF URL and thumbnail URL from attributes
    $text = isset($attributes['content']) ? $attributes['content'] : '';
    $pdfUrl = isset($attributes['pdfUrl']) ? $attributes['pdfUrl'] : '';
    $customText = isset($attributes['customText']) ? $attributes['customText'] : '';
    $thumbnailUrl = isset($attributes['thumbnailUrl']) ? $attributes['thumbnailUrl'] : '';
    $file = $thumb = '';

    // Content structure for both logged in and logged out users
    $output = '<div class="user-download__block ' . (is_user_logged_in() ? 'logged-in-content' : 'logged-out-content') . '">';
    // $output .= '<p class="custom-text">' . esc_html($attributes['customText']) . '</p>';

    // Show thumbnail for all users
    if ($thumbnailUrl) {
        $thumb .= '<img src="' . esc_url($thumbnailUrl) . '" alt="PDF Thumbnail">';
    }

    if (is_user_logged_in()) {
        if ($pdfUrl) {
                $output .= '<a href="' . esc_url($pdfUrl) . '" class="user-download__a" target="_blank">Download Now'.$thumb.'</a>';
        }
    } else {
        if ($pdfUrl) {
                $url = 'https://superdoula.local/my-account/';
                $output .= 'Please <a href="'.$url.'" ><b>login</b></a> to download this content.';
                $output .= '<a href="'.$url.'" class="user-download__a">' . $thumb . '</a>';
        }

    }
    $output .= '<p class="custom-text">' . esc_html($customText) . '</p>';

    $output .= '</div>';

    return $output;
}