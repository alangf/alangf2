<?php

// Setup.

require "lang.php";

$config = array(
	'colorPrimary' => '#000',
	'colorSecondary' => '#fff'
);

function enqueue_scripts_styles() {
	// scripts.
	wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main.js', array(), '', true);
	wp_localize_script('main', 'config', array( 
		'ajaxUrl' => admin_url( 'admin-ajax.php'),
		'baseUrl' => (strpos($_SERVER['SERVER_NAME'], 'alangf.com')) ? '/' : '/alangf/',
		'lang' => qtrans_getLanguage(),
		'cache' => array(),
		'siteTitle' => get_bloginfo('name')
	));

	// styles.
	wp_enqueue_style('main', get_current_template_uri() . '/assets/css/main.css');
	wp_enqueue_style('font_opensans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,400i|Space+Mono:400,700' );
}
add_action('wp_enqueue_scripts', 'enqueue_scripts_styles');

function after_setup() {
	// menu support.
    add_theme_support('menus');

    // featured image support.
    add_theme_support('post-thumbnails');

    // thumb post size
    add_image_size('midthumb', 550, 400, true); 

	// menus.
	register_nav_menus(array(
		'header' => 'Header'
	));
}
add_action('after_setup_theme', 'after_setup');


// Helpers.

function get_current_template_uri() {
	$uri = get_template_directory_uri();
	return (strpos($uri, "localhost") == 0) ? "http://localhost:3000" : $uri;
}

function get_part($template = "") {
	$base = 'templates/';

	if ($template !== "") {
		get_template_part($base . $template);
		return true;
	}

	if (is_home()) {
		$template = "home";
	}
	if (is_category()) {
		$template = "category";
	}
	if (is_single()) {
		$template = "single";
	}
	if (is_page()) {
		$template = "page";
	}
	if (is_404()) {
		$template = "404";
	}
	if ($template !== "") {
		get_template_part($base . $template);
		return true;
	}
	return false;
}

function _l($key, $echo = true) {
	global $trad;

	$lang = qtrans_getLanguage();
	if ($echo) {
		echo $trad[$key][$lang];
	}
	return $trad[$key][$lang];
}

function featured($post_id, $size = 'full') {
    $featured = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), $size );
    return $featured[0];
}

function get_project_entry($project) {
	$images = get_field('gallery', $project->ID);
	if (is_array($images) && count($images) > 0) {
		$images = array_map(function ($image) {
					return $image['url'];
				}, $images);
	} else {
		$images = array();
	}
	return array(
		'title' => qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($project->post_title),
		'path' => '/projects/' . $project->post_name,
		'imageSrc' => featured($project->ID, 'midthumb'),
		'colorPrimary' => (get_field('color_primary', $project->ID)) ? get_field('color_primary', $project->ID) : $config['colorPrimary'],
		'colorSecondary' => (get_field('color_secondary', $project->ID)) ? get_field('color_secondary', $project->ID) : $config['colorSecondary'],
		'description' => apply_filters('the_content', $project->post_content),
		'url' => get_field('url', $project->ID),
		'online' => get_field('online', $project->ID),
		'images' => $images
	); 
}

function get_json_projects($posts, $jsonify = true) {
	$projects = array_map(function ($project) {
		return get_project_entry($project);
	}, $posts);
	return ($jsonify) ? json_encode($projects) : $projects;
}

// Ajax functions.

require('ajax.php');

