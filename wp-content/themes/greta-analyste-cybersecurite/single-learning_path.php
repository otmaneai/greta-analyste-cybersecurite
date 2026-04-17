<?php
get_header();

the_post();

$slug      = get_post_field( 'post_name', get_the_ID() );
$path      = get_post_meta( get_the_ID(), 'gac_path_payload', true );
$path      = is_array( $path ) ? $path : gac_theme_get_path_by_slug( $slug );
$resources = array();
$modules   = array();

if ( $path && ! empty( $path['resourceSlugs'] ) ) {
	foreach ( gac_theme_get_resources_by_slugs( $path['resourceSlugs'] ) as $resource ) {
		$resources[] = gac_theme_build_resource_card( $resource );
	}

	$modules = gac_theme_build_module_cards_for_path( $path );
}
?>
<main>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8">
			<div class="space-y-5">
				<p class="gac-section-label">Learning path detail</p>
				<h1 class="text-5xl font-black text-white"><?php the_title(); ?></h1>
				<?php if ( $path ) : ?>
					<p class="text-xl leading-8 text-slate-300"><?php echo esc_html( $path['strapline'] ); ?></p>
					<div class="flex flex-wrap gap-3">
						<span class="gac-tag text-white"><?php echo esc_html( $path['level'] ); ?></span>
						<span class="gac-tag text-white"><?php echo esc_html( $path['estimatedTime'] ); ?></span>
						<span class="gac-tag text-white"><?php echo esc_html( gac_theme_status_label( $path['status'] ) ); ?></span>
					</div>
					<div class="gac-prose max-w-3xl text-base leading-8">
						<p><?php echo esc_html( $path['description'] ); ?></p>
						<p><?php echo esc_html( $path['stackNarrative'] ); ?></p>
					</div>
				<?php else : ?>
					<div class="gac-prose max-w-3xl text-base leading-8"><?php the_content(); ?></div>
				<?php endif; ?>
			</div>
			<div class="gac-image-frame">
				<img class="h-full w-full object-cover" src="<?php echo esc_url( gac_theme_path_image( $slug ) ); ?>" alt="<?php the_title_attribute(); ?>">
			</div>
		</div>
	</section>

	<?php if ( $path ) : ?>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-8 max-w-3xl space-y-4">
				<p class="gac-section-label">Modules</p>
				<h2 class="text-4xl font-black text-white">Structure du parcours</h2>
			</div>
			<div class="grid gap-4">
				<?php foreach ( $modules as $index => $module ) : ?>
					<div class="gac-card p-5">
						<div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
							<div class="space-y-3">
								<div class="flex flex-wrap gap-2">
									<span class="gac-tag text-white">Module <?php echo esc_html( $module['index'] ); ?></span>
									<span class="gac-tag text-white"><?php echo esc_html( $module['lessonCount'] ); ?> leçons</span>
									<span class="gac-tag text-white"><?php echo esc_html( $module['estimatedTime'] ); ?></span>
								</div>
								<div>
									<h3 class="text-xl font-bold text-white"><?php echo esc_html( $module['title'] ); ?></h3>
									<p class="mt-2 max-w-3xl text-sm leading-7 text-slate-400"><?php echo esc_html( $module['summary'] ); ?></p>
								</div>
							</div>
							<a class="gac-button-secondary" href="<?php echo esc_url( $module['url'] ); ?>">Voir le module</a>
						</div>
						<div class="mt-4 border-t border-white/10 pt-4">
							<ul class="grid gap-3 text-sm text-slate-300 md:grid-cols-2">
								<?php foreach ( $module['lessons'] as $lesson ) : ?>
									<li>
										<a class="flex items-start gap-3 transition hover:text-white" href="<?php echo esc_url( $lesson['url'] ); ?>">
											<span class="mt-1 h-2 w-2 rounded-full bg-emerald-300"></span>
											<span><?php echo esc_html( $lesson['title'] ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
			<div class="space-y-4">
				<p class="gac-section-label">Practical outcomes</p>
				<h2 class="text-4xl font-black text-white">Ce que ce parcours rend visible dans le produit.</h2>
				<ul class="space-y-3 text-base leading-8 text-slate-300">
					<?php foreach ( $path['outcomes'] as $outcome ) : ?>
						<li class="flex gap-3"><span class="mt-2 h-2 w-2 rounded-full bg-amber-300"></span><span><?php echo esc_html( $outcome ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="space-y-4">
				<p class="gac-section-label">Resources</p>
				<h2 class="text-4xl font-black text-white">Supports source liés à ce parcours.</h2>
				<div class="grid gap-4 md:grid-cols-2">
					<?php foreach ( $resources as $resource_card ) : ?>
						<?php get_template_part( 'template-parts/resource-card', null, array( 'resource' => $resource_card ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>
</main>
<?php
get_footer();
