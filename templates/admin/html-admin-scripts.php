  <?php

   function k7_scripts_page()
    {
        if ( current_user_can('manage_options') ) {

        if (array_key_exists('submit_scripts_update', $_POST)) {
            update_option('k7_header_scripts', sanitize_text_field($_POST['header_scripts']));
            update_option('k7_footer_scripts', sanitize_text_field($_POST['footer_scripts']) );

            ?>
            <div id="setting-error-settings-updated" class="updated settings-error notice is-dismissible "><strong>Setting
                    have been saved.</strong></div>
            <?php

        }
        $header_scripts = get_option('k7_header_scripts', 'none');
        $footer_scripts = get_option('k7_footer_scripts', 'none');
        ?>
        <div class="wrap">
            <h2> Update Scripts on the header and footer</h2>
            <form action="" method="post">
                <label for="header_scripts">Header Script</label>
                <textarea class="large-text bt" name="header_scripts"> <?php print $header_scripts; ?></textarea>
                <label for="footer_scripts">Footer Script</label>
                <textarea class="large-text" name="footer_scripts"><?php print $footer_scripts; ?></textarea>
                <input type="submit" name="submit_scripts_update" class="button button-primary" value="UPDATE SCRIPT">
            </form>

        </div>
        <?php
    }
    }