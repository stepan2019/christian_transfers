<?php
/**
 * Template part for displaying quote.
 *
 * @package tecblogger
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>





<?php
	$thumb_id = get_post_thumbnail_id();
	$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'bg-thumb', false);
	$thumb_url = $thumb_url_array[0];
?>

	<div class="postFormatQuote">
		<div class="postFormatbgimage postFormatOverlay" style="background-image: url(<?php echo $thumb_url; ?>);"></div>

		<?php the_content();?>
		<cite>
			<span class="postFormatQuote-author">
				<span class="postFormatQuote-author-name">
					<strong><?php the_title(''); ?></strong>
				</span>
			</span>
		</cite>
	</div>










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
			<?php the_title( sprintf( '<h1 class="entry-title"> <a href="%s" rel="bookmark"> Quote by ', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

			<div class="entry-meta">
				<?php tecblogger_posted_on();?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<?php tecblogger_post_comments(); ?>
				<?php endif; ?>	
			</div><!-- .entry-meta -->	
		</header><!-- .entry-header -->
		<?php endif; ?>

	</div>
</article><!-- #post-## -->
