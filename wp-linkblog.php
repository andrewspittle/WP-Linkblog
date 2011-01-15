<?php
/**
 * @package WP_Linkblog
 * @version 0.1
 */
/*
Plugin Name: WP Linkblog
Plugin URI: http://andrewspittle.net/projects/wp-linkblog
Description: Filters the permalinks to look for a custom field. The custom field becomes the destination permalink ala Daring Fireball's linked list.
Author: Andrew Spittle
Version: 0.1
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
add_filter('post_link', 'wplinkblog_permalink')
?>