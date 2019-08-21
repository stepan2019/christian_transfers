<?php
/**
 * Template part for displaying aside.
 *
 * @package tecblogger
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="quote_area_height" style="margin-bottom:20px;display:block;overflow: hidden;"></div>	
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
<br />
		<div class="entry-meta">
			<?php tecblogger_posted_on();?>
			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<?php tecblogger_post_comments(); ?>
			<?php endif; ?>	
		</div><!-- .entry-meta -->	
	</header><!-- .entry-header -->
	<?php endif; ?>

	<div class="entry-content">
		<div class="post-aside">
			<?php the_content();?>			
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
</article><!-- #post-## -->
