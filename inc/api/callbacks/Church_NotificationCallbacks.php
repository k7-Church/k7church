<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/api/callbacks
 */

defined('ABSPATH') || exit;

class Church_NotificationCallbacks extends Church_BaseController
{

    private $notify;

    public function __construct()
    {
        $this->notify = new Church_NotificationController();
    }

    /*
     * Print the meta_box
     *
     * @param obj $post The object for the current post
     */
    function ch_meta_box_callback($post)
    {

        // Add a nonce field
        wp_nonce_field('ch_meta_box', 'ch_meta_box_nonce');

        $address = esc_attr(get_post_meta(get_the_ID(), 'address', true));
        $city = esc_attr(get_post_meta(get_the_ID(), 'city', true));
        $country = esc_attr(get_post_meta(get_the_ID(), 'country', true));
        $zip = esc_attr(get_post_meta(get_the_ID(), 'zip', true));
        $phone = esc_attr(get_post_meta(get_the_ID(), 'phone', true));
        $website = esc_attr(get_post_meta(get_the_ID(), 'website', true));
        $disable = esc_attr(get_post_meta(get_the_ID(), 'disable', true));

        ?>

        <table id="venue">
            <tbody>
            <tr>
                <td class="label"><?php _e( 'Address', 'k7-church' ); ?></td>
                <td><input type="text" id="address" name="venue[address]" value="<?php echo $address; ?>" size="30" /></td>
            </tr>
            <tr>
                <td><?php _e( 'City', 'k7-church' ); ?></td>
                <td><input type="text" id="city" name="venue[city]" value="<?php echo $city; ?>" size="30" /></td>
            </tr>
            <tr>
                <td><?php _e('Country', 'k7-church'); ?></td>
                <td>
                    <select name="venue[country]">
                        <option value="" <?php selected($country, ""); ?>>Select</option>
                        <?php foreach ($this->notify->ch_setCountry() as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($country, $key); ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php _e( 'zip', 'k7-church' ); ?></td>
                <td><input type="text" id="zip" name="venue[zip]" value="<?php echo $zip; ?>" size="30" /></td>
            </tr>
            <tr>
                <td><?php _e( 'Phone', 'k7-church' ); ?></td>
                <td><input type="text" id="phone" name="venue[phone]" value="<?php echo $phone; ?>" size="30" /></td>
            </tr>
            <tr>
                <td><?php _e( 'Website', 'k7-church' ); ?></td>
                <td><input type="text" id="website" name="venue[website]" value="<?php echo $website; ?>" size="30" /></td>
            </tr>
            
            <tr>
                <td><?php _e('Disable notification', 'k7-church'); ?></td>
                <td class="ui-toggle"><input class="ui-toggle" id="disable" type="checkbox" name="venue[disable]" value="1" <?php checked($disable, 'true'); ?> /></td>
            </tr>

            </tbody>
        </table>
        <?php
    }
}
