<?php
$resource = isset( $args['resource'] ) ? $args['resource'] : array();

if ( empty( $resource ) ) {
	return;
}
?>
<article class="gac-card resource-card p-6 transition hover:-translate-y-1">
	<div class="flex flex-wrap gap-2">
		<span class="gac-tag text-white"><?php echo esc_html( $resource['format'] ); ?></span>
		<span class="gac-tag text-white"><?php echo esc_html( $resource['status'] ); ?></span>
	</div>
	<h3 class="mt-5 text-2xl font-black text-white">
		<a class="transition hover:text-emerald-300" href="<?php echo esc_url( $resource['url'] ); ?>">
			<?php echo esc_html( $resource['title'] ); ?>
		</a>
	</h3>
	<p class="mt-4 text-sm leading-7 text-slate-400"><?php echo esc_html( $resource['summary'] ); ?></p>
	<div class="mt-6 flex flex-wrap gap-2">
		<?php foreach ( $resource['domains'] as $domain ) : ?>
			<span class="gac-tag text-white"><?php echo esc_html( $domain ); ?></span>
		<?php endforeach; ?>
	</div>
	<div class="mt-6 flex items-center justify-between text-sm text-slate-300">
		<span><strong><?php echo esc_html( $resource['pages'] ); ?></strong> pages</span>
		<span><?php echo esc_html( $resource['file'] ); ?></span>
	</div>
	<div class="mt-6 flex flex-wrap gap-3">
		<a class="gac-button-secondary" href="<?php echo esc_url( $resource['url'] ); ?>">Voir la fiche</a>
		<a class="gac-button-secondary" href="<?php echo esc_url( $resource['pdfUrl'] ); ?>" target="_blank" rel="noopener noreferrer">Ouvrir le PDF</a>
	</div>
</article>
