<?php
/**
 * Template part for displaying single posts.
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



<?php 
if(has_post_format('standard')){?>
	<div class="post-thumbnails">
		<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail(); ?>
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
		</div><!-- End Categories -->
		
		
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">
				<?php tecblogger_posted_on();?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<?php tecblogger_post_comments(); ?>
				<?php endif; ?>	
			</div><!-- .entry-meta -->	
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tecblogger' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php tecblogger_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		
		<div class="post-author">
			<div class="row">
				<div class="col-xs-2">
					<div class="author-img">
						<?php echo get_avatar( get_the_author_meta('email'), '100' ); ?>
					</div>
				</div>
				<div class="col-xs-10">
					<div class="author-content">
						<h2 class="author-name-headding"><?php the_author_posts_link(); ?></h2>
						<p><?php the_author_meta('description'); ?></p>
					</div>
				</div>
			</div>
		</div><!-- End Author M -->
	</div>

<?php
}
elseif ( has_post_format( 'quote' )) {?>

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
		</div><!-- End Categories -->
		
		
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">
				<?php tecblogger_posted_on();?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<?php tecblogger_post_comments(); ?>
				<?php endif; ?>	
			</div><!-- .entry-meta -->	
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tecblogger' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php tecblogger_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		
		<div class="post-author">
			<div class="row">
				<div class="col-xs-2">
					<div class="author-img">
						<?php echo get_avatar( get_the_author_meta('email'), '100' ); ?>
					</div>
				</div>
				<div class="col-xs-10">
					<div class="author-content">
						<h2 class="author-name-headding"><?php the_author_posts_link(); ?></h2>
						<p><?php the_author_meta('description'); ?></p>
					</div>
				</div>
			</div>
		</div><!-- End Author M -->
	</div>


<?php
}
elseif ( has_post_format( 'gallery' )) {?>

    <div class="content-featured">
        <div data-ride="carousel" class="carousel slide" id="gallery-carousel<?php echo get_the_ID(); ?>">
            <div role="listbox" class="carousel-inner">
                <?php                
                if ( get_post_gallery() ) :
                $gallery = get_post_gallery( get_the_ID(), false );
                $ids = explode( ",", $gallery['ids'] );
                $active_counter = 1;
                foreach( $ids as $id ) :
                    $link   = wp_get_attachment_url( $id );
                ?>
                <div class="item <?php if($active_counter == 1){echo "active";} ?>">
                    <img alt="" src="<?php echo $link; ?>">
                </div>
                <?php $active_counter++; endforeach;endif; ?>                
            </div>

            <a data-slide="prev" role="button" href="#gallery-carousel<?php echo get_the_ID(); ?>" class="left gallery-control"><i class="fa fa-angle-left"></i></a>
            <a data-slide="next" role="button" href="#gallery-carousel<?php echo get_the_ID(); ?>" class="right gallery-control"><i class="fa fa-angle-right"></i></a>
        </div>      
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
		</div><!-- End Categories -->
		
		
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">
				<?php tecblogger_posted_on();?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<?php tecblogger_post_comments(); ?>
				<?php endif; ?>	
			</div><!-- .entry-meta -->	
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tecblogger' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php tecblogger_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		
		<div class="post-author">
			<div class="row">
				<div class="col-xs-2">
					<div class="author-img">
						<?php echo get_avatar( get_the_author_meta('email'), '100' ); ?>
					</div>
				</div>
				<div class="col-xs-10">
					<div class="author-content">
						<h2 class="author-name-headding"><?php the_author_posts_link(); ?></h2>
						<p><?php the_author_meta('description'); ?></p>
					</div>
				</div>
			</div>
		</div><!-- End Author M -->
	</div>






<?php
}
else{?>


	<div class="post-thumbnails">
		<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail(); ?>
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
		</div><!-- End Categories -->
		
		
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">
				<?php tecblogger_posted_on();?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<?php tecblogger_post_comments(); ?>
				<?php endif; ?>	
			</div><!-- .entry-meta -->	
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tecblogger' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php tecblogger_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		
		<div class="post-author">
			<div class="row">
				<div class="col-xs-2">
					<div class="author-img">
						<?php echo get_avatar( get_the_author_meta('email'), '100' ); ?>
					</div>
				</div>
				<div class="col-xs-10">
					<div class="author-content">
						<h2 class="author-name-headding"><?php the_author_posts_link(); ?></h2>
						<p><?php the_author_meta('description'); ?></p>
					</div>
				</div>
			</div>
		</div><!-- End Author M -->
	</div>



<?php
}
?>

</article><!-- #post-## -->

