<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content
 *
 * @package WordPress
 * @subpackage Transfers
 * @since Transfers 1.0
 */
global $transfers_theme_globals;
?>
	</main>
	<?php get_sidebar('above-footer'); ?>
	<!-- //Main -->
	<!-- Footer -->
	<footer class="footer black" role="contentinfo">
		<div class="wrap">
			<div class="rowfooter">
				<!-- Column -->
				<article class="one-fourth">
					<h6>About Us</h6>
                        <ul class="contact-data">
                            <li><a href="/timetable" alt="timetable christian transfers Europe">Time Tables</a></li>
                            <li><a href="/about-christian-transfers" alt="About Christian Transfers Europe">About Christian Transfers</a></li>
                            <li><a href="/partners" alt="Become a partners of Christian Transfers Europe">Partners</a></li>
                        </ul>
				</article>
				<!-- //Column -->
				
				<!-- Column -->
				<article class="one-fourth">
					<h6>Private</h6>
					    <ul class="contact-data">
                            <li><a href="/taxi" alt="taxi gatwick london">Taxi</a></li>
							<li><a href="/couriers" alt="couriers gatwick london">Couriers and Vans</a></li>
							<li><a href="/recovery-car" alt="recovery cars gatwick london">Recovery Car</a></li>                                            
                        </ul>
				</article>
				<!-- //Column -->
				
				<!-- Column -->
				<article class="one-fourth">
					<h6>Need Help?</h6>
					    <ul class="contact-data">
					        <li><a href="/help" alt="Help for customers with Airport Transfers">Customer Help</a></li>
                            <li><a href="/system-of-bookings" alt="System of reservations for Christian Transfers Europe">Bookings System Help</a></li>
                            <li><a href="/terms-conditions" title="Terms & Conditions of Christian Transfers Europe">Terms & Conditions</a></li>
                            <li><a href="/privacy-policy" title="Privacy Policy of Christian Transfers Europe">Privacy Policy</a></li>
                        </ul>
				</article>
				<!-- //Column -->
				
				<!-- Column -->
				<article class="one-fourth">
					<h6>Our websites</h6>
					    <ul class="contact-data">
                            <li><a href="http://www.christiantransfers.co.uk/" target="_blank" alt="christian transfers uk">Christian Transfers Uk</a></li>
                            <li><a href="http://www.christiantransfers.ro/" target="_blank" alt="christian transfers romania">Christian Transfers Romania</a></li>
                            <li><a href="http://www.mayamarket.co.uk/" target="_blank" alt="your adds website maya market">Maya Market</a></li>
					    </ul>
				</article>
				<!-- //Column -->
			</div>
                    
                    <div class="rowfooter">
                        <article class="one-half">
                            <!--<h6>Our payment system</h6>
                            <p>
                                <img height="40" width="52" title="Jcb card payments in GBP" alt="jcb logo" src="/images/zcards/jcb.gif">
                                <img height="40" width="63" title="Maestro card payments in GBP" alt="maestro logo" src="/images/zcards/maestro 1.gif">
                                <img height="40" width="67" title="Mastercard card payments in GBP" alt="mastercard logo" src="/images/zcards/mastercard.png">
                                <img height="40" width="94" title="Visa card payments in GBP" alt="visa logo" src="/images/zcards/visa.png">
                                <img height="40" width="50" title="Worldpay card payments" alt="worldpay logo" src="/images/zcards/worldpay logo 1.png">
                                <img height="40" width="50" title="We accept bank transfers in GBP" alt="metro bank logo" src="/images/zcards/metro bank 1.png">
                                <img height="40" width="68" title="We accept bank transfers in GBP" alt="hsbc logo" src="/images/zcards/hsbc.png">
                            </p>-->
                        </article>
                        
                        <article class="one-fourth">
                            <h6>Follow us</h6>
                            <ul class="social"><li><a href="http://www.facebook.com/ChristianTransfers?sk=wall" title="facebook"><i class="fa fa-fw fa-facebook"></i></a></li>
                                <li><a href="https://www.twitter.com/christiantransf" title="twitter"><i class="fa fa-fw fa-twitter"></i></a></li>
                                <li><a href="https://plus.google.com/+ChristianTransfersEu/posts" title="gplus"><i class="fa fa-fw fa-google-plus"></i></a></li>
                                <li><a href="https://www.linkedin.com/company/christian-transfers/" title="linkedin"><i class="fa fa-fw fa-linkedin"></i></a></li>
                                <li><a href="https://www.pinterest.co.uk/christiantransfers/" title="pinterest"><i class="fa fa-fw fa-pinterest-p"></i></a></li></ul>

                        </article>
                        
                    </div>
			
			<div class="copy">
              <p>Copyright {$year}, <a href="http://{$sitename_footer}">{$sitename_footer}</a>. All rights reserved. </p>
				
				<nav role="navigation" class="foot-nav">
                <!--<ul>
						<li><a href="#" title="Home">Home</a></li>
						<li><a href="#" title="Blog">Blog</a></li>
						<li><a href="#" title="About us">About us</a></li>
						<li><a href="#" title="Contact us">Contact us</a></li>
						<li><a href="#" title="Terms of use">Terms of use</a></li>
						<li><a href="#" title="Help">Help</a></li>
						<li><a href="#" title="For partners">For partners</a></li>
					</ul>-->
				</nav>
			</div>
		</div>
	</footer>
	<!-- //Footer -->
	
	<?php wp_footer(); ?>
</body>
</html>