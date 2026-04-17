<?php
get_header();

$cards = array();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$slug = get_post_field( 'post_name', get_the_ID() );
		$path = gac_theme_get_path_by_slug( $slug );

		if ( $path ) {
			$cards[] = gac_theme_build_path_card( $path );
		}
	}
	wp_reset_postdata();
}

if ( empty( $cards ) ) {
	foreach ( gac_theme_get_paths() as $path ) {
		$cards[] = gac_theme_build_path_card( $path );
	}
}

$archive_payload = array();
foreach ( $cards as $card ) {
	$archive_payload[] = array(
		'title'        => $card['title'],
		'strapline'    => $card['strapline'],
		'level'        => $card['level'],
		'status'       => $card['status'],
		'estimated'    => $card['estimated'],
		'resourceCount'=> $card['resourceCount'],
		'image'        => $card['image'],
		'url'          => $card['url'],
	);
}
?>
<main class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
	<div class="max-w-3xl space-y-4">
		<p class="gac-section-label">Learning paths archive</p>
		<h1 class="text-5xl font-black text-white">Parcours GRETA normalisés</h1>
		<p class="text-base leading-8 text-slate-400">
			Chaque piste transforme une partie du corpus source en progression lisible. Les cartes ci-dessous assument le lien
			entre le sujet étudié et la technologie réellement mobilisée dans le produit.
		</p>
	</div>

	<div class="mt-10" x-data='gacPathArchive(<?php echo gac_theme_json_attr( $archive_payload ); ?>)'>
		<div class="mb-8 flex flex-wrap gap-3">
			<button class="gac-tag text-white" type="button" @click="level = 'all'">Tous</button>
			<template x-for="available in levels" :key="available">
				<button class="gac-tag text-white" type="button" @click="level = available" x-text="available"></button>
			</template>
		</div>
		<div class="grid gap-6 lg:grid-cols-2">
			<template x-for="path in filtered" :key="path.url">
				<a class="gac-card overflow-hidden transition hover:-translate-y-1" :href="path.url">
					<div class="grid gap-0 md:grid-cols-[0.9fr_1.1fr]">
						<div class="min-h-[240px]">
							<img class="h-full w-full object-cover" :src="path.image" :alt="path.title">
						</div>
						<div class="space-y-5 p-6">
							<div class="flex flex-wrap gap-2">
								<span class="gac-tag text-slate-100" x-text="path.level"></span>
								<span class="gac-tag text-slate-100" x-text="path.status"></span>
							</div>
							<div>
								<h2 class="text-2xl font-black text-white" x-text="path.title"></h2>
								<p class="mt-3 text-sm leading-7 text-slate-400" x-text="path.strapline"></p>
							</div>
							<div class="flex gap-6 text-sm text-slate-300">
								<span><strong x-text="path.resourceCount"></strong> ressources</span>
								<span x-text="path.estimated"></span>
							</div>
						</div>
					</div>
				</a>
			</template>
		</div>
	</div>
</main>
<?php
get_footer();
