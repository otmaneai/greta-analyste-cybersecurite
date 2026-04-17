<?php
get_header();

$metrics        = gac_theme_get_metrics();
$stack          = gac_theme_get_stack();
$featured_paths = gac_theme_featured_paths();
$php_path       = gac_theme_get_path_by_slug( 'php-developpement' );
$php_resources  = $php_path ? gac_theme_get_resources_by_slugs( $php_path['resourceSlugs'] ) : array();
$resource_cards = array();

foreach ( array_slice( $php_resources, 0, 3 ) as $resource ) {
	$resource_cards[] = gac_theme_build_resource_card( $resource );
}
?>
<main>
	<section class="relative isolate overflow-hidden">
		<div class="absolute inset-0 bg-[#0d1117]"></div>
		<div class="gac-grid-fade absolute inset-0 opacity-30"></div>
		<div class="gac-hero-collage absolute inset-0 opacity-30">
			<div class="absolute -left-8 top-20 h-[52%] w-[32%] rotate-[-6deg] gac-image-frame gac-cover-shadow">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/generated/linux-path.png' ); ?>" alt="Fondamentaux Linux">
			</div>
			<div class="absolute left-[28%] top-8 h-[46%] w-[34%] rotate-[3deg] gac-image-frame gac-cover-shadow">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/generated/apache-path.png' ); ?>" alt="Apache client4">
			</div>
			<div class="absolute right-[4%] top-20 h-[50%] w-[28%] rotate-[7deg] gac-image-frame gac-cover-shadow">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/generated/wordpress-path.png' ); ?>" alt="WordPress client4">
			</div>
			<div class="absolute bottom-8 left-[18%] h-[34%] w-[32%] rotate-[-4deg] gac-image-frame gac-cover-shadow">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/generated/php-path.png' ); ?>" alt="PHP et MariaDB">
			</div>
		</div>
		<div class="absolute inset-0 bg-black/55"></div>
		<div class="relative mx-auto flex min-h-[92vh] max-w-7xl items-end px-4 pb-14 pt-20 sm:px-6 lg:px-8">
			<div class="max-w-4xl">
				<p class="gac-section-label">Premium learning platform + applied stack showcase</p>
				<h1 class="mt-5 max-w-4xl text-balance text-5xl font-black leading-none text-white sm:text-6xl lg:text-7xl">
					GRETA ANALYSTE CYBERSÉCURITÉ
				</h1>
				<p class="mt-6 max-w-3xl text-lg leading-8 text-slate-200 sm:text-xl">
					Une plateforme de parcours qui regroupe les supports GRETA, structure la progression par domaines,
					et démontre en même temps un usage réel de WordPress, PHP, MariaDB, Apache2, Tailwind CSS,
					Alpine.js et HTML.
				</p>
				<div class="mt-8 flex flex-wrap gap-3">
					<?php foreach ( $stack as $item ) : ?>
						<span class="gac-tag text-white"><?php echo esc_html( $item['name'] ); ?></span>
					<?php endforeach; ?>
				</div>
				<div class="mt-10 flex flex-wrap gap-4">
					<a class="gac-button-primary" href="<?php echo esc_url( get_post_type_archive_link( 'learning_path' ) ? get_post_type_archive_link( 'learning_path' ) : home_url( '/learning-paths/' ) ); ?>">Explorer les parcours</a>
					<a class="gac-button-secondary" href="#applied-stack">Voir la stack appliquée</a>
				</div>
				<div class="mt-12 grid gap-4 border-t border-white/10 pt-6 sm:grid-cols-3">
					<div>
						<p class="gac-stat-number text-emerald-300"><?php echo esc_html( $metrics['paths'] ); ?></p>
						<p class="mt-2 text-sm text-slate-300">learning paths normalisés</p>
					</div>
					<div>
						<p class="gac-stat-number text-amber-300"><?php echo esc_html( $metrics['resources'] ); ?></p>
						<p class="mt-2 text-sm text-slate-300">PDF issus du dossier GRETA</p>
					</div>
					<div>
						<p class="gac-stat-number text-rose-300"><?php echo esc_html( $metrics['pages'] ); ?></p>
						<p class="mt-2 text-sm text-slate-300">pages de matière structurées</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="border-t border-white/10 py-20">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
			<div class="space-y-5">
				<p class="gac-section-label">Pourquoi ce projet existe</p>
				<h2 class="text-4xl font-black text-white">Transformer un corpus GRETA brut en système d'apprentissage lisible.</h2>
				<div class="gac-prose max-w-3xl text-base leading-8">
					<p>
						Le dossier source contient des supports Linux, réseau, MariaDB, Apache, PHP, WordPress et Nginx produits
						pendant la formation. Le produit les regroupe dans des parcours cohérents, signale les variantes,
						et donne une lecture plus professionnelle du chemin parcouru.
					</p>
					<p>
						L'ambition n'est pas de publier une simple bibliothèque de PDF. L'ambition est de faire apparaître une
						architecture pédagogique, une logique de progression et une preuve visible d'appropriation du stack étudié.
					</p>
				</div>
			</div>
			<div class="gac-image-frame max-w-lg self-end">
				<img class="h-full w-full object-cover" src="<?php echo esc_url( get_template_directory_uri() . '/assets/generated/wordpress-path.png' ); ?>" alt="Couverture du support WordPress">
			</div>
		</div>
	</section>

	<section id="paths" class="border-t border-white/10 py-20">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-10 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
				<div class="max-w-2xl space-y-4">
					<p class="gac-section-label">Featured learning paths</p>
					<h2 class="text-4xl font-black text-white">Parcours mis en avant pour la première verticale.</h2>
					<p class="text-base leading-7 text-slate-400">
						Le premier slice produit met l'accent sur le coeur portfolio du projet : PHP, WordPress, Apache2 et les
						chaînes de déploiement qui rendent la démonstration crédible.
					</p>
				</div>
				<a class="text-sm font-semibold text-emerald-300" href="<?php echo esc_url( get_post_type_archive_link( 'learning_path' ) ? get_post_type_archive_link( 'learning_path' ) : home_url( '/learning-paths/' ) ); ?>">
					Voir tous les parcours
				</a>
			</div>
			<div class="grid gap-6 lg:grid-cols-2">
				<?php foreach ( $featured_paths as $path_card ) : ?>
					<?php get_template_part( 'template-parts/path-card', null, array( 'path' => $path_card ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section id="php-track" class="border-t border-white/10 py-20">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.92fr_1.08fr] lg:px-8">
			<div class="gac-image-frame">
				<img class="h-full w-full object-cover" src="<?php echo esc_url( get_template_directory_uri() . '/assets/generated/php-path.png' ); ?>" alt="Couverture du support PHP">
			</div>
			<div class="space-y-5">
				<p class="gac-section-label">PHP development track</p>
				<h2 class="text-4xl font-black text-white">La section PHP n'est pas un ajout tardif. C'est un axe produit central.</h2>
				<?php if ( $php_path ) : ?>
					<p class="text-base leading-8 text-slate-400"><?php echo esc_html( $php_path['description'] ); ?></p>
					<div class="grid gap-4 sm:grid-cols-2">
						<?php foreach ( $php_path['modules'] as $module ) : ?>
							<div class="gac-card p-5">
								<h3 class="text-lg font-bold text-white"><?php echo esc_html( $module['title'] ); ?></h3>
								<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $module['summary'] ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="border-t border-white/10 py-20">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-10 max-w-2xl space-y-4">
				<p class="gac-section-label">Resource preview</p>
				<h2 class="text-4xl font-black text-white">Les supports du parcours deviennent des ressources consultables, comparables et reliées aux parcours.</h2>
			</div>
			<div class="grid gap-6 lg:grid-cols-3">
				<?php foreach ( $resource_cards as $resource_card ) : ?>
					<?php get_template_part( 'template-parts/resource-card', null, array( 'resource' => $resource_card ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section id="applied-stack" class="border-t border-white/10 py-20">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-10 max-w-3xl space-y-4">
				<p class="gac-section-label">Built with the technologies studied during the training</p>
				<h2 class="text-4xl font-black text-white">Le stack n'est pas caché dans le code. Il est assumé comme partie de l'identité du produit.</h2>
				<p class="text-base leading-8 text-slate-400">
					Le site explique explicitement comment WordPress, PHP, MariaDB, Apache2, Tailwind CSS, Alpine.js et HTML
					se combinent pour transformer les supports GRETA en portail de parcours, bibliothèque documentaire et preuve
					de pratique technique.
				</p>
			</div>
			<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
				<?php foreach ( $stack as $item ) : ?>
					<div class="gac-card p-6">
						<p class="text-xs font-bold uppercase tracking-[0.15em] text-amber-300"><?php echo esc_html( $item['name'] ); ?></p>
						<p class="mt-4 text-sm font-semibold text-white"><?php echo esc_html( $item['role'] ); ?></p>
						<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $item['visibility'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="border-t border-white/10 py-20">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="gac-card grid gap-6 p-8 lg:grid-cols-[1fr_auto_auto_auto] lg:items-end">
				<div class="space-y-3">
					<p class="gac-section-label">Dashboard preview</p>
					<h2 class="text-3xl font-black text-white">Vue rapide sur l'état du corpus et les axes à travailler ensuite.</h2>
				</div>
				<div>
					<p class="gac-stat-number text-white"><?php echo esc_html( $metrics['modules'] ); ?></p>
					<p class="mt-2 text-sm text-slate-400">modules structurés</p>
				</div>
				<div>
					<p class="gac-stat-number text-white"><?php echo esc_html( $metrics['lessons'] ); ?></p>
					<p class="mt-2 text-sm text-slate-400">lessons normalisées</p>
				</div>
				<div>
					<a class="gac-button-secondary" href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>">Ouvrir le dashboard</a>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
