<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tecblogger
 */

?>









<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<div class="post-thumbnails">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail(); ?>
			</a>
		<?php endif; ?>
	</div><!-- End Post Thumbnail -->

	<div class="post-content-container">
	
		<div class="content-categories">
			<ul class="post-categories">
			<?php
				foreach(get_the_category(get_the_ID()) as $cd) {
				if(get_the_category(get_the_ID()) !=null) { ?>
					<li><a href="<?php echo get_category_link( $cd->term_id ); ?>"><?php echo $cd->cat_name; ?></a></li>
					<?php
					}
				}
				?>
			</ul>
		</div>

		<?php if ( 'post' == get_post_type() ) : ?>
		
		
		<header class="entry-header">
			<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

			<div class="entry-meta">
				<?php tecblogger_posted_on();?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<?php tecblogger_post_comments(); ?>
				<?php endif; ?>	
			</div><!-- .entry-meta -->	
		</header><!-- .entry-header -->
		<?php endif; ?>


		<div class="entry-content">
		
			<div class="tech_standard_excerpt">
				<?php the_excerpt(); ?>
			</div>

			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tecblogger' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
		<footer class="entry-footer">

			<?php tecblogger_entry_footer(); ?>

			<?php edit_post_link( __( 'Edit', 'tecblogger' ), '<span class="edit-link">', '</span>' ); ?>		
		</footer><!-- .entry-footer -->
		
		
		<?php endif; // End if 'post' == get_post_type() ?>

	</div>
</article><!-- #post-## -->


