<?php
get_header();
?>
<main class="mx-auto max-w-5xl px-4 py-24 sm:px-6 lg:px-8">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="space-y-6">
				<p class="gac-section-label"><?php echo esc_html( get_post_type() ); ?></p>
				<h1 class="text-4xl font-black text-white"><?php the_title(); ?></h1>
				<div class="gac-prose prose prose-invert max-w-none">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	<?php endif; ?>
</main>
<?php
get_footer();
