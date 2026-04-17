<?php
/**
 * Catalog helpers for GRETA learning platform.
 */

defined( 'ABSPATH' ) || exit;

function gac_catalog_root_path() {
	return dirname( __DIR__, 4 );
}

function gac_catalog_inventory_path() {
	return gac_catalog_root_path() . '/project-data/inventory.json';
}

function gac_catalog_get_data() {
	static $catalog = null;

	if ( null !== $catalog ) {
		return $catalog;
	}

	$path = gac_catalog_inventory_path();

	if ( ! file_exists( $path ) ) {
		$catalog = array();
		return $catalog;
	}

	$raw = file_get_contents( $path );
	$decoded = json_decode( $raw, true );
	$catalog = is_array( $decoded ) ? $decoded : array();

	return $catalog;
}

function gac_catalog_get_paths() {
	$catalog = gac_catalog_get_data();
	return isset( $catalog['paths'] ) ? $catalog['paths'] : array();
}

function gac_catalog_get_resources() {
	$catalog = gac_catalog_get_data();
	return isset( $catalog['resources'] ) ? $catalog['resources'] : array();
}

function gac_catalog_find_path( $slug ) {
	foreach ( gac_catalog_get_paths() as $path ) {
		if ( isset( $path['slug'] ) && $slug === $path['slug'] ) {
			return $path;
		}
	}

	return null;
}

function gac_catalog_find_resource( $slug ) {
	foreach ( gac_catalog_get_resources() as $resource ) {
		if ( isset( $resource['slug'] ) && $slug === $resource['slug'] ) {
			return $resource;
		}
	}

	return null;
}

function gac_catalog_module_slug( $path_slug, $module_index, $module_title ) {
	return sanitize_title( $path_slug . ' module ' . ( $module_index + 1 ) . ' ' . $module_title );
}

function gac_catalog_lesson_slug( $path_slug, $module_index, $lesson_index, $lesson_title ) {
	return sanitize_title( $path_slug . ' lesson ' . ( $module_index + 1 ) . ' ' . ( $lesson_index + 1 ) . ' ' . $lesson_title );
}
