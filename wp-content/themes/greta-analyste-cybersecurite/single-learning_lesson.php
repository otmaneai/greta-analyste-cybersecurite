<?php
get_header();

the_post();

$payload   = get_post_meta( get_the_ID(), 'gac_lesson_payload', true );
$payload   = is_array( $payload ) ? $payload : array();
$path      = isset( $payload['pathSlug'] ) ? gac_theme_get_path_by_slug( $payload['pathSlug'] ) : null;
$lesson_nav = ! empty( $payload ) ? gac_theme_get_lesson_navigation( $payload ) : array();
$path_card  = ! empty( $lesson_nav['path'] ) ? $lesson_nav['path'] : ( $path ? gac_theme_build_path_card( $path ) : null );
$module_ref = ! empty( $lesson_nav['module'] ) ? $lesson_nav['module'] : null;
$resources = array();

if ( ! empty( $payload['resourceSlugs'] ) ) {
	foreach ( gac_theme_get_resources_by_slugs( $payload['resourceSlugs'] ) as $resource ) {
		$resources[] = gac_theme_build_resource_card( $resource );
	}
}
?>
<main>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
			<div class="space-y-5">
				<p class="gac-section-label">Lesson detail</p>
				<h1 class="text-5xl font-black text-white"><?php the_title(); ?></h1>
				<?php if ( ! empty( $payload ) ) : ?>
					<div class="flex flex-wrap gap-3">
						<span class="gac-tag text-white">Lesson <?php echo esc_html( $payload['lessonIndex'] ); ?></span>
						<span class="gac-tag text-white"><?php echo esc_html( $payload['estimatedTime'] ); ?></span>
						<?php if ( $path_card ) : ?>
							<a class="gac-tag text-white" href="<?php echo esc_url( $path_card['url'] ); ?>"><?php echo esc_html( $path_card['title'] ); ?></a>
						<?php endif; ?>
						<?php if ( $module_ref ) : ?>
							<a class="gac-tag text-white" href="<?php echo esc_url( $module_ref['url'] ); ?>"><?php echo esc_html( $module_ref['title'] ); ?></a>
						<?php endif; ?>
					</div>
					<p class="max-w-3xl text-lg leading-8 text-slate-300"><?php echo esc_html( $payload['summary'] ); ?></p>
					<div class="flex flex-wrap gap-3">
						<?php if ( $module_ref ) : ?>
							<a class="gac-button-secondary" href="<?php echo esc_url( $module_ref['url'] ); ?>">Retour au module</a>
						<?php endif; ?>
						<?php if ( $path_card ) : ?>
							<a class="gac-button-secondary" href="<?php echo esc_url( $path_card['url'] ); ?>">Retour au parcours</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $payload ) ) : ?>
				<div class="gac-card p-6">
					<p class="gac-section-label">Practical relevance</p>
					<p class="mt-4 text-sm leading-7 text-slate-300"><?php echo esc_html( $payload['practicalRelevance'] ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
			<?php if ( ! empty( $payload ) ) : ?>
				<div class="space-y-4">
					<p class="gac-section-label">Learning objectives</p>
					<h2 class="text-4xl font-black text-white">Objectifs pédagogiques</h2>
					<ul class="space-y-3 text-base leading-8 text-slate-300">
						<?php foreach ( $payload['objectives'] as $objective ) : ?>
							<li class="flex gap-3"><span class="mt-2 h-2 w-2 rounded-full bg-emerald-300"></span><span><?php echo esc_html( $objective ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="space-y-4">
					<p class="gac-section-label">Prerequisites</p>
					<h2 class="text-4xl font-black text-white">Prérequis</h2>
					<ul class="space-y-3 text-base leading-8 text-slate-300">
						<?php foreach ( $payload['prerequisites'] as $item ) : ?>
							<li class="flex gap-3"><span class="mt-2 h-2 w-2 rounded-full bg-amber-300"></span><span><?php echo esc_html( $item ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( ! empty( $payload ) ) : ?>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-8 max-w-3xl space-y-4">
				<p class="gac-section-label">Lesson sequence</p>
				<h2 class="text-4xl font-black text-white">La leçon reste située dans une progression réelle.</h2>
			</div>
			<div class="grid gap-4 lg:grid-cols-3">
				<div class="gac-card p-5">
					<p class="gac-section-label">Leçon précédente</p>
					<?php if ( ! empty( $lesson_nav['previous'] ) ) : ?>
						<h3 class="mt-3 text-xl font-bold text-white">
							<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $lesson_nav['previous']['url'] ); ?>"><?php echo esc_html( $lesson_nav['previous']['title'] ); ?></a>
						</h3>
						<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $lesson_nav['previous']['moduleTitle'] ); ?></p>
					<?php else : ?>
						<p class="mt-3 text-sm leading-7 text-slate-400">Début de séquence. Cette leçon ouvre la progression documentée.</p>
					<?php endif; ?>
				</div>
				<div class="gac-card p-5">
					<p class="gac-section-label">Module courant</p>
					<?php if ( $module_ref ) : ?>
						<h3 class="mt-3 text-xl font-bold text-white">
							<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $module_ref['url'] ); ?>"><?php echo esc_html( $module_ref['title'] ); ?></a>
						</h3>
					<?php endif; ?>
					<p class="mt-3 text-sm leading-7 text-slate-400">
						La leçon reste liée à son module pour conserver le contexte, les objectifs et le support source.
					</p>
				</div>
				<div class="gac-card p-5">
					<p class="gac-section-label">Leçon suivante</p>
					<?php if ( ! empty( $lesson_nav['next'] ) ) : ?>
						<h3 class="mt-3 text-xl font-bold text-white">
							<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $lesson_nav['next']['url'] ); ?>"><?php echo esc_html( $lesson_nav['next']['title'] ); ?></a>
						</h3>
						<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $lesson_nav['next']['moduleTitle'] ); ?></p>
					<?php else : ?>
						<p class="mt-3 text-sm leading-7 text-slate-400">Fin de séquence. La suite logique se poursuit par les PDF reliés ou le module suivant.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8">
			<?php if ( ! empty( $payload ) ) : ?>
				<div class="space-y-4">
					<p class="gac-section-label">Notes extraites</p>
					<h2 class="text-4xl font-black text-white">Contexte utile pour réviser.</h2>
					<div class="space-y-4">
						<?php foreach ( $payload['notes'] as $note ) : ?>
							<div class="gac-card p-5 text-sm leading-7 text-slate-300"><?php echo esc_html( $note ); ?></div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="space-y-4">
					<p class="gac-section-label">Concepts liés</p>
					<h2 class="text-4xl font-black text-white">Repères techniques</h2>
					<div class="flex flex-wrap gap-2">
						<?php foreach ( $payload['relatedConcepts'] as $concept ) : ?>
							<span class="gac-tag text-white"><?php echo esc_html( $concept ); ?></span>
						<?php endforeach; ?>
					</div>
					<p class="text-base leading-8 text-slate-400">
						La leçon reste reliée à la matière source pour conserver le lien entre notion, manipulation réelle et architecture du projet.
					</p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="py-16">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-8 max-w-3xl space-y-4">
				<p class="gac-section-label">Related PDFs</p>
				<h2 class="text-4xl font-black text-white">Supports associés</h2>
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
