<?php
$path = isset( $args['path'] ) ? $args['path'] : array();

if ( empty( $path ) ) {
	return;
}
?>
<a class="gac-card overflow-hidden transition hover:-translate-y-1" href="<?php echo esc_url( $path['url'] ); ?>">
	<div class="grid gap-0 md:grid-cols-[0.9fr_1.1fr]">
		<div class="min-h-[260px]">
			<img class="h-full w-full object-cover" src="<?php echo esc_url( $path['image'] ); ?>" alt="<?php echo esc_attr( $path['title'] ); ?>">
		</div>
		<div class="space-y-5 p-6">
			<div class="flex flex-wrap gap-2">
				<span class="gac-tag text-white"><?php echo esc_html( $path['level'] ); ?></span>
				<span class="gac-tag text-white"><?php echo esc_html( $path['status'] ); ?></span>
			</div>
			<div>
				<h3 class="text-2xl font-black text-white"><?php echo esc_html( $path['title'] ); ?></h3>
				<p class="mt-3 text-sm leading-7 text-slate-400"><?php echo esc_html( $path['strapline'] ); ?></p>
			</div>
			<p class="text-sm leading-7 text-slate-400"><?php echo esc_html( $path['description'] ); ?></p>
			<div class="flex gap-6 text-sm text-slate-300">
				<span><strong><?php echo esc_html( $path['resourceCount'] ); ?></strong> ressources</span>
				<span><?php echo esc_html( $path['estimated'] ); ?></span>
			</div>
		</div>
	</div>
</a>
