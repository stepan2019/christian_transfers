<?php
/**
 * The template for displaying search results pages.
 *
 * @package tecblogger
 */

get_header(); ?>
<div class="content-area">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<section id="primary" class="content-area">
					<main id="main" class="site-main" role="main">

					<?php if ( have_posts() ) : ?>


						<div class="tech_page_header">
							<header class="page-header">
								<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'tecblogger' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
							</header><!-- .page-header -->
						</div>


						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'search' );
							?>

						<?php endwhile; ?>

						<?php the_posts_navigation(); ?>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content', 'none' ); ?>

					<?php endif; ?>

					</main><!-- #main -->
				</section><!-- #primary -->
			</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
