<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
//        if ( is_singular() ) :
//            the_title( '<h1 class="entry-title">', '</h1>' );
//        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
//        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php
                custom_posted_on();
                custom_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php custom_post_thumbnail(); ?>

    <div class="entry-summary">
        <?php
        the_excerpt();

//        wp_link_pages(
//            array(
//                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'custom' ),
//                'after'  => '</div>',
//            )
//        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
<!--        --><?php //custom_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
