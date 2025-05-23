<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Custom
 */

?>

	<footer id="colophon" class="site-footer">
        <hr class="hr-separator">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'custom' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'custom' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'custom' ), 'custom', '<a href="http://underscores.me/">Underscores.me</a>' );
				?>
            <hr class="hr-separator">
            <?php
                $tagline = get_bloginfo('description');
                if ($tagline) : ?>
                    <div id="tagline">
                        <?= $tagline ?>
                    </div>
                    <hr class="hr-separator">
            <?php endif; ?>
            <div></div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
