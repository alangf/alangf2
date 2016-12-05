<?php

// cargar category

add_action('wp_ajax_category', 'ajax_category');
add_action('wp_ajax_nopriv_category', 'ajax_category');

function ajax_category() {

	global $config;

	$projects = get_json_projects(get_posts(array(
		'category_name' => $_GET['category'],
		'posts_per_page' => 8
	)), false);
	$category = get_category_by_path($_GET['category']);

	echo json_encode(array(
		'projects' => $projects,
		'title' => qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($category->name)
	));


	die();
}

// cargar proyecto

add_action('wp_ajax_project', 'ajax_project');
add_action('wp_ajax_nopriv_project', 'ajax_project');

function ajax_project() {

	global $config;

	echo get_json_projects(get_posts(array(
		'name' => $_GET['slug']
	)));

	die();

}

// cargar proyecto

add_action('wp_ajax_page', 'ajax_page');
add_action('wp_ajax_nopriv_page', 'ajax_page');

function ajax_page() {

	global $config;

	echo get_json_projects(get_posts(array(
		'post_type' => 'page',
		'name' => $_GET['slug']
	)));

	die();

}