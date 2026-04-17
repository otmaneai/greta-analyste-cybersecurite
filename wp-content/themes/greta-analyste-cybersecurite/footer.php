<?php
$stack = gac_theme_get_stack();
?>
<footer class="border-t border-white/10 bg-black/20">
	<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
		<div class="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
			<div class="space-y-3">
				<p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-300">Built with the course stack</p>
				<h2 class="text-2xl font-black text-white">Le produit lui-même sert de preuve de pratique.</h2>
				<p class="max-w-3xl text-sm leading-7 text-slate-400">
					GRETA ANALYSTE CYBERSÉCURITÉ organise les supports du parcours et assume clairement son implémentation :
					WordPress, PHP, MariaDB, Apache2, Tailwind CSS, Alpine.js et HTML.
				</p>
			</div>
			<div class="flex flex-wrap gap-3">
				<?php foreach ( $stack as $item ) : ?>
					<span class="gac-tag text-slate-100"><?php echo esc_html( $item['name'] ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
