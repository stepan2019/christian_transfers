</main>
	<!-- Footer -->
	<footer class="footer black" role="contentinfo">
		<div class="wrap">
			<div class="rowfooter">
				
				<!-- Column -->
				<article class="one-fourth">
					<h6>Questions?</h6>
					    <ul class="contact-data">
						    <li><a href="/about-christian-transfers" alt="about christian transfers">About Us</a></li>
							<li><a href="/help" alt="christian transfers help center">Help Center</a></li>
                            <li><a href="/how-it-works" alt="how it works christian transfers">How it works</a></li>
							<li><a href="/privacy-policy" title="privacy policy christian transfers">Privacy Policy</a></li>
                            <li><a href="/terms-conditions" title="terms & conditions christian transfers">Terms & Conditions</a></li>
                            <li><a href="/blog" target="_blank" alt="christian transfers blog">Christian Transfers Blog</a></li>
							<li><a href="/od" target="_blank" alt="open data christian transfers">Open Data</a></li>
                        </ul>
				</article>
				<!-- //Column -->

				<!-- Column -->
				<article class="one-fourth">
					<h6>Private Transfers</h6>
					    <ul class="contact-data">
						    <li><a href="/airport-transfers" alt="airport transfers">Airport Transfers</a></li>
							<li><a href="/limousines" alt="executive and vip transfers">Executive & Vip</a></li>
						    <li><a href="/minibus" alt="private minibus rentals">Private Minibus</a></li>
                            <li><a href="/coaches" alt="private coaches rentals">Private Coaches</a></li>
                            <li><a href="/taxi" alt="taxi app">Taxi App</a></li>
							<li><a href="/couriers" alt="urgent couriers bike moto vans app">Couriers and Vans</a></li>
							<li><a href="/recovery-car" alt="recovery car app">Recovery Car</a></li>  
                        </ul>
				</article>
				<!-- //Column -->
								
				<!-- Column -->
				<article class="one-fourth">
					<h6>Shared Transfers</h6>
                        <ul class="contact-data">
							<li><a href="/timetable" alt="timetable christian transfers europe">Time Tables</a></li>
                            <li><a href="/budapest-timisoara" alt="shuttle budapest timisoara">Budapest Timisoara</a></li>
                            <li><a href="/budapest-cluj" alt="shuttle budapest cluj">Budapest Cluj</a></li>
							<li><a href="/timisoara-belgrade" alt="shuttle timisoara belgrade">Timisoara Belgrade</a></li>
							<li><a href="/otopeni-constanta" alt="shuttle otopeni constanta">Otopeni Constanta</a></li>
                        </ul>
				</article>
				<!-- //Column -->
				
				<!-- Column -->
				<article class="one-fourth">
				    <h6>Partner with Us</h6>	
					    <ul class="contact-data">
							<li><a href="/partners" alt="partner with christian transfers">Partner with Us</a></li>
							<li><a href="/countries" alt="countries and cities where christian transfers operate">Countries & Cities</a></li>
                        </ul>
					<h6>Our websites</h6>
					    <ul class="contact-data">
                            <li><a href="https://www.christiantransfers.co.uk/" target="_blank" alt="christian transfers uk">Christian Transfers Uk</a></li>
                            <li><a href="https://www.christiantransfers.ro/" target="_blank" alt="christian transfers romania">Christian Transfers Ro</a></li>
                            <li><a href="https://www.mayamarket.co.uk/" target="_blank" alt="classified ads maya market">Ads - Maya Market</a></li>
					    </ul>
				</article>
				<!-- //Column -->
			</div>   
            <div class="rowfooter">
                        <!--<article class="one-half">
                            <h6>Our payment system</h6>
                            <p>
                                <img height="40" width="52" title="Jcb card payments in GBP" alt="jcb logo" src="/images/zcards/jcb.gif">
                                <img height="40" width="63" title="Maestro card payments in GBP" alt="maestro logo" src="/images/zcards/maestro 1.gif">
                                <img height="40" width="67" title="Mastercard card payments in GBP" alt="mastercard logo" src="/images/zcards/mastercard.png">
                                <img height="40" width="94" title="Visa card payments in GBP" alt="visa logo" src="/images/zcards/visa.png">
                                <img height="40" width="50" title="Worldpay card payments" alt="worldpay logo" src="/images/zcards/worldpay logo 1.png">
                            </p>
                        </article>
                        
                        <article class="one-fourth">
                           <h6>Follow us</h6>
                            <ul class="social">
							</ul>
                        </article>-->
                        
                    </div>
			
			<div class="copy">
                <p>Copyright <?php echo $this->_tpl_vars['year']; ?>
, <a href="https://www.<?php echo $this->_tpl_vars['sitename_footer']; ?>
"><?php echo $this->_tpl_vars['sitename_footer']; ?>
</a>. All rights reserved. </p>
				
				<nav role="navigation" class="foot-nav">
				   <ul>
						<li><a href="http://www.facebook.com/ChristianTransfers?sk=wall" title="facebook" target="_blank"><i class="fa fa-fw fa-facebook"></i></a></li>
                        <li><a href="https://www.linkedin.com/company/christian-transfers/" title="linkedin" target="_blank"><i class="fa fa-fw fa-linkedin"></i></a></li>
						<li><a href="https://www.twitter.com/christiantransf" title="twitter" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>
						<li><a href="https://www.instagram.com/christian.transfers/" title="instagram" target="_blank"><i class="fa fa-fw fa-instagram"></i></a></li>
                        <li><a href="https://www.pinterest.co.uk/christiantransfers/" title="pinterest" target="_blank"><i class="fa fa-fw fa-pinterest-p"></i></a></li>
						<li><a href="https://www.tumblr.com/dashboard/blog/christiantransfers" title="tumblr" target="_blank"><i class="fa fa-fw fa-tumblr"></i></a></li>	
					<!--<li><a href="#" title="Home">Home</a></li>
						<li><a href="#" title="Blog">Blog</a></li>
						<li><a href="#" title="About us">About us</a></li>
						<li><a href="#" title="Contact us">Contact us</a></li>
						<li><a href="#" title="Terms of use">Terms of use</a></li>
						<li><a href="#" title="Help">Help</a></li>
						<li><a href="#" title="For partners">For partners</a></li>-->
					</ul>
				</nav>
				
			</div>
		</div>
	</footer>
<!-- //Footer -->
<?php echo '	
	 <!-- jQuery -->

<script language=\'JavaScript\' type=\'text/javascript\'>
 $.ajax({
   url: "/application/search_trains.php",
   type: "post",
   dataType: "html",
   data: {},
   success: function(returnData){
     $("#booking").html(returnData);
   },
   error: function(e){
     //alert(e);
   }
});
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \'UA-16218826-15\']);
  _gaq.push([\'_setDomainName\', \'.christiantransfers.eu\']);
  _gaq.push([\'_trackPageview\']);

  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- Google Analytics END-->

'; ?>


</body>
</html>