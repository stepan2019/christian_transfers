<?php
/**
 * The template for displaying all single posts.
 *
 * @package tecblogger
 */

get_header(); ?>
<div class="content-area">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'template-parts/content', 'single' ); ?>

						<div class="clearfix post-navigation">
							<?php previous_post_link('<span class="previous-post pull-left">%link</span>','<i class="fa fa-long-arrow-left"></i> '.esc_html__("previous article",'tecblogger')); ?>
							<?php next_post_link('<span class="next-post pull-right">%link</span>',esc_html__("next article",'tecblogger').' <i class="fa fa-long-arrow-right"></i>'); ?>
						</div> <!-- .post-navigation -->

						<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						?>

					<?php endwhile; // End of the loop. ?>

					</main><!-- #main -->
				</div><!-- #primary -->
			</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
