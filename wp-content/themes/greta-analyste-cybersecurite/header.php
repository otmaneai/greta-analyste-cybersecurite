<?php
$metrics = gac_theme_get_metrics();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'gac-shell antialiased' ); ?>>
<?php wp_body_open(); ?>
<header class="sticky top-0 z-40 border-b border-white/10 bg-[#0e1116]/90 backdrop-blur">
	<div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8" x-data="{ open: false }">
		<a class="text-sm font-black uppercase tracking-[0.16em] text-white" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			GRETA ANALYSTE CYBERSÉCURITÉ
		</a>
		<nav class="hidden items-center gap-6 text-sm text-slate-300 lg:flex">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'learning_path' ) ? get_post_type_archive_link( 'learning_path' ) : home_url( '/learning-paths/' ) ); ?>" class="transition hover:text-white">Learning Paths</a>
			<a href="<?php echo esc_url( home_url( '/resource-library/' ) ); ?>" class="transition hover:text-white">Ressources</a>
			<a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>" class="transition hover:text-white">Dashboard</a>
			<a href="<?php echo esc_url( home_url( '/#applied-stack' ) ); ?>" class="transition hover:text-white">Applied Stack</a>
		</nav>
		<button class="inline-flex rounded-md border border-white/15 px-3 py-2 text-sm font-semibold text-white lg:hidden" @click="open = ! open">
			Menu
		</button>
		<div class="absolute left-0 right-0 top-full border-b border-white/10 bg-[#111622] px-4 py-4 lg:hidden" x-show="open" x-transition>
			<div class="flex flex-col gap-3 text-sm text-slate-200">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'learning_path' ) ? get_post_type_archive_link( 'learning_path' ) : home_url( '/learning-paths/' ) ); ?>">Learning Paths</a>
				<a href="<?php echo esc_url( home_url( '/resource-library/' ) ); ?>">Ressources</a>
				<a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>">Dashboard</a>
				<a href="<?php echo esc_url( home_url( '/#applied-stack' ) ); ?>">Applied Stack</a>
			</div>
		</div>
	</div>
</header>
