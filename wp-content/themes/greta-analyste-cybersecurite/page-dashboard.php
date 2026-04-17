<?php
/**
 * Template Name: Dashboard
 */

get_header();

$metrics       = gac_theme_get_metrics();
$paths         = gac_theme_get_paths();
$resources     = gac_theme_get_resources();
$stack         = gac_theme_get_stack();
$status_counts = gac_theme_get_status_counts( $resources );
$domain_counts = gac_theme_get_domain_counts( $resources );
$path_rows     = gac_theme_get_path_health_rows( $paths, $resources );
$variants      = gac_theme_get_resource_variants( $resources );
$canonical     = isset( $status_counts['canonical'] ) ? $status_counts['canonical'] : 0;
$secondary     = count( $resources ) - $canonical;
?>
<main class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
	<div class="max-w-3xl space-y-4">
		<p class="gac-section-label">Dashboard</p>
		<h1 class="text-5xl font-black text-white">Vue d'ensemble du corpus et du produit.</h1>
		<p class="text-base leading-8 text-slate-400">
			Le dashboard synthétise le volume documentaire, la structure pédagogique et la matière directement utile pour le
			projet vitrine.
		</p>
	</div>

	<div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-6">
		<div class="gac-card p-6">
			<p class="gac-stat-number text-emerald-300"><?php echo esc_html( $metrics['paths'] ); ?></p>
			<p class="mt-2 text-sm text-slate-400">learning paths</p>
		</div>
		<div class="gac-card p-6">
			<p class="gac-stat-number text-sky-300"><?php echo esc_html( $metrics['modules'] ); ?></p>
			<p class="mt-2 text-sm text-slate-400">modules</p>
		</div>
		<div class="gac-card p-6">
			<p class="gac-stat-number text-fuchsia-300"><?php echo esc_html( $metrics['lessons'] ); ?></p>
			<p class="mt-2 text-sm text-slate-400">lessons</p>
		</div>
		<div class="gac-card p-6">
			<p class="gac-stat-number text-amber-300"><?php echo esc_html( $metrics['resources'] ); ?></p>
			<p class="mt-2 text-sm text-slate-400">ressources PDF</p>
		</div>
		<div class="gac-card p-6">
			<p class="gac-stat-number text-rose-300"><?php echo esc_html( $canonical ); ?></p>
			<p class="mt-2 text-sm text-slate-400">supports canoniques</p>
		</div>
		<div class="gac-card p-6">
			<p class="gac-stat-number text-white"><?php echo esc_html( $secondary ); ?></p>
			<p class="mt-2 text-sm text-slate-400">variantes / compagnons</p>
		</div>
	</div>

	<div class="mt-10 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
		<div class="gac-card p-6">
			<p class="gac-section-label">Applied stack posture</p>
			<h2 class="mt-3 text-2xl font-black text-white">La stack n'est pas un détail technique, c'est le sujet.</h2>
			<p class="mt-4 text-sm leading-7 text-slate-300">
				Le dashboard rappelle que la plateforme organise la matière GRETA tout en servant de preuve d'application.
				Les briques de cours sont visibles dans l'interface, reliées aux parcours, puis reprises dans l'architecture WordPress.
			</p>
			<div class="mt-6 grid gap-4 md:grid-cols-2">
				<?php foreach ( $stack as $item ) : ?>
					<div class="rounded-lg border border-white/10 bg-white/5 p-4">
						<p class="text-sm font-bold text-white"><?php echo esc_html( $item['name'] ); ?></p>
						<p class="mt-2 text-sm leading-7 text-slate-400"><?php echo esc_html( $item['role'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="gac-card p-6">
			<p class="gac-section-label">Corpus states</p>
			<h2 class="mt-3 text-2xl font-black text-white">Qualité de la bibliothèque source.</h2>
			<div class="mt-6 space-y-3">
				<?php foreach ( $status_counts as $status => $count ) : ?>
					<div class="flex items-center justify-between rounded-lg border border-white/10 bg-white/5 px-4 py-3">
						<span class="text-sm font-semibold text-slate-200"><?php echo esc_html( gac_theme_status_label( $status ) ); ?></span>
						<span class="gac-tag text-white"><?php echo esc_html( $count ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
			<p class="mt-6 text-sm leading-7 text-slate-400">
				Les variantes et supports compagnons restent visibles pour garder la traçabilité documentaire et signaler les versions proches.
			</p>
		</div>
	</div>

	<section class="mt-10 grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
		<div class="gac-card p-6">
			<p class="gac-section-label">Coverage by domain</p>
			<h2 class="mt-3 text-2xl font-black text-white">Les thèmes réellement couverts par le corpus.</h2>
			<div class="mt-6 space-y-3">
				<?php foreach ( array_slice( $domain_counts, 0, 8 ) as $row ) : ?>
					<div class="rounded-lg border border-white/10 bg-white/5 px-4 py-4">
						<div class="flex items-center justify-between gap-4">
							<p class="text-sm font-bold uppercase tracking-[0.14em] text-white"><?php echo esc_html( $row['domain'] ); ?></p>
							<span class="text-xs text-slate-400"><?php echo esc_html( $row['resourceCount'] ); ?> ressources</span>
						</div>
						<p class="mt-2 text-sm text-slate-400"><?php echo esc_html( $row['pathCount'] ); ?> parcours reliés</p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="gac-card p-6">
			<p class="gac-section-label">Priority tracks</p>
			<h2 class="mt-3 text-2xl font-black text-white">Axe portfolio assumé pour la démo publique.</h2>
			<ul class="mt-6 space-y-4 text-sm leading-7 text-slate-300">
				<li>PHP, parce que la plateforme doit assumer le langage dans sa propre implémentation.</li>
				<li>WordPress, parce que c'est le socle CMS demandé et visible dans le produit.</li>
				<li>Apache2 et MariaDB, parce qu'ils matérialisent la chaîne de déploiement étudiée.</li>
				<li>Ressource library et dashboard, parce que la matière source doit rester lisible et exploitable.</li>
			</ul>
		</div>
	</section>

	<section class="mt-10">
		<div class="mb-6 max-w-3xl space-y-3">
			<p class="gac-section-label">Path health</p>
			<h2 class="text-3xl font-black text-white">Lecture opérationnelle des parcours.</h2>
		</div>
		<div class="grid gap-4">
			<?php foreach ( $path_rows as $row ) : ?>
				<div class="gac-card p-5">
					<div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
						<div class="space-y-3">
							<div class="flex flex-wrap gap-2">
								<span class="gac-tag text-white"><?php echo esc_html( $row['level'] ); ?></span>
								<span class="gac-tag text-white"><?php echo esc_html( $row['statusLabel'] ); ?></span>
								<span class="gac-tag text-white"><?php echo esc_html( $row['estimatedTime'] ); ?></span>
							</div>
							<div>
								<h3 class="text-xl font-bold text-white">
									<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['title'] ); ?></a>
								</h3>
								<p class="mt-3 text-sm leading-7 text-slate-400">
									<?php echo esc_html( $row['moduleCount'] ); ?> modules, <?php echo esc_html( $row['lessonCount'] ); ?> leçons, <?php echo esc_html( $row['resourceCount'] ); ?> ressources liées.
								</p>
							</div>
						</div>
						<div class="grid min-w-[220px] gap-3 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
							<div class="rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
								<p class="text-xs uppercase tracking-[0.14em] text-slate-500">Canoniques</p>
								<p class="mt-2 text-lg font-bold text-white"><?php echo esc_html( $row['canonicalCount'] ); ?></p>
							</div>
							<div class="rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
								<p class="text-xs uppercase tracking-[0.14em] text-slate-500">Support</p>
								<p class="mt-2 text-lg font-bold text-white"><?php echo esc_html( $row['supportCount'] ); ?></p>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="mt-10">
		<div class="mb-6 max-w-3xl space-y-3">
			<p class="gac-section-label">Flagged variants</p>
			<h2 class="text-3xl font-black text-white">Versions alternatives et supports transverses conservés dans le catalogue.</h2>
		</div>
		<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
			<?php foreach ( $variants as $variant ) : ?>
				<div class="gac-card p-5">
					<div class="flex flex-wrap gap-2">
						<span class="gac-tag text-white"><?php echo esc_html( $variant['statusLabel'] ); ?></span>
						<?php foreach ( array_slice( $variant['domains'], 0, 2 ) as $domain ) : ?>
							<span class="gac-tag text-white"><?php echo esc_html( $domain ); ?></span>
						<?php endforeach; ?>
					</div>
					<h3 class="mt-4 text-xl font-bold text-white">
						<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $variant['url'] ); ?>"><?php echo esc_html( $variant['title'] ); ?></a>
					</h3>
					<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $variant['summary'] ); ?></p>
					<p class="mt-4 text-xs text-slate-500"><?php echo esc_html( $variant['file'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
</main>
<?php
get_footer();
