<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/api/widgets
 */

defined('ABSPATH') || exit;


class Church_MediaWidget extends \WP_Widget
{
    public $widget_ID;

    public $widget_name;

    public $widget_options = array();

    public $control_options = array();

    function __construct()
    {

        $this->widget_ID = 'church_media_widget';
        $this->widget_name = 'Church Media Widget';

        $this->widget_options = array(
            'classname' => $this->widget_ID,
            'description' => $this->widget_name,
            'customize_selective_refresh' => true,
        );

        $this->control_options = array(
            'width' => 400,
            'height' => 350,
        );
    }

    public function ch_register()
    {
        parent::__construct($this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options);

        add_action('widgets_init', array($this, 'ch_widgetsInit'));
    }

    public function ch_widgetsInit()
    {
        register_widget($this);
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        if (!empty($instance['image'])) {
            echo '<img src="' . esc_url($instance['image']) . '" alt="">';
        }
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $image = !empty($instance['image']) ? $instance['image'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'k7-church'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('image')); ?>"><?php esc_attr_e('Image:', 'k7-church'); ?></label>
            <input class="widefat image-upload" id="<?php echo esc_attr($this->get_field_id('image')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('image')); ?>" type="text"
                   value="<?php echo esc_url($image); ?>">
            <button type="button" class="button button-primary js-image-upload">Select Image</button>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['image'] = !empty($new_instance['image']) ? $new_instance['image'] : '';

        return $instance;
    }
}