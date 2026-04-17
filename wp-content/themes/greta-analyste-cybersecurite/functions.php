<?php
defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/catalog.php';

function gac_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'greta-analyste-cybersecurite' ),
		)
	);
}
add_action( 'after_setup_theme', 'gac_theme_setup' );

function gac_theme_enqueue_assets() {
	wp_enqueue_style( 'gac-theme-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	wp_enqueue_script( 'gac-tailwind', 'https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio', array(), null, false );
	wp_add_inline_script(
		'gac-tailwind',
		'tailwind.config = { theme: { extend: { fontFamily: { sans: ["Inter", "ui-sans-serif", "system-ui"] }, colors: { gac: { bg: "#0e1116", surface: "#141922", surfaceSoft: "#1a2130", accent: "#31c48d", warm: "#f59e0b", rose: "#fb7185", mist: "#98a3b3" } } } } };',
		'before'
	);

	wp_enqueue_script( 'gac-alpine', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', array(), null, true );
	wp_script_add_data( 'gac-alpine', 'defer', true );

	wp_enqueue_script( 'gac-theme-js', get_template_directory_uri() . '/assets/theme.js', array(), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'gac_theme_enqueue_assets' );
