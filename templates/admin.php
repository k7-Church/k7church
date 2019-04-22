<div class="wrap">
	<h1><?php esc_html_e( 'Church Plugin', 'k7');?></h1>
	<?php settings_errors(); ?>

	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1"><?php esc_html_e( 'Manage Settings', 'k7');?></a></li>
		<li><a href="#tab-2"><?php esc_html_e( 'Updates', 'k7');?></a></li>
		<li><a href="#tab-3"><?php esc_html_e( 'About', 'k7');?></a></li>
	</ul>

	<div class="tab-content">
		<div id="tab-1" class="tab-pane active">

			<form method="post" action="options.php">
				<?php 
					settings_fields( 'church_plugin_settings' );
					do_settings_sections( 'church_plugin' );
					submit_button();
				?>
			</form>
			
		</div>

		<div id="tab-2" class="tab-pane">
			<h3><?php esc_html_e( 'Updates', 'k7');?></h3>
			<p>
				<code>
				== Changelog ==

				== 1.0.10 ==
				Add Custom Post Type Manager, Custom Taxonomy Manager
				fixe bug

				= 1.0.9
				fix bug 

				= 1.0.8
				fix bug 

				= 1.0.7
				fix bug 

				= 1.0.6
				fix bug 

				= 1.0.5
				fix bug 

				= 1.0.4
				fix bug 

				= 1.0.3

				CSS style sheet repair, page fix for user account,
				= 1.0.2

				has been added to the login page, register page, recaptcha login page
				fixe bug
				 
				= 1.0.1

				fixe bug
				= 1.0.0 =

				fixing various bugs

				== Upgrade Notice ==
				no
			</code>
			</p>
		</div>

		<div id="tab-3" class="tab-pane">
			<h3><?php esc_html_e( 'About', 'k7');?></h3>

			<h1>K7 CHURCH</h1>
			<P>
				K7 Church is a Wordpress plugin for churches that claims to be simple and objective for your church's website.
			</P>
			 <div class='wrap'>
            <p>
                <p>Testimonial Form Shortcode</p>
			    <code>[testimonial-form]</code>
			    <p>Testimonial SlideShow Shortcode</p><br>
			    <code>[testimonial-slideshow]</code>
			    <p>location for defaul </p><br>
				<code>[locations location_id=1]</code>
				<p>Location for namber the post</p>
				<code>[locations location_id=1 number_of_locations=1]</code>
				<code>[locations location_id="1" number_of_locations=1 post_status="publish"]</code>
				<br>
            <p><h2>3. Go to Settings » Permalinks, and simply click on Save Changes button.</h2></p>
            <em>If you like this plugin, please <a href="http://wordpress.org/extend/plugins/k7church">vote</a> .
                Author : <a href="https://github.com/zebedeu">Máecio Zebedeu</a>
                You can <a href="https://github.com/knut7/k7church">for bugs,</a> thanks.</em>

        </div>
		</div>
	</div>
</div>