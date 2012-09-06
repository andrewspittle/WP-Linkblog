<?php
/**
 * @package WP_Linkblog
 * @version 0.2
 */
/*
Plugin Name: WP Linkblog
Plugin URI: http://andrewspittle.net/projects/
Description: Filters the permalinks to look for a custom field. The custom field becomes the destination permalink ala Daring Fireball's linked list.
Author: Andrew Spittle
Version: 0.2
Author URI: http://andrewspittle.net/
*/

function wplinkblog_permalink($permalink) {
	global $wp_query;
	if($url = get_post_meta($wp_query->post->ID, 'linkblog_url', true)) {
		return $url;
	}
	return $permalink;
}
add_filter('the_permalink_rss', 'wplinkblog_permalink');
add_filter('post_link', 'wplinkblog_permalink');


// Add persistent metadata box
add_action( 'add_meta_boxes', 'linkblog_add_meta_box' );
add_action( 'save_post', 'linkblog_save_post' );

function linkblog_add_meta_box() {

    add_meta_box( 
        'linkblog',
        'WP_Linkblog external link',
        'linkblog_meta_box',
        'post',
        'normal',
        'high'
    );

}

function linkblog_meta_box( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'linkblog_noncename' );

    $linkblog_url = get_post_meta($post->ID,'linkblog_url',true);

	echo '<p><label for="linkblog_url">Link: </label><input type="text" id="linkblog_url" name="linkblog_url" size="70" value="' . $linkblog_url . '" /></p>';
}

function linkblog_save_post( $post_id ) {

	// Ignore if doing an autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

	// verify data came from the linkblog meta box
	if ( !wp_verify_nonce( $_POST['linkblog_noncename'], plugin_basename( __FILE__ ) ) )
		return;      

	// Check user permissions
	if ( 'post' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;
	}

	$linkblog_data = $_POST['linkblog_url'];
	update_post_meta($post_id, 'linkblog_url', $linkblog_data);

}

?>
