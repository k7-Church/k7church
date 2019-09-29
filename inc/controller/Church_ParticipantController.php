<?php
/**
 * @version 1.0
 *
 * @package K7Church/inc/controller
 */


class Church_ParticipantController extends Church_BaseController
{
    public $settings;

    public $callbacks;

    public function ch_register()
    {
        if (!$this->ch_activated('participant_manager')) return;

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_ParticipantCallbacks();

        add_action('init', array($this, 'ch_participant_cpt'));
        add_action('add_meta_boxes', array($this, 'ch_add_meta_boxes'));
        add_action('save_post', array($this, 'ch_save_meta_box'));
        add_action('manage_participant_posts_columns', array($this, 'ch_set_custom_columns'));
        add_action('manage_participant_posts_custom_column', array($this, 'ch_set_custom_columns_data'), 10, 2);
        add_filter('manage_edit-participant_sortable_columns', array($this, 'ch_set_custom_columns_sortable'));

        $this->ch_setShortcodePage();

        add_shortcode('particip-form', array($this, 'ch_participant_form'));
        add_shortcode('particip-slideshow', array($this, 'ch_participant_slideshow'));
        add_action('wp_ajax_submit_participant', array($this, 'ch_submit_participant'));
        add_action('wp_ajax_nopriv_submit_participant', array($this, 'ch_submit_participant'));
    }

    public function ch_setShortcodePage()
    {
        $subpage = array(
            array(
                'parent_slug' => 'edit.php?post_type=participant',
                'page_title' => 'Shortcodes',
                'menu_title' => 'Shortcodes',
                'capability' => 'manage_options',
                'menu_slug' => 'church_participant_shortcode',
                'callback' => array($this->callbacks, 'ch_shortcodePage')
            )
        );

        $this->settings->ch_addSubPages($subpage)->ch_register();
    }

    public function ch_submit_participant()
    {
        if (!DOING_AJAX || !check_ajax_referer('participant-nonce', 'nonce')) {
            return $this->return_json('error');
        }

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $telephone = sanitize_text_field($_POST['telephone']);
        $party = isset($_POST['party']) ? 1 : 0;
        $post_event_id = sanitize_text_field($_POST['post_event_id']);


        $data = array(
            'post_event_id' => $post_event_id,
            'name' => $name,
            'email' => $email,
            'telephone' => $telephone,
            'approved' => 0,
            'party' => $party,
        );


        $args = array(
            'post_title' => __( 'participant from ' . $name, 'k7-church'),
            'post_content' => "teste",
            'post_author' => 1,
            'post_status' => 'publish',
            'post_type' => 'participant',
            'meta_input' => array(
                '_church_participant_key' => $data
            )
        );

        $postID = wp_insert_post($args);

        if ($postID) {
            return $this->ch_return_json('success');
        }

        return $this->ch_return_json('error');
    }

    public function ch_return_json($status)
    {
        $return = array(
            'status' => $status
        );
        wp_send_json($return);

        wp_die();
    }

    public function ch_participant_form()
    {
        ob_start();
        echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/css/party.css\" type=\"text/css\" media=\"all\" />";
                echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/mystyle.css\" type=\"text/css\" media=\"all\" />";

        require_once("$this->plugin_path/templates/participe-form.php");
        echo "<script src=\"$this->plugin_url/src/js/parti.js\"></script>";
        // echo "<script src=\"$this->plugin_url/src/js/form.js\"></script>";
        return ob_get_clean();
    }

    public function ch_participant_slideshow()
    {
        ob_start();
        echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/slider.css\" type=\"text/css\" media=\"all\" />";
        require_once("$this->plugin_path/templates/slider.php");
        echo "<script src=\"$this->plugin_url/assets/slider.js\"></script>";
        return ob_get_clean();
    }

    public function ch_participant_cpt()
    {
        $labels = array(
            'name' => 'participants',
            'singular_name' => __('participant', 'k7-church')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-participant',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'supports' => array('title', 'editor'),
            'show_in_rest' => false
        );

        register_post_type('participant', $args);
    }

    public function ch_add_meta_boxes()
    {
        add_meta_box(
            'participant_author',
            __( 'participant Options', 'k7-church'),
            array($this, 'ch_render_features_box'),
            'participant',
            'side',
            'default'
        );
    }

    public function ch_render_features_box($post)
    {
        wp_nonce_field('church_participant', 'church_participant_nonce');

        $data = get_post_meta($post->ID, '_church_participant_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $telephone = isset($data['telephone']) ? $data['telephone'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $party = isset($data['party']) ? $data['party'] : false;
        ?>
        <p>
            <label class="meta-label" for="church_participant_author"><?php _e('Author Name', 'k7-church'); ?></label>
            <input type="text" id="church_participant_author" name="church_participant_author" class="widefat"
                   value="<?php echo esc_attr($name); ?>">
        </p>
        <p>
            <label class="meta-label" for="church_participant_email"><?php _e('Author Email', 'k7-church'); ?></label>
            <input type="email" id="church_participant_email" name="church_participant_email" class="widefat"
                   value="<?php echo esc_attr($email); ?>">
        </p>
        <p>
            <label class="meta-label" for="church_participant_telephone"><?php _e('Author Telephone', 'k7-church'); ?></label>
            <input type="text" id="church_participant_telephone" name="church_participant_telephone" class="widefat"
                   value="<?php echo esc_attr($telephone); ?>">
        </p>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="church_participant_approved"><?php _e('Approved', 'k7-church'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="church_participant_approved"
                                                     name="church_participant_approved"
                                                     value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="church_participant_approved">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="church_participant_party"><?php _e('Partic', 'k7-church'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="church_participant_party"
                                                     name="church_participant_party"
                                                     value="1" <?php echo $party ? 'checked' : ''; ?>>
                    <label for="church_participant_party">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    public function ch_save_meta_box($post_id)
    {
        if (!isset($_POST['church_participant_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['church_participant_nonce'];
        if (!wp_verify_nonce($nonce, 'church_participant')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        $data = array(
            'name' => sanitize_text_field($_POST['church_participant_author']),
            'email' => sanitize_email($_POST['church_participant_email']),
            'telephone' => sanitize_text_field($_POST['church_participant_telephone']),
            'approved' => isset($_POST['church_participant_approved']) ? 1 : 0,
            'party' => isset($_POST['church_participant_party']) ? 1 : 0,
        );

        update_post_meta($post_id, '_church_participant_key', $data);
    }

    public function ch_set_custom_columns($columns)
    {
        $title = $columns['title'];
        $date = $columns['date'];
        unset($columns['title'], $columns['date']);

        $columns['name'] = __('Partic Name', 'k7-church');
        $columns['title'] = $title;
        $columns['telephone'] =  __('Telphone', 'k7-church');
        $columns['approved'] = __('Approved', 'k7-church');
        $columns['party'] = __('Partic', 'k7-church');
        $columns['date'] = $date;

        return $columns;
    }

    public function ch_set_custom_columns_data($column, $post_id)
    {
        $data = get_post_meta($post_id, '_church_participant_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $telephone = isset($data['telephone']) ? $data['telephone'] : '';
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>'. __( 'YES', 'k7-church').'</strong>' : __(  'NO', 'k7-church');
        $party = isset($data['party']) && $data['party'] === 1 ? '<strong>'. __( 'YES', 'k7-church').'</strong>' : __(  'NO', 'k7-church');

        switch ($column) {
            case 'name':
                echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
                break;

            case 'telephone':
                echo $telephone;
                break;
            case 'approved':
                echo $approved;
                break;

            case 'party':
                echo $party;
                break;
        }
    }

    public function ch_set_custom_columns_sortable($columns)
    {
        $columns['name'] = __( 'name', 'k7-church');
        $columns['approved'] = __( 'approved', 'k7-church');
        $columns['party'] = __( 'partic', 'k7-church');

        return $columns;
    }
}