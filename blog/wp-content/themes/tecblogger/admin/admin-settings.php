<?php


	$customizer_url = admin_url('customize.php');
	$customizer_theme_support = 'https://themepoints.com/questions-answer';
	$customizer_theme_pro = '';


?>
<div class="wrap">
	
	<h2><?php echo'Help & Support';?></h2>
	
	<div class="support-area">
		<div class="support">
			<div class="single-items">
				<h3>Customize</h3>
				<p>Click the "Customize" link in your menu, or use the button below to get started customizing TecBlogger</p>
				<span class="support-btn">
					<a class="button-primary" href="<?php echo esc_url( $customizer_url ); ?>">Customize</a>
				</span>
			</div>
			<div class="single-items">
				<h3>Support</h3>
				<p>If you need more help please feel free to join our forum and ask any question</p>
				<span class="support-btn">
					<a target="_blank" class="button-primary" href="<?php echo esc_url( $customizer_theme_support ); ?>">Support</a>
				</span>
			</div>
<!-- 			<div class="single-items">
				<h3>Upgrade to Pro</h3>
				<p>TecBlogger Pro is the premium upgrade for TecBlogger.</p>
				<span class="support-btn">
					<a target="_blank" class="button-primary" href="<?php //echo esc_url( $customizer_theme_pro ); ?>">Pro Version</a>
				</span>
			</div> -->
		</div>
	</div>


	<div class="tech-changelogs">
		<h3>Change Log - Version 1.0.7</h3>

		<ul>
			<li><span class="dashicons dashicons-arrow-right"></span> Fix Mobile Menu issues.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Fix Js Error issues.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Fix header issues.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Fix Post Formats issues.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Added Gallery Post Format.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Added Logo Uploader Options.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Added Footer Social Icons.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Update Customizer Options.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Added New Options.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Update Style CSS.</li>
			<li><span class="dashicons dashicons-arrow-right"></span> Fix Other Page Issues.</li>
		</ul>
	</div>
</div>



<?php

?>