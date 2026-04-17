<?php
/**
 * Plugin Name: GRETA Learning Platform
 * Description: Content structures, inventory helpers and seed data for GRETA ANALYSTE CYBERSÉCURITÉ.
 * Version: 0.2.0
 * Author: OpenAI Codex
 * Text Domain: greta-learning-platform
 */

defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/catalog.php';

function gac_register_content_types() {
	register_post_type(
		'learning_path',
		array(
			'labels'       => array(
				'name'          => __( 'Learning Paths', 'greta-learning-platform' ),
				'singular_name' => __( 'Learning Path', 'greta-learning-platform' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'learning-paths' ),
			'menu_icon'    => 'dashicons-welcome-learn-more',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);

	register_post_type(
		'learning_module',
		array(
			'labels'       => array(
				'name'          => __( 'Modules', 'greta-learning-platform' ),
				'singular_name' => __( 'Module', 'greta-learning-platform' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'modules' ),
			'menu_icon'    => 'dashicons-index-card',
			'supports'     => array( 'title', 'editor', 'excerpt' ),
		)
	);

	register_post_type(
		'learning_lesson',
		array(
			'labels'       => array(
				'name'          => __( 'Lessons', 'greta-learning-platform' ),
				'singular_name' => __( 'Lesson', 'greta-learning-platform' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'lessons' ),
			'menu_icon'    => 'dashicons-media-document',
			'supports'     => array( 'title', 'editor', 'excerpt' ),
		)
	);

	register_post_type(
		'learning_resource',
		array(
			'labels'       => array(
				'name'          => __( 'Resources', 'greta-learning-platform' ),
				'singular_name' => __( 'Resource', 'greta-learning-platform' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'resources' ),
			'menu_icon'    => 'dashicons-portfolio',
			'supports'     => array( 'title', 'editor', 'excerpt' ),
		)
	);
}
add_action( 'init', 'gac_register_content_types' );

function gac_register_taxonomies() {
	$taxonomies = array(
		'learning_domain' => array(
			'label'    => __( 'Domaines', 'greta-learning-platform' ),
			'posttype' => array( 'learning_path', 'learning_resource' ),
		),
		'learning_level'  => array(
			'label'    => __( 'Niveaux', 'greta-learning-platform' ),
			'posttype' => array( 'learning_path' ),
		),
		'resource_format' => array(
			'label'    => __( 'Formats', 'greta-learning-platform' ),
			'posttype' => array( 'learning_resource' ),
		),
		'content_status'  => array(
			'label'    => __( 'Statuts', 'greta-learning-platform' ),
			'posttype' => array( 'learning_path', 'learning_resource' ),
		),
		'stack_area'      => array(
			'label'    => __( 'Stack Areas', 'greta-learning-platform' ),
			'posttype' => array( 'learning_path', 'learning_resource' ),
		),
	);

	foreach ( $taxonomies as $taxonomy => $args ) {
		register_taxonomy(
			$taxonomy,
			$args['posttype'],
			array(
				'label'             => $args['label'],
				'public'            => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'hierarchical'      => false,
				'rewrite'           => array( 'slug' => $taxonomy ),
			)
		);
	}
}
add_action( 'init', 'gac_register_taxonomies' );

function gac_seed_learning_content() {
	$catalog = gac_catalog_get_data();
	$resource_lookup = array();

	if ( empty( $catalog['paths'] ) || empty( $catalog['resources'] ) ) {
		return;
	}

	foreach ( $catalog['resources'] as $resource ) {
		$resource_lookup[ $resource['slug'] ] = $resource;
	}

	foreach ( $catalog['paths'] as $path ) {
		$existing = get_page_by_path( $path['slug'], OBJECT, 'learning_path' );

		if ( $existing ) {
			$post_id = $existing->ID;
		} else {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'learning_path',
					'post_status'  => 'publish',
					'post_title'   => $path['title'],
					'post_name'    => $path['slug'],
					'post_excerpt' => $path['strapline'],
					'post_content' => $path['description'],
				),
				true
			);

			if ( is_wp_error( $post_id ) ) {
				continue;
			}
		}

		update_post_meta( $post_id, 'gac_path_payload', $path );
		update_post_meta( $post_id, 'gac_estimated_time', $path['estimatedTime'] );

		if ( ! empty( $path['level'] ) ) {
			wp_set_object_terms( $post_id, $path['level'], 'learning_level', false );
		}

		if ( ! empty( $path['status'] ) ) {
			wp_set_object_terms( $post_id, $path['status'], 'content_status', false );
		}

		foreach ( $path['modules'] as $module_index => $module ) {
			$module_slug        = gac_catalog_module_slug( $path['slug'], $module_index, $module['title'] );
			$module_existing    = get_page_by_path( $module_slug, OBJECT, 'learning_module' );
			$lesson_slugs       = array();
			$module_resources   = array_slice( $path['resourceSlugs'], 0, min( 3, count( $path['resourceSlugs'] ) ) );
			$module_objectives  = array();
			$module_prereq      = array();

			foreach ( $module['lessons'] as $lesson_index => $lesson_title ) {
				$lesson_slugs[]      = gac_catalog_lesson_slug( $path['slug'], $module_index, $lesson_index, $lesson_title );
				$module_objectives[] = sprintf( 'Relier "%s" au contexte de %s.', $lesson_title, $path['title'] );
			}

			if ( 0 === $module_index ) {
				$module_prereq[] = sprintf( 'Comprendre la logique générale du parcours %s.', $path['title'] );
			} else {
				$module_prereq[] = sprintf( 'Avoir parcouru le module précédent : %s.', $path['modules'][ $module_index - 1 ]['title'] );
			}

			if ( ! $module_existing ) {
				$module_post_id = wp_insert_post(
					array(
						'post_type'    => 'learning_module',
						'post_status'  => 'publish',
						'post_title'   => $module['title'],
						'post_name'    => $module_slug,
						'post_excerpt' => $module['summary'],
						'post_content' => $module['summary'],
					),
					true
				);

				if ( ! is_wp_error( $module_post_id ) ) {
					update_post_meta(
						$module_post_id,
						'gac_module_payload',
						array(
							'slug'              => $module_slug,
							'title'             => $module['title'],
							'summary'           => $module['summary'],
							'pathSlug'          => $path['slug'],
							'pathTitle'         => $path['title'],
							'pathStrapline'     => $path['strapline'],
							'moduleIndex'       => $module_index + 1,
							'estimatedTime'     => (string) max( 25, count( $module['lessons'] ) * 18 ) . ' min',
							'objectives'        => $module_objectives,
							'prerequisites'     => $module_prereq,
							'lessonSlugs'       => $lesson_slugs,
							'lessonTitles'      => $module['lessons'],
							'resourceSlugs'     => $module_resources,
							'notes'             => array(
								$module['summary'],
								$path['stackNarrative'],
							),
							'practicalRelevance' => $path['stackNarrative'],
						)
					);
					update_post_meta( $module_post_id, 'gac_parent_path_slug', $path['slug'] );
				}
			}

			foreach ( $module['lessons'] as $lesson_index => $lesson_title ) {
				$lesson_slug         = gac_catalog_lesson_slug( $path['slug'], $module_index, $lesson_index, $lesson_title );
				$lesson_existing     = get_page_by_path( $lesson_slug, OBJECT, 'learning_lesson' );
				$related_domains     = array();
				$related_resources   = array_slice( $path['resourceSlugs'], 0, min( 2, count( $path['resourceSlugs'] ) ) );
				$lesson_prerequisite = array();

				foreach ( $related_resources as $resource_slug ) {
					if ( isset( $resource_lookup[ $resource_slug ]['domains'] ) ) {
						$related_domains = array_merge( $related_domains, $resource_lookup[ $resource_slug ]['domains'] );
					}
				}

				$related_domains = array_values( array_unique( $related_domains ) );

				if ( 0 === $lesson_index ) {
					$lesson_prerequisite[] = sprintf( 'Situer la leçon dans le module %s.', $module['title'] );
				} else {
					$lesson_prerequisite[] = sprintf( 'Avoir vu la leçon précédente : %s.', $module['lessons'][ $lesson_index - 1 ] );
				}

				if ( $lesson_existing ) {
					continue;
				}

				$lesson_post_id = wp_insert_post(
					array(
						'post_type'    => 'learning_lesson',
						'post_status'  => 'publish',
						'post_title'   => $lesson_title,
						'post_name'    => $lesson_slug,
						'post_excerpt' => sprintf( 'Leçon du module %s dans le parcours %s.', $module['title'], $path['title'] ),
						'post_content' => sprintf( 'Leçon structurée à partir du module %s dans le parcours %s.', $module['title'], $path['title'] ),
					),
					true
				);

				if ( is_wp_error( $lesson_post_id ) ) {
					continue;
				}

				update_post_meta(
					$lesson_post_id,
					'gac_lesson_payload',
					array(
						'slug'               => $lesson_slug,
						'title'              => $lesson_title,
						'summary'            => sprintf( 'Cette leçon s\'inscrit dans le module %s du parcours %s.', $module['title'], $path['title'] ),
						'pathSlug'           => $path['slug'],
						'pathTitle'          => $path['title'],
						'moduleSlug'         => $module_slug,
						'moduleTitle'        => $module['title'],
						'moduleIndex'        => $module_index + 1,
						'lessonIndex'        => $lesson_index + 1,
						'estimatedTime'      => '20 min',
						'objectives'         => array(
							sprintf( 'Comprendre la notion "%s" dans le contexte de %s.', $lesson_title, $path['title'] ),
							sprintf( 'Relier "%s" aux manipulations vues dans %s.', $lesson_title, $module['title'] ),
							sprintf( 'Identifier son impact pratique dans le projet construit avec %s.', $path['title'] ),
						),
						'prerequisites'      => $lesson_prerequisite,
						'notes'              => array(
							$module['summary'],
							$path['stackNarrative'],
						),
						'relatedConcepts'    => array_slice( $related_domains, 0, 4 ),
						'resourceSlugs'      => $related_resources,
						'practicalRelevance' => $path['stackNarrative'],
					)
				);
				update_post_meta( $lesson_post_id, 'gac_parent_path_slug', $path['slug'] );
				update_post_meta( $lesson_post_id, 'gac_parent_module_slug', $module_slug );
			}
		}
	}

	foreach ( $catalog['resources'] as $resource ) {
		$existing = get_page_by_path( $resource['slug'], OBJECT, 'learning_resource' );

		if ( $existing ) {
			$post_id = $existing->ID;
		} else {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'learning_resource',
					'post_status'  => 'publish',
					'post_title'   => $resource['title'],
					'post_name'    => $resource['slug'],
					'post_excerpt' => $resource['summary'],
					'post_content' => $resource['summary'],
				),
				true
			);

			if ( is_wp_error( $post_id ) ) {
				continue;
			}
		}

		update_post_meta( $post_id, 'gac_resource_payload', $resource );
		update_post_meta( $post_id, 'gac_resource_file', $resource['file'] );
		update_post_meta( $post_id, 'gac_resource_pages', $resource['pages'] );
		update_post_meta( $post_id, 'gac_related_paths', $resource['relatedPaths'] );

		if ( ! empty( $resource['status'] ) ) {
			wp_set_object_terms( $post_id, $resource['status'], 'content_status', false );
		}

		if ( ! empty( $resource['format'] ) ) {
			wp_set_object_terms( $post_id, $resource['format'], 'resource_format', false );
		}

		if ( ! empty( $resource['domains'] ) ) {
			wp_set_object_terms( $post_id, $resource['domains'], 'learning_domain', false );
		}
	}

	$front_page = get_page_by_path( 'home' );
	if ( ! $front_page ) {
		$front_page_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Home',
				'post_name'   => 'home',
			)
		);
		if ( $front_page_id && ! is_wp_error( $front_page_id ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page_id );
		}
	}

	$resource_page = get_page_by_path( 'resource-library' );
	if ( ! $resource_page ) {
		$resource_page_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Resource Library',
				'post_name'   => 'resource-library',
			)
		);
		if ( $resource_page_id && ! is_wp_error( $resource_page_id ) ) {
			update_post_meta( $resource_page_id, '_wp_page_template', 'page-resource-library.php' );
		}
	}

	$dashboard_page = get_page_by_path( 'dashboard' );
	if ( ! $dashboard_page ) {
		$dashboard_page_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Dashboard',
				'post_name'   => 'dashboard',
			)
		);
		if ( $dashboard_page_id && ! is_wp_error( $dashboard_page_id ) ) {
			update_post_meta( $dashboard_page_id, '_wp_page_template', 'page-dashboard.php' );
		}
	}
}

function gac_maybe_sync_learning_content() {
	$version = '0.2.0';

	if ( get_option( 'gac_seed_version' ) === $version ) {
		return;
	}

	gac_seed_learning_content();
	update_option( 'gac_seed_version', $version );
}
add_action( 'init', 'gac_maybe_sync_learning_content', 25 );

function gac_activate_learning_platform() {
	gac_register_content_types();
	gac_register_taxonomies();
	gac_seed_learning_content();
	update_option( 'gac_seed_version', '0.2.0' );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'gac_activate_learning_platform' );

function gac_deactivate_learning_platform() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'gac_deactivate_learning_platform' );

function gac_register_admin_page() {
	add_menu_page(
		__( 'GRETA Corpus', 'greta-learning-platform' ),
		__( 'GRETA Corpus', 'greta-learning-platform' ),
		'manage_options',
		'greta-corpus',
		'gac_render_admin_page',
		'dashicons-analytics',
		21
	);
}
add_action( 'admin_menu', 'gac_register_admin_page' );

function gac_render_admin_page() {
	$catalog   = gac_catalog_get_data();
	$metrics   = isset( $catalog['metrics'] ) ? $catalog['metrics'] : array();
	$resources = isset( $catalog['resources'] ) ? $catalog['resources'] : array();
	$variants  = array();

	foreach ( $resources as $resource ) {
		if ( in_array( $resource['status'], array( 'variant', 'companion', 'cross-track' ), true ) ) {
			$variants[] = $resource;
		}
	}
	?>
	<div class="wrap">
		<h1>GRETA ANALYSTE CYBERSÉCURITÉ</h1>
		<p>Corpus local normalisé pour la plateforme d'apprentissage et le projet vitrine.</p>
		<ul>
			<li><strong>Paths :</strong> <?php echo esc_html( isset( $metrics['paths'] ) ? $metrics['paths'] : 0 ); ?></li>
			<li><strong>Resources :</strong> <?php echo esc_html( isset( $metrics['resources'] ) ? $metrics['resources'] : 0 ); ?></li>
			<li><strong>Pages source :</strong> <?php echo esc_html( isset( $metrics['pages'] ) ? $metrics['pages'] : 0 ); ?></li>
		</ul>
		<h2>Ressources à surveiller</h2>
		<table class="widefat striped">
			<thead>
				<tr>
					<th>Titre</th>
					<th>Statut</th>
					<th>Fichier</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $variants as $variant ) : ?>
				<tr>
					<td><?php echo esc_html( $variant['title'] ); ?></td>
					<td><?php echo esc_html( $variant['status'] ); ?></td>
					<td><code><?php echo esc_html( $variant['file'] ); ?></code></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}
