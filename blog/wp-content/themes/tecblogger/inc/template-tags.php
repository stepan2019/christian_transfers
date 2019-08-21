<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package tecblogger
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
            <nav class="next-previous-post clearfix" role="navigation">
                <div class="next-previous-post clearfix">
                    <!-- Previous Post -->
                    <div class="previous-post pull-left">
                        <?php previous_post_link('<div class="nav-previous">%link</div>', __('<i class="fa fa-angle-left"></i> Previous Post', 'tecblogger')); ?>
                    </div>

                    <!-- Next Post -->
                    <div class="next-post pull-right text-right">
                        <?php next_post_link('<div class="nav-next">%link</div>', __('Next Post <i class="fa fa-angle-right"></i>', 'tecblogger')); ?>
                    </div>
                </div>
            </nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'tecblogger' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;





//----------------------------------------------------------------------
//  Tecblogger Pagination
//----------------------------------------------------------------------

if (!function_exists('tecblogger_posts_pagination')) {
	function tecblogger_posts_pagination()
	{
		global $wp_query;
		if ($wp_query->max_num_pages > 1) {
			$big   = 999999999; // need an unlikely integer
			$items = paginate_links(array(
				'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format'    => '?paged=%#%',
				'prev_next' => TRUE,
				'current'   => max(1, get_query_var('paged')),
				'total'     => $wp_query->max_num_pages,
				'type'      => 'array',
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'next_text' => '<i class="fa fa-angle-right"></i>'
			));

			$pagination = "<nav id=\"tecblogger_pagination\" class=\"tecblogger_pagination\">";
			$pagination .= join("", $items);
			$pagination . "</nav>\n";

			echo $pagination;
		}
		return;
	}
}








if ( ! function_exists( 'tecblogger_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function tecblogger_posted_on() {
	?>
	<?php _e('By ', 'tecblogger');
		printf('<a class="url fn n" href="%1$s">%2$s</a>',
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			esc_html(get_the_author())
		) ?>
		<span class="entry-author-time"><i class="fa fa-clock-o"></i> <?php the_time('j M,  Y'); ?></span>
	<?php
}
endif;

if ( ! function_exists( 'tecblogger_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function tecblogger_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ' ', 'tecblogger' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( '%1$s', 'tecblogger' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'tecblogger' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'tecblogger' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'tecblogger' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'tecblogger' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'tecblogger' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'tecblogger' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'tecblogger' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'tecblogger' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'tecblogger' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'tecblogger' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'tecblogger' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'tecblogger' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'tecblogger' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'tecblogger' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;


function tecblogger_post_comments() {
?>
	<span class="entry-author-comments">
		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
			  <i class="fa fa-comments"></i><?php comments_popup_link( __( 'Leave a comment', 'tecblogger' ), __( '1 Comment', 'tecblogger' ), __( '% Comments', 'tecblogger' ) ); ?>
		<?php endif; //.comment-link ?>
	</span>

<?php
}


function tecblogger_post_thumbnail() {

	// Post password check
	if ( post_password_required() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<figure class="post-thumbnail post-thumbnail-single">
		<?php the_post_thumbnail( 'blog-thumbnail', array( 'class' => 'img-featured img-responsive' ) ); ?>
	</figure><!-- .post-thumbnail -->

	<?php else : ?>

	<figure class="post-thumbnail">
		<a href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail( 'blog-thumbnail', array( 'class' => 'img-featured img-responsive' ) ); ?>
		</a>
	</figure><!-- .post-thumbnail -->

	<?php endif; // End is_singular()
}


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function tecblogger_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'tecblogger_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'tecblogger_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so tecblogger_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so tecblogger_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in tecblogger_categorized_blog.
 */
function tecblogger_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'tecblogger_categories' );
}
add_action( 'edit_category', 'tecblogger_category_transient_flusher' );
add_action( 'save_post',     'tecblogger_category_transient_flusher' );
