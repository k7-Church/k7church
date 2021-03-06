<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;

 
class Church_EmailController 
{
    


    public function __construct(){

        add_action('publish_post', array($this,  'ch_send_email', 10, 2));

        add_filter( 'post_updated_messages', array( $this, 'ch_updated_messages') );
    }



    /*
     * Save the custom field values
     *
     * @param int $post_id The current post id
     */
    public function ch_send_email($to, $subject, $message)
    {

    	global $post_ID, $post;
       
       $num = 0;
       $headers = array('Content-Type: text/html; charset=UTF-8');

            if (wp_mail($to, $subject, $message, $headers)) {
                ++$num;
            }
        
        // a hidden custom field
        update_post_meta($post_ID, '_notified_users', $num);

        return $post_ID;
    }

    /**
     * Post update messages.
     *
     * See /wp-admin/edit-form-advanced.php
     *
     * @param array $messages existing post update messages
     *
     * @return array amended post update messages with new update messages
     */
    public function ch_updated_messages($msgs)
    {
        $post = get_post();

        $post_type = get_post_type($post);
        $post_type_object = get_post_type_object($post_type);

        $num = get_post_meta($post->ID, '_notified_users', true);

        if ($post_type_object->publicly_queryable) {
            @$msgs[$post_type][6] .= ' - '.$num.__(' notifications sent.', 'k7-church');

        }

        return $msgs;
    }
}
