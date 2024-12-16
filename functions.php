<?php
/**
 * Theme functions and definitions
 *
 * @package Element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function riga_hide_admin_bar(){
  return false;
}
add_filter( 'show_admin_bar' , 'riga_hide_admin_bar' );

/**
 * Loading All CSS Stylesheets and Javascript Files.
 *
 * @since v1.0
 *
 * @return void
 */
function jq_scripts_loader() {
	// 2. Scripts.
  wp_enqueue_script( 'jQuery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'jq_scripts_loader' );

function update_user_acf_field() {
    if (!isset($_POST['price']) || !isset($_POST['account'])) {
      wp_send_json_error('Invalid data');
    }

    $user_id = intval($_POST['account']);
    $price = intval($_POST['price']);
    $balance = get_field('balance', 'user_' . $user_id);
    $total = $price + $balance;
    update_field('balance', $total, 'user_' . $user_id);

    $current_user = wp_get_current_user();
    $current_balance = get_field('balance', 'user_' . $current_user->ID);
    $sum = $current_balance - $price;

    update_field('balance', $sum, 'user_' . $current_user->ID);

    $new_post = array(
      'post_title'   => 'تحويل', // Valid post name
      'post_content' => $price, // Unslashed post data - Set the content of the new post
      'post_status'  => 'publish', // Unslashed post data - Set the status of the new post to 'publish'
      'post_author'  => $current_user->ID, // Replace with the desired author's user ID
    );

    // Insert post into the database
    $post_id = wp_insert_post($new_post, true); // Use $wp_error set to true for error handling
    // Check if there was an error during post insertion
    if (is_wp_error($post_id)) {
        // Error occurred while inserting the post
        echo "Error: " . $post_id->get_error_message();
    } else {
        // The post was successfully inserted, and $post_id contains the post ID
        echo "Post inserted successfully. New Post ID: " . $post_id;
    }

    wp_send_json_success('updated successfully');
}
add_action('wp_ajax_update_user_acf_field', 'update_user_acf_field');
add_action('wp_ajax_nopriv_update_user_acf_field', 'update_user_acf_field');

function update_user_acf_field_add() {
    if (!isset($_POST['price'])) {
      wp_send_json_error('Invalid data');
    }
    $price = intval($_POST['price']);
    $current_user = wp_get_current_user();
    $current_balance = get_field('balance', 'user_' . $current_user->ID);
    $sum = $current_balance + $price;
    update_field('balance', $sum, 'user_' . $current_user->ID);

    $new_post = array(
      'post_title'   => 'ايداع', // Valid post name
      'post_content' => $price, // Unslashed post data - Set the content of the new post
      'post_status'  => 'publish', // Unslashed post data - Set the status of the new post to 'publish'
      'post_author'  => $current_user->ID, // Replace with the desired author's user ID
    );

    // Insert post into the database
    $post_id = wp_insert_post($new_post, true); // Use $wp_error set to true for error handling
    // Check if there was an error during post insertion
    if (is_wp_error($post_id)) {
        // Error occurred while inserting the post
        echo "Error: " . $post_id->get_error_message();
    } else {
        // The post was successfully inserted, and $post_id contains the post ID
        echo "Post inserted successfully. New Post ID: " . $post_id;
    }    
    wp_send_json_success('updated successfully');
}
add_action('wp_ajax_update_user_acf_field_add', 'update_user_acf_field_add');
add_action('wp_ajax_nopriv_update_user_acf_field_add', 'update_user_acf_field_add');