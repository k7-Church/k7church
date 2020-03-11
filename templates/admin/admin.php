<div class="wrap">
    <?php settings_errors(); ?>

    <?php
    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    }
        elseif (isset($_GET['events'])) {
        $active_tab = $_GET['events'];
    }
        elseif (isset($_GET['church_color'])) {
        $active_tab = $_GET['church_color'];
    }
     else {
        $active_tab = 'tab_one';
    }
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=church_plugin&tab=tab_one"
           class="nav-tab <?php echo $active_tab == 'tab_one' ? 'nav-tab-active' : ''; ?>"><?php _e('GENERAL SETTINGS', 'k7-church');?></a>
        <a href="?page=church_plugin&tab=events"
           class="nav-tab <?php echo $active_tab == 'events' ? 'nav-tab-active' : ''; ?>"><?php _e('Events', 'k7-church');?></a>
        <a href="?page=church_plugin&tab=church_color"
           class="nav-tab <?php echo $active_tab == 'church_color' ? 'nav-tab-active' : ''; ?>"><?php _e('Color Control', 'k7-church');?></a>
    </h2>
    <form method="post" action="options.php">
        <?php
        if ($active_tab == 'tab_one') { ?>

            <?php /** settings manager */ ?>

            <div class="wrap">


                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-1"><?php _e('Manage Settings', 'k7-church'); ?></a></li>
                    <li><a href="#tab-2"><?php _e('Updates', 'k7-church'); ?></a></li>
                    <li><a href="#tab-3"><?php _e('About', 'k7-church'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <form method="post" action="options.php">

                            <?php
                            settings_fields('church_plugin_settings');
                            do_settings_sections('church_plugin');
                            submit_button();
                            ?>
                        </form>

                    </div>

                    <div id="tab-2" class="tab-pane">
                        <h3><?php _e('Updates', 'k7-church'); ?></h3>
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

                        <P>
                            K7 Church is a WordPress plugin for churches that claims to be simple and objective for your
                            church's website.
                        </P>
                        <div class='wrap'>
                            

                            <p>Testimonial Form Shortcode</p>
                            <code>[testimonial-form]</code>
                            <p>Testimonial SlideShow Shortcode</p><br>
                            <code>[testimonial-slideshow]</code>
                            <p>location for default </p><br>
                            <code>[locations location_id=1]</code>
                            <p>Location for number the post</p>
                            <code>[locations location_id=1 number_of_locations=1]</code>
                            <code>[locations location_id="1" number_of_locations=1 post_status="publish"]</code>
                            <br>
                            <p>
                            <h2>3. Go to Settings » Permalinks, and simply click on Save Changes button.</h2></p>
                            <em>If you like this plugin, please <a href="http://wordpress.org/extend/plugins/k7-church">vote</a>
                                .
                                Author : <a href="https://github.com/zebedeu">Máecio Zebedeu</a>
                                You can <a href="https://github.com/knut7/k7-church">for bugs,</a> thanks.</em>

                        </div>
                    </div>
                </div>
            </div>

            <?php

        } elseif ($active_tab == 'events') {
                  /** settings manager */ 

                    settings_fields( 'church_options_group' );
                    do_settings_sections( 'church_settings' );
                    submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
        }
         elseif ($active_tab == 'church_color') {
                  /** settings manager */ 

                    settings_fields( 'church_options_group' );
                    do_settings_sections( 'colors' );
                    submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
        }
        ?>
    </form>

</div>
