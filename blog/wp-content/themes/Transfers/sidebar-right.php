<?php/** * The sidebar containing the right widget area. * * If no active widgets in sidebar, let's hide it completely. * * @package WordPress * @subpackage Transfers * @since Transfers 1.0 */ global $exclude_right_sidebar_wrap;?><?php if ( is_active_sidebar( 'right' ) ) { ?>	<?php if ($exclude_right_sidebar_wrap) {		dynamic_sidebar( 'right' );	} else { ?>	<aside id="right-sidebar" class="right-sidebar one-fourth sidebar right widget-area" role="complementary">		<ul>		<?php dynamic_sidebar( 'right' ); ?>		</ul>	</aside><!-- #secondary -->	<?php } ?>	<?php }