<?php
get_header();

the_post();

$slug          = get_post_field( 'post_name', get_the_ID() );
$resource      = get_post_meta( get_the_ID(), 'gac_resource_payload', true );
$resource      = is_array( $resource ) ? $resource : gac_catalog_find_resource( $slug );
$resource_card = $resource ? gac_theme_build_resource_card( $resource ) : null;
$related_paths = $resource ? gac_theme_build_path_cards_from_slugs( $resource['relatedPaths'] ) : array();
?>
<main>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
			<div class="space-y-5">
				<p class="gac-section-label">Resource detail</p>
				<h1 class="text-5xl font-black text-white"><?php the_title(); ?></h1>
				<?php if ( $resource_card ) : ?>
					<div class="flex flex-wrap gap-3">
						<span class="gac-tag text-white"><?php echo esc_html( $resource_card['format'] ); ?></span>
						<span class="gac-tag text-white"><?php echo esc_html( $resource_card['status'] ); ?></span>
						<span class="gac-tag text-white"><?php echo esc_html( $resource_card['pages'] ); ?> pages</span>
					</div>
					<p class="max-w-3xl text-lg leading-8 text-slate-300"><?php echo esc_html( $resource_card['summary'] ); ?></p>
					<div class="flex flex-wrap gap-4">
						<a class="gac-button-primary" href="<?php echo esc_url( $resource_card['pdfUrl'] ); ?>" target="_blank" rel="noopener noreferrer">Ouvrir le PDF</a>
						<a class="gac-button-secondary" href="<?php echo esc_url( home_url( '/resource-library/' ) ); ?>">Retour à la bibliothèque</a>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $resource_card ) : ?>
				<div class="gac-card p-6">
					<p class="gac-section-label">Fichier source</p>
					<div class="mt-4 space-y-4 text-sm text-slate-300">
						<p><strong class="text-white">Nom :</strong> <?php echo esc_html( $resource_card['file'] ); ?></p>
						<p><strong class="text-white">Origine :</strong> <code>pdfs_context/</code> intégré au projet.</p>
						<p><strong class="text-white">Usage :</strong> support de révision, preuve de contenu source et brique documentaire du parcours.</p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $resource_card ) : ?>
	<section class="border-b border-white/10 py-16">
		<div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8">
			<div class="space-y-4">
				<p class="gac-section-label">Couverture thématique</p>
				<h2 class="text-4xl font-black text-white">Domaines et usage dans la plateforme.</h2>
				<div class="flex flex-wrap gap-2">
					<?php foreach ( $resource_card['domains'] as $domain ) : ?>
						<span class="gac-tag text-white"><?php echo esc_html( $domain ); ?></span>
					<?php endforeach; ?>
				</div>
				<p class="text-base leading-8 text-slate-400">
					Ce support n'est pas traité comme un simple fichier joint. Il sert à documenter un ou plusieurs parcours,
					à donner du contexte aux leçons, et à rendre la matière source consultable depuis le produit lui-même.
				</p>
			</div>
			<div class="gac-card p-6">
				<p class="gac-section-label">Pourquoi il compte</p>
				<ul class="mt-4 space-y-3 text-sm leading-7 text-slate-300">
					<li>Il matérialise le lien entre contenu de formation et architecture produit.</li>
					<li>Il sert de support de révision directement relié à un parcours.</li>
					<li>Il renforce la crédibilité portfolio du projet en montrant la matière réelle utilisée.</li>
				</ul>
			</div>
		</div>
	</section>

	<section class="py-16">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="mb-8 max-w-3xl space-y-4">
				<p class="gac-section-label">Linked learning paths</p>
				<h2 class="text-4xl font-black text-white">Parcours reliés à cette ressource.</h2>
			</div>
			<div class="grid gap-6 lg:grid-cols-2">
				<?php foreach ( $related_paths as $path_card ) : ?>
					<?php get_template_part( 'template-parts/path-card', null, array( 'path' => $path_card ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
</main>
<?php
get_footer();
