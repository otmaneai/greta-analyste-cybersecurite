<?php
defined( 'ABSPATH' ) || exit;

function gac_theme_inventory() {
	static $inventory = null;

	if ( null !== $inventory ) {
		return $inventory;
	}

	if ( function_exists( 'gac_catalog_get_data' ) ) {
		$inventory = gac_catalog_get_data();
		return $inventory;
	}

	$path = dirname( __DIR__, 4 ) . '/project-data/inventory.json';

	if ( ! file_exists( $path ) ) {
		$inventory = array();
		return $inventory;
	}

	$raw = file_get_contents( $path );
	$decoded = json_decode( $raw, true );
	$inventory = is_array( $decoded ) ? $decoded : array();

	return $inventory;
}

function gac_theme_get_metrics() {
	$inventory = gac_theme_inventory();
	return isset( $inventory['metrics'] ) ? $inventory['metrics'] : array();
}

function gac_theme_get_stack() {
	$inventory = gac_theme_inventory();
	return isset( $inventory['stack'] ) ? $inventory['stack'] : array();
}

function gac_theme_get_paths() {
	$inventory = gac_theme_inventory();
	return isset( $inventory['paths'] ) ? $inventory['paths'] : array();
}

function gac_theme_get_resources() {
	$inventory = gac_theme_inventory();
	return isset( $inventory['resources'] ) ? $inventory['resources'] : array();
}

function gac_theme_status_label( $status ) {
	return ucwords( str_replace( '-', ' ', (string) $status ) );
}

function gac_theme_get_path_by_slug( $slug ) {
	foreach ( gac_theme_get_paths() as $path ) {
		if ( isset( $path['slug'] ) && $slug === $path['slug'] ) {
			return $path;
		}
	}

	return null;
}

function gac_theme_get_resources_by_slugs( $slugs ) {
	$resources = array();

	foreach ( gac_theme_get_resources() as $resource ) {
		if ( in_array( $resource['slug'], $slugs, true ) ) {
			$resources[] = $resource;
		}
	}

	return $resources;
}

function gac_theme_count_lessons_in_path( $path ) {
	$count = 0;

	if ( empty( $path['modules'] ) || ! is_array( $path['modules'] ) ) {
		return $count;
	}

	foreach ( $path['modules'] as $module ) {
		$count += isset( $module['lessons'] ) && is_array( $module['lessons'] ) ? count( $module['lessons'] ) : 0;
	}

	return $count;
}

function gac_theme_get_status_counts( $resources = null ) {
	$resources = is_array( $resources ) ? $resources : gac_theme_get_resources();
	$counts    = array();

	foreach ( $resources as $resource ) {
		$status = isset( $resource['status'] ) ? $resource['status'] : 'unknown';
		if ( ! isset( $counts[ $status ] ) ) {
			$counts[ $status ] = 0;
		}

		$counts[ $status ]++;
	}

	arsort( $counts );

	return $counts;
}

function gac_theme_get_domain_counts( $resources = null ) {
	$resources = is_array( $resources ) ? $resources : gac_theme_get_resources();
	$rows      = array();

	foreach ( $resources as $resource ) {
		if ( empty( $resource['domains'] ) || ! is_array( $resource['domains'] ) ) {
			continue;
		}

		foreach ( $resource['domains'] as $domain ) {
			if ( ! isset( $rows[ $domain ] ) ) {
				$rows[ $domain ] = array(
					'domain'        => $domain,
					'resourceCount' => 0,
					'paths'         => array(),
				);
			}

			$rows[ $domain ]['resourceCount']++;

			if ( ! empty( $resource['relatedPaths'] ) && is_array( $resource['relatedPaths'] ) ) {
				$rows[ $domain ]['paths'] = array_merge( $rows[ $domain ]['paths'], $resource['relatedPaths'] );
			}
		}
	}

	foreach ( $rows as &$row ) {
		$row['paths']     = array_values( array_unique( $row['paths'] ) );
		$row['pathCount'] = count( $row['paths'] );
	}
	unset( $row );

	usort(
		$rows,
		function ( $left, $right ) {
			if ( $left['resourceCount'] === $right['resourceCount'] ) {
				return strcmp( $left['domain'], $right['domain'] );
			}

			return $right['resourceCount'] <=> $left['resourceCount'];
		}
	);

	return array_values( $rows );
}

function gac_theme_get_resource_variants( $resources = null ) {
	$resources = is_array( $resources ) ? $resources : gac_theme_get_resources();
	$variants  = array();

	foreach ( $resources as $resource ) {
		if ( empty( $resource['status'] ) || 'canonical' === $resource['status'] ) {
			continue;
		}

		$variants[] = array(
			'title'        => $resource['title'],
			'status'       => $resource['status'],
			'statusLabel'  => gac_theme_status_label( $resource['status'] ),
			'summary'      => $resource['summary'],
			'url'          => gac_theme_build_resource_url( $resource ),
			'pdfUrl'       => gac_theme_build_resource_pdf_url( $resource ),
			'domains'      => isset( $resource['domains'] ) ? $resource['domains'] : array(),
			'file'         => basename( $resource['file'] ),
			'relatedPaths' => gac_theme_build_path_cards_from_slugs( isset( $resource['relatedPaths'] ) ? $resource['relatedPaths'] : array() ),
		);
	}

	return $variants;
}

function gac_theme_get_path_health_rows( $paths = null, $resources = null ) {
	$paths            = is_array( $paths ) ? $paths : gac_theme_get_paths();
	$resources        = is_array( $resources ) ? $resources : gac_theme_get_resources();
	$resources_by_slug = array();
	$rows             = array();

	foreach ( $resources as $resource ) {
		$resources_by_slug[ $resource['slug'] ] = $resource;
	}

	foreach ( $paths as $path ) {
		$module_count    = isset( $path['modules'] ) && is_array( $path['modules'] ) ? count( $path['modules'] ) : 0;
		$lesson_count    = gac_theme_count_lessons_in_path( $path );
		$canonical_count = 0;
		$support_count   = 0;

		foreach ( isset( $path['resourceSlugs'] ) ? $path['resourceSlugs'] : array() as $resource_slug ) {
			if ( empty( $resources_by_slug[ $resource_slug ] ) ) {
				continue;
			}

			if ( 'canonical' === $resources_by_slug[ $resource_slug ]['status'] ) {
				$canonical_count++;
			} else {
				$support_count++;
			}
		}

		$rows[] = array(
			'slug'           => $path['slug'],
			'title'          => $path['title'],
			'level'          => $path['level'],
			'status'         => $path['status'],
			'statusLabel'    => gac_theme_status_label( $path['status'] ),
			'estimatedTime'  => $path['estimatedTime'],
			'moduleCount'    => $module_count,
			'lessonCount'    => $lesson_count,
			'resourceCount'  => isset( $path['resourceSlugs'] ) ? count( $path['resourceSlugs'] ) : 0,
			'canonicalCount' => $canonical_count,
			'supportCount'   => $support_count,
			'url'            => gac_theme_build_path_card( $path )['url'],
		);
	}

	return $rows;
}

function gac_theme_module_slug( $path_slug, $module_index, $module_title ) {
	return sanitize_title( $path_slug . ' module ' . ( $module_index + 1 ) . ' ' . $module_title );
}

function gac_theme_lesson_slug( $path_slug, $module_index, $lesson_index, $lesson_title ) {
	return sanitize_title( $path_slug . ' lesson ' . ( $module_index + 1 ) . ' ' . ( $lesson_index + 1 ) . ' ' . $lesson_title );
}

function gac_theme_path_image( $slug ) {
	$map = array(
		'linux-fondamentaux'             => 'linux-path.png',
		'linux-reseau-dns'               => 'linux-path.png',
		'lamp-culture-bdd'               => 'mariadb-path.png',
		'mariadb-administration'         => 'mariadb-path.png',
		'mariadb-modelisation-sql'       => 'mariadb-path.png',
		'mariadb-tp'                     => 'mariadb-path.png',
		'apache-deploiement-multisites'  => 'apache-path.png',
		'apache-securite-reecriture'     => 'apache-path.png',
		'php-developpement'              => 'php-path.png',
		'wordpress-stack'                => 'wordpress-path.png',
		'nginx-php-fpm'                  => 'nginx-path.png',
	);

	$file = isset( $map[ $slug ] ) ? $map[ $slug ] : 'linux-path.png';
	return get_template_directory_uri() . '/assets/generated/' . $file;
}

function gac_theme_build_resource_pdf_url( $resource ) {
	$basename = basename( $resource['file'] );
	return home_url( '/wp-content/uploads/greta-resources/' . rawurlencode( $basename ) );
}

function gac_theme_build_resource_url( $resource ) {
	$post = get_page_by_path( $resource['slug'], OBJECT, 'learning_resource' );

	if ( $post ) {
		return get_permalink( $post );
	}

	return home_url( '/resources/' . $resource['slug'] . '/' );
}

function gac_theme_build_module_reference( $path, $module_index ) {
	if ( empty( $path['modules'][ $module_index ] ) ) {
		return null;
	}

	$module = $path['modules'][ $module_index ];
	$slug   = gac_theme_module_slug( $path['slug'], $module_index, $module['title'] );
	$post   = get_page_by_path( $slug, OBJECT, 'learning_module' );

	return array(
		'slug'         => $slug,
		'title'        => $module['title'],
		'summary'      => $module['summary'],
		'index'        => $module_index + 1,
		'lessonCount'  => isset( $module['lessons'] ) && is_array( $module['lessons'] ) ? count( $module['lessons'] ) : 0,
		'url'          => $post ? get_permalink( $post ) : home_url( '/modules/' . $slug . '/' ),
	);
}

function gac_theme_build_lesson_reference( $path, $module_index, $lesson_index ) {
	if ( empty( $path['modules'][ $module_index ] ) || empty( $path['modules'][ $module_index ]['lessons'][ $lesson_index ] ) ) {
		return null;
	}

	$module       = $path['modules'][ $module_index ];
	$lesson_title = $module['lessons'][ $lesson_index ];
	$slug         = gac_theme_lesson_slug( $path['slug'], $module_index, $lesson_index, $lesson_title );
	$post         = get_page_by_path( $slug, OBJECT, 'learning_lesson' );

	return array(
		'slug'        => $slug,
		'title'       => $lesson_title,
		'moduleTitle' => $module['title'],
		'moduleIndex' => $module_index + 1,
		'lessonIndex' => $lesson_index + 1,
		'url'         => $post ? get_permalink( $post ) : home_url( '/lessons/' . $slug . '/' ),
	);
}

function gac_theme_get_flattened_lessons_for_path( $path ) {
	$lessons = array();

	if ( empty( $path['modules'] ) || ! is_array( $path['modules'] ) ) {
		return $lessons;
	}

	foreach ( $path['modules'] as $module_index => $module ) {
		foreach ( isset( $module['lessons'] ) ? $module['lessons'] : array() as $lesson_index => $lesson_title ) {
			$reference = gac_theme_build_lesson_reference( $path, $module_index, $lesson_index );
			if ( $reference ) {
				$lessons[] = $reference;
			}
		}
	}

	return $lessons;
}

function gac_theme_get_module_navigation( $payload ) {
	if ( empty( $payload['pathSlug'] ) ) {
		return array();
	}

	$path         = gac_theme_get_path_by_slug( $payload['pathSlug'] );
	$current_index = isset( $payload['moduleIndex'] ) ? max( 0, (int) $payload['moduleIndex'] - 1 ) : 0;

	if ( ! $path ) {
		return array();
	}

	return array(
		'path'     => gac_theme_build_path_card( $path ),
		'current'  => gac_theme_build_module_reference( $path, $current_index ),
		'previous' => $current_index > 0 ? gac_theme_build_module_reference( $path, $current_index - 1 ) : null,
		'next'     => isset( $path['modules'][ $current_index + 1 ] ) ? gac_theme_build_module_reference( $path, $current_index + 1 ) : null,
	);
}

function gac_theme_get_lesson_navigation( $payload ) {
	if ( empty( $payload['pathSlug'] ) ) {
		return array();
	}

	$path          = gac_theme_get_path_by_slug( $payload['pathSlug'] );
	$current_module = isset( $payload['moduleIndex'] ) ? max( 0, (int) $payload['moduleIndex'] - 1 ) : 0;
	$current_lesson = isset( $payload['lessonIndex'] ) ? max( 0, (int) $payload['lessonIndex'] - 1 ) : 0;

	if ( ! $path ) {
		return array();
	}

	$current   = gac_theme_build_lesson_reference( $path, $current_module, $current_lesson );
	$flattened = gac_theme_get_flattened_lessons_for_path( $path );
	$position  = null;

	foreach ( $flattened as $index => $lesson ) {
		if ( $current && $lesson['slug'] === $current['slug'] ) {
			$position = $index;
			break;
		}
	}

	return array(
		'path'     => gac_theme_build_path_card( $path ),
		'module'   => gac_theme_build_module_reference( $path, $current_module ),
		'current'  => $current,
		'previous' => null !== $position && ! empty( $flattened[ $position - 1 ] ) ? $flattened[ $position - 1 ] : null,
		'next'     => null !== $position && ! empty( $flattened[ $position + 1 ] ) ? $flattened[ $position + 1 ] : null,
	);
}

function gac_theme_build_path_card( $path ) {
	$post = get_page_by_path( $path['slug'], OBJECT, 'learning_path' );

	return array(
		'slug'         => $path['slug'],
		'title'        => $path['title'],
		'strapline'    => $path['strapline'],
		'level'        => $path['level'],
		'estimated'    => $path['estimatedTime'],
		'status'       => $path['status'],
		'resourceCount'=> count( $path['resourceSlugs'] ),
		'image'        => gac_theme_path_image( $path['slug'] ),
		'description'  => $path['description'],
		'url'          => $post ? get_permalink( $post ) : home_url( '/learning-paths/' . $path['slug'] . '/' ),
	);
}

function gac_theme_build_resource_card( $resource ) {
	return array(
		'title'        => $resource['title'],
		'slug'         => $resource['slug'],
		'summary'      => $resource['summary'],
		'format'       => $resource['format'],
		'pages'        => $resource['pages'],
		'status'       => $resource['status'],
		'domains'      => $resource['domains'],
		'url'          => gac_theme_build_resource_url( $resource ),
		'pdfUrl'       => gac_theme_build_resource_pdf_url( $resource ),
		'file'         => basename( $resource['file'] ),
		'relatedPaths' => isset( $resource['relatedPaths'] ) ? $resource['relatedPaths'] : array(),
	);
}

function gac_theme_featured_paths() {
	$featured = array(
		'php-developpement',
		'wordpress-stack',
		'apache-deploiement-multisites',
		'mariadb-administration',
	);
	$paths = array();

	foreach ( $featured as $slug ) {
		$path = gac_theme_get_path_by_slug( $slug );
		if ( $path ) {
			$paths[] = gac_theme_build_path_card( $path );
		}
	}

	return $paths;
}

function gac_theme_json_attr( $value ) {
	return htmlspecialchars( wp_json_encode( $value ), ENT_QUOTES, 'UTF-8' );
}

function gac_theme_build_path_cards_from_slugs( $slugs ) {
	$cards = array();

	foreach ( $slugs as $slug ) {
		$path = gac_theme_get_path_by_slug( $slug );
		if ( $path ) {
			$cards[] = gac_theme_build_path_card( $path );
		}
	}

	return $cards;
}

function gac_theme_build_module_card( $path, $module, $module_index ) {
	$lesson_cards = array();

	foreach ( $module['lessons'] as $lesson_index => $lesson_title ) {
		$lesson_reference = gac_theme_build_lesson_reference( $path, $module_index, $lesson_index );
		if ( $lesson_reference ) {
			$lesson_cards[] = array(
				'title' => $lesson_reference['title'],
				'url'   => $lesson_reference['url'],
			);
		}
	}

	$module_reference = gac_theme_build_module_reference( $path, $module_index );

	return array(
		'slug'          => $module_reference['slug'],
		'title'         => $module['title'],
		'summary'       => $module['summary'],
		'index'         => $module_index + 1,
		'lessonCount'   => count( $lesson_cards ),
		'estimatedTime' => (string) max( 25, count( $module['lessons'] ) * 18 ) . ' min',
		'url'           => $module_reference['url'],
		'lessons'       => $lesson_cards,
	);
}

function gac_theme_build_module_cards_for_path( $path ) {
	$cards = array();

	foreach ( $path['modules'] as $module_index => $module ) {
		$cards[] = gac_theme_build_module_card( $path, $module, $module_index );
	}

	return $cards;
}
