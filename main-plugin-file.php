<?php
/**
 * Plugin Name: My Custom Block Plugin
 * Plugin URI: https://superdoula.co.uk
 * Description: A plugin to create a custom Gutenberg block for downloading files.
 * Version: 1.0.0
 * Author: Daz
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
              'default' => 'default - not collected',
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

// function my_custom_block_render_callback($attributes, $content) {
//     // Output the saved content directly. This should include the PDF link.
//     // Get the PDF URL and thumbnail URL from attributes
//     $text = isset($attributes['content']) ? $attributes['content'] : '';
//     $pdfUrl = isset($attributes['pdfUrl']) ? $attributes['pdfUrl'] : '';
//     $customText = isset($attributes['customText']) ? $attributes['customText'] : '';
//     $thumbnailUrl = isset($attributes['thumbnailUrl']) ? $attributes['thumbnailUrl'] : '';
//     $output = 'text: ' . $text . '<br/>'; 
//     $output .= 'pdfUrl: ' . $pdfUrl . '<br/>'; 
//     $output .= 'customText: ' . $customText . '<br/>'; 
//     $output .= 'thumbnailUrl: ' . $thumbnailUrl . '<br/>'; 

//     return $output;

// }
function my_custom_block_render_callback($attributes, $content) {
    // Output the saved content directly. This should include the PDF link.
    // Get the PDF URL and thumbnail URL from attributes

    if (is_user_logged_in()) return $content;

    $text = isset($attributes['content']) ? $attributes['content'] : '';
    $pdfUrl = isset($attributes['pdfUrl']) ? $attributes['pdfUrl'] : '';
    $customText = isset($attributes['customText']) ? $attributes['customText'] : '';
    $thumb = isset($attributes['thumbnailUrl']) ? $attributes['thumbnailUrl'] : '';
    $my_account_url = wc_get_page_permalink('myaccount');

    // $file = $thumb = '';
    $output = '<div class="wp-block-my-plugin-my-custom-block user-download-block">';
    $output .= '<h5 class="file-title">'.$text.'</h5>';
    $output .= '<p class="additional-text">'.$customText.'</p>';
    $output .= '<a class="download-link" href="'.$my_account_url.'" >';
    $output .= '<p>Please <b>Register</b> to download this content.</p>';
    $output .= '<img decoding="async" src="'.$thumb.'" alt="PDF Thumbnail frontend">';
    $output .= '<a/></div>';

    return $output;
}