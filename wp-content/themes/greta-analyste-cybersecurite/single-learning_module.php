<?php
get_header();

the_post();

$payload       = get_post_meta( get_the_ID(), 'gac_module_payload', true );
$payload       = is_array( $payload ) ? $payload : array();
$path          = isset( $payload['pathSlug'] ) ? gac_theme_get_path_by_slug( $payload['pathSlug'] ) : null;
$module_nav    = ! empty( $payload ) ? gac_theme_get_module_navigation( $payload ) : array();
$path_card     = ! empty( $module_nav['path'] ) ? $module_nav['path'] : ( $path ? gac_theme_build_path_card( $path ) : null );
$resources     = array();
$lesson_links  = array();

if ( ! empty( $payload['resourceSlugs'] ) ) {
	foreach ( gac_theme_get_resources_by_slugs( $payload['resourceSlugs'] ) as $resource ) {
		$resources[] = gac_theme_build_resource_card( $resource );
	}
}

if ( ! empty( $payload['lessonTitles'] ) ) {
	foreach ( $payload['lessonTitles'] as $lesson_index => $lesson_title ) {
		$lesson_slug = gac_theme_lesson_slug( $payload['pathSlug'], $payload['moduleIndex'] - 1, $lesson_index, $lesson_title );
		$lesson_post = get_page_by_path( $lesson_slug, OBJECT, 'learning_lesson' );
		$lesson_links[] = array(
			'title' => $lesson_title,
			'url'   => $lesson_post ? get_permalink( $lesson_post ) : home_url( '/lessons/' . $lesson_slug . '/' ),
		);
	}
}
?>
<main>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
			<div class="space-y-5">
				<p class="gac-section-label">Module detail</p>
				<h1 class="text-5xl font-black text-white"><?php the_title(); ?></h1>
				<?php if ( ! empty( $payload ) ) : ?>
					<div class="flex flex-wrap gap-3">
						<span class="gac-tag text-white">Module <?php echo esc_html( $payload['moduleIndex'] ); ?></span>
						<span class="gac-tag text-white"><?php echo esc_html( $payload['estimatedTime'] ); ?></span>
						<?php if ( $path_card ) : ?>
							<a class="gac-tag text-white" href="<?php echo esc_url( $path_card['url'] ); ?>"><?php echo esc_html( $path_card['title'] ); ?></a>
						<?php endif; ?>
					</div>
					<p class="max-w-3xl text-lg leading-8 text-slate-300"><?php echo esc_html( $payload['summary'] ); ?></p>
					<p class="max-w-3xl text-base leading-8 text-slate-400"><?php echo esc_html( $payload['practicalRelevance'] ); ?></p>
					<div class="flex flex-wrap gap-3">
						<?php if ( ! empty( $lesson_links[0] ) ) : ?>
							<a class="gac-button-primary" href="<?php echo esc_url( $lesson_links[0]['url'] ); ?>">Ouvrir la première leçon</a>
						<?php endif; ?>
						<?php if ( $path_card ) : ?>
							<a class="gac-button-secondary" href="<?php echo esc_url( $path_card['url'] ); ?>">Retour au parcours</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $payload ) ) : ?>
				<div class="gac-card p-6">
					<p class="gac-section-label">Prérequis</p>
					<ul class="mt-4 space-y-3 text-sm leading-7 text-slate-300">
						<?php foreach ( $payload['prerequisites'] as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8">
			<?php if ( ! empty( $payload ) ) : ?>
				<div class="space-y-4">
					<p class="gac-section-label">Learning objectives</p>
					<h2 class="text-4xl font-black text-white">Ce module doit rendre la progression visible.</h2>
					<ul class="space-y-3 text-base leading-8 text-slate-300">
						<?php foreach ( $payload['objectives'] as $objective ) : ?>
							<li class="flex gap-3"><span class="mt-2 h-2 w-2 rounded-full bg-emerald-300"></span><span><?php echo esc_html( $objective ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="space-y-4">
					<p class="gac-section-label">Lessons</p>
					<h2 class="text-4xl font-black text-white">Leçons rattachées</h2>
					<div class="grid gap-4">
						<?php foreach ( $lesson_links as $lesson ) : ?>
							<a class="gac-card p-5 transition hover:-translate-y-1" href="<?php echo esc_url( $lesson['url'] ); ?>">
								<h3 class="text-lg font-bold text-white"><?php echo esc_html( $lesson['title'] ); ?></h3>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( ! empty( $payload ) ) : ?>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-8 max-w-3xl space-y-4">
				<p class="gac-section-label">Module sequence</p>
				<h2 class="text-4xl font-black text-white">Continuité du parcours.</h2>
			</div>
			<div class="grid gap-4 lg:grid-cols-3">
				<div class="gac-card p-5">
					<p class="gac-section-label">Module précédent</p>
					<?php if ( ! empty( $module_nav['previous'] ) ) : ?>
						<h3 class="mt-3 text-xl font-bold text-white">
							<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $module_nav['previous']['url'] ); ?>"><?php echo esc_html( $module_nav['previous']['title'] ); ?></a>
						</h3>
						<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $module_nav['previous']['summary'] ); ?></p>
					<?php else : ?>
						<p class="mt-3 text-sm leading-7 text-slate-400">Début du parcours. Ce module pose la première brique de progression.</p>
					<?php endif; ?>
				</div>
				<div class="gac-card p-5">
					<p class="gac-section-label">Point courant</p>
					<h3 class="mt-3 text-xl font-bold text-white"><?php the_title(); ?></h3>
					<p class="mt-3 text-sm leading-7 text-slate-400">
						<?php echo esc_html( count( $lesson_links ) ); ?> leçons rattachées. La page détail garde le lien entre objectifs, ressource source et ordre pédagogique.
					</p>
				</div>
				<div class="gac-card p-5">
					<p class="gac-section-label">Module suivant</p>
					<?php if ( ! empty( $module_nav['next'] ) ) : ?>
						<h3 class="mt-3 text-xl font-bold text-white">
							<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $module_nav['next']['url'] ); ?>"><?php echo esc_html( $module_nav['next']['title'] ); ?></a>
						</h3>
						<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $module_nav['next']['summary'] ); ?></p>
					<?php else : ?>
						<p class="mt-3 text-sm leading-7 text-slate-400">Fin du module. La suite logique se fait côté ressources ou retour parcours.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="py-16">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-8 max-w-3xl space-y-4">
				<p class="gac-section-label">Related resources</p>
				<h2 class="text-4xl font-black text-white">Supports source pour approfondir le module.</h2>
			</div>
			<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
				<?php foreach ( $resources as $resource_card ) : ?>
					<?php get_template_part( 'template-parts/resource-card', null, array( 'resource' => $resource_card ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
