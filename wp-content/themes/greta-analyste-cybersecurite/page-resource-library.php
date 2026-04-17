<?php
/**
 * Template Name: Resource Library
 */

get_header();

$resource_cards = array();

foreach ( gac_theme_get_resources() as $resource ) {
	$resource_cards[] = gac_theme_build_resource_card( $resource );
}

$resource_payload = array();
foreach ( $resource_cards as $card ) {
	$resource_payload[] = array(
		'title'   => $card['title'],
		'summary' => $card['summary'],
		'format'  => $card['format'],
		'pages'   => $card['pages'],
		'status'  => $card['status'],
		'domains' => $card['domains'],
		'url'     => $card['url'],
		'pdfUrl'  => $card['pdfUrl'],
		'file'    => $card['file'],
	);
}
?>
<main class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
	<div class="max-w-3xl space-y-4">
		<p class="gac-section-label">Resource library</p>
		<h1 class="text-5xl font-black text-white">Bibliothèque des supports GRETA</h1>
		<p class="text-base leading-8 text-slate-400">
			Une base documentaire orientée révision et exploitation : les supports sont triables par domaine, statut et usage.
		</p>
	</div>

	<div class="mt-10" x-data='gacResourceLibrary(<?php echo gac_theme_json_attr( $resource_payload ); ?>)'>
		<div class="grid gap-4 lg:grid-cols-[1fr_auto_auto_auto]">
			<input x-model="query" class="rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-slate-500" type="search" placeholder="Rechercher un support, un thème, un format">
			<div class="flex flex-wrap gap-3">
				<button class="gac-tag text-white" type="button" @click="domain = 'all'">Tous</button>
				<template x-for="available in domains" :key="available">
					<button class="gac-tag text-white" type="button" @click="domain = available" x-text="available"></button>
				</template>
			</div>
			<select x-model="status" class="rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white">
				<option value="all">Tous les statuts</option>
				<template x-for="available in statuses" :key="available">
					<option :value="available" x-text="available"></option>
				</template>
			</select>
			<select x-model="format" class="rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white">
				<option value="all">Tous les formats</option>
				<template x-for="available in formats" :key="available">
					<option :value="available" x-text="available"></option>
				</template>
			</select>
		</div>
		<p class="mt-4 text-sm text-slate-400"><strong x-text="filtered.length"></strong> ressources affichées</p>

		<div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
			<template x-for="resource in filtered" :key="resource.title">
				<article class="gac-card resource-card p-6 transition hover:-translate-y-1">
					<div class="flex flex-wrap gap-2">
						<span class="gac-tag text-white" x-text="resource.format"></span>
						<span class="gac-tag text-white" x-text="resource.status"></span>
					</div>
					<h2 class="mt-5 text-2xl font-black text-white">
						<a class="transition hover:text-emerald-300" :href="resource.url" x-text="resource.title"></a>
					</h2>
					<p class="mt-4 text-sm leading-7 text-slate-400" x-text="resource.summary"></p>
					<div class="mt-6 flex flex-wrap gap-2 text-xs text-slate-300">
						<template x-for="domainTag in resource.domains" :key="domainTag">
							<span class="gac-tag" x-text="domainTag"></span>
						</template>
					</div>
					<div class="mt-6 flex items-center justify-between text-sm text-slate-300">
						<span><strong x-text="resource.pages"></strong> pages</span>
						<span x-text="resource.file"></span>
					</div>
					<div class="mt-6 flex flex-wrap gap-3">
						<a class="gac-button-secondary" :href="resource.url">Voir la fiche</a>
						<a class="gac-button-secondary" :href="resource.pdfUrl" target="_blank" rel="noopener noreferrer">Ouvrir le PDF</a>
					</div>
				</article>
			</template>
		</div>
	</div>
</main>
<?php
get_footer();
