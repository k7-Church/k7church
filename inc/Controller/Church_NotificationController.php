<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;

 
class Church_NotificationController extends Church_BaseController
{
    // an array of countrys

    public $notification_countrys = array(
        'AF' => 'Afghanistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'BQ' => 'British Antarctic Territory',
        'IO' => 'British Indian Ocean Territory',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CT' => 'Canton and Enderbury Islands',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos [Keeling] Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo - Brazzaville',
        'CD' => 'Congo - Kinshasa',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'CI' => 'Côte d’Ivoire',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'NQ' => 'Dronning Maud Land',
        'DD' => 'East Germany',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'FQ' => 'French Southern and Antarctic Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong SAR China',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JT' => 'Johnston Island',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau SAR China',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'FX' => 'Metropolitan France',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MI' => 'Midway Islands',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar [Burma]',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NT' => 'Neutral Zone',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'KP' => 'North Korea',
        'VD' => 'North Vietnam',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PC' => 'Pacific Islands Trust Territory',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territories',
        'PA' => 'Panama',
        'PZ' => 'Panama Canal Zone',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'YD' => "People's Democratic Republic of Yemen",
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'RE' => 'Réunion',
        'BL' => 'Saint Barthélemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'CS' => 'Serbia and Montenegro',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'KR' => 'South Korea',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'ST' => 'São Tomé and Príncipe',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UM' => 'U.S. Minor Outlying Islands',
        'PU' => 'U.S. Miscellaneous Pacific Islands',
        'VI' => 'U.S. Virgin Islands',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'SU' => 'Union of Soviet Socialist Republics',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United Countrys',
        'ZZ' => 'Unknown or Invalid Region',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican City',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WK' => 'Wake Island',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
        'AX' => 'Åland Islands'
    );

    public $notification_city = array('AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming');

    public function ch_register()
    {
        if ( ! $this->ch_activated( 'notify_manager' ) ) return;

        $this->callbacks = new Church_NotificationCallbacks();

        add_action('personal_options_update', array($this,  'ch_save_user_meta_fields'));

        add_action('edit_user_profile_update', array($this, 'ch_save_user_meta_fields'));

        add_action('show_user_profile', array($this, 'ch_show_user_meta_fields'));

        add_action('edit_user_profile', array($this, 'ch_show_user_meta_fields'));

        add_action('add_meta_boxes', array($this,  'ch_add_meta_box'));

        add_action('save_post', array($this, 'ch_save_custom_fields'));

        add_action('publish_post', array($this,  'ch_notify_new_post'));

        add_filter( 'post_updated_messages', array( $this, 'ch_updated_messages') );
    }

    public function ch_setCountry()
    {
        $country = $this->notification_countrys;

        return $country;
    }

    /**
     * Show custom user profile fields.
     *
     * @param obj $user the user object
     */
    public function ch_show_user_meta_fields($user)
    {
        ?>
<h3><?php _e('Notification for receiving messages by country', 'k7'); ?></h3>

<table class="form-table">

    <tr>
        <th scope="row"><?php _e('Country', 'k7'); ?></th>

        <td>
            <label for="country">
                <select name="country">
                    <option value="" <?php selected(get_user_meta($user->ID, 'country', true), ''); ?>>Select
                    </option>
                    <?php foreach ($this->notification_countrys as $key => $value) {
            ?>
                    <option value="<?php echo $key; ?>"
                        <?php selected(esc_attr(get_user_meta($user->ID, 'country', true)), $key); ?>>
                        <?php echo $value; ?></option>
                    <?php
        } ?>
                </select>
                <?php _e('Select country', 'k7'); ?>
            </label>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('Notifications', 'k7'); ?></th>
        <td>
            <label for="notification">
                <input id="notification" type="checkbox" name="notification" value="true"
                    <?php checked(esc_attr(get_user_meta($user->ID, 'notification', true)), 'true'); ?> />
                <?php _e('Subscribe to email notifications', 'k7'); ?>
            </label>
        </td>
    </tr>

    <tr>
        <th scoper="row"><label for="telefono"><?php _e('Phone', 'k7'); ?></label></th>
        <td>
            <input type="text" name="phone" id="phone"
                value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>"
                class="regular-text" /><br />
            <span class="description"><?php _e('Phone number', 'k7'); ?></span>
        </td>
    </tr>

</table>
<?php
    }

    /**
     * Store data in wp_usermeta table.
     *
     * @param int $user_id the user unique id
     */
    public function ch_save_user_meta_fields($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        if (isset($_POST['country'])) {
            update_user_meta($user_id, 'country', sanitize_text_field($_POST['country']));
        }

        if (!isset($_POST['notification'])) {
            $_POST['notification'] = 'false';
        }

        update_user_meta($user_id, 'notification', sanitize_text_field($_POST['notification']));

        if (isset($_POST['phone'])) {
            update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
        }
    }

    /*
     * Adds the meta_box
     */
    public function ch_add_meta_box()
    {
        /** possible values: 'post', 'page', 'dashboard', 'link', 'attachment', 'custom_post_type' **/
        
        $screens = array('post', 'locations', 'sermon'); 

        foreach ($screens as $screen) {
            add_meta_box(
                'ch_metabox',             // $id - meta_box ID
                __('Venue information', 'k7'),      // $title - a title for the meta_box container
                array($this->callbacks, 'ch_meta_box_callback'),   // $callback - the callback which outputs the html for the meta_box
                $screen,                        // $post_type - where to show the meta_box. Possible values: 'post', 'page', 'dashboard', 'link', 'attachment', 'custom_post_type'
                'advanced',                       // $context - possible values: 'normal', 'advanced', 'side'
                'low'                          // $priority - possible values: 'high', 'core', 'default', 'low'
                );
        }
    }

    /*
     * Save the custom field values
     *
     * @param int $post_id The current post id
     */
    public function ch_save_custom_fields($post_id)
    {
        // Check WP nonce
        if (!isset($_POST['ch_meta_box_nonce']) || !wp_verify_nonce($_POST['ch_meta_box_nonce'], 'ch_meta_box')) {
            return;
        }

        // Return if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // check the post_type and set the correspondig capability value
        $capability = (isset($_POST['post_type']) && 'page' == $_POST['post_type']) ? 'edit_page' : 'edit_post';

        // Return if the user lacks the required capability
        if (!current_user_can($capability, $post_id)) {
            return;
        }

        if (!isset($_POST['venue']['disable'])) {
            $_POST['venue']['disable'] = 'false';
        }

        // validate custom field values
        $fields = (isset($_POST['venue'])) ? (array) $_POST['venue'] : array();
        $fields = array_map('sanitize_text_field', $fields);

        foreach ($fields as $key => $value) {
            // store data
            update_post_meta($post_id, $key, $value);
        }
    }

    /*
     * Save the custom field values
     *
     * @param int $post_id The current post id
     */
    public function ch_notify_new_post($post_ID)
    {
        $url = get_permalink($post_ID);

        $country = get_post_meta($post_ID, 'country', true);

        if ('true' == get_post_meta($post_ID, 'disable', true)) {
            return;
        }

        // build the meta query to retrieve subscribers
        $args = array(
            'meta_query' => array(
                    array('key' => 'country', 'value' => $country, 'compare' => '='),
                    array('key' => 'notification', 'value' => 'true', 'compare' => '='),
                ),
            'fields' => array('display_name', 'user_email'),
        );
        // retrieve users to notify the new post
        $users = get_users($args);
        $num = 0;
        foreach ($users as $user) {
            $to = $user->display_name.' <'.$user->user_email.'>';

            $subject = sprintf(__('Hei! We have news for you from %s', 'k7'), $this->notification_countrys[$country]);

            $message = sprintf(__('Hi %s!', 'k7'), $user->display_name)."\r\n".
        sprintf(__('We have a new post from %s', 'k7'), $this->notification_countrys[$country])."\r\n".
        sprintf(__('Read more on %s', 'k7'), $url).'.'."\r\n";

            $headers = array('Content-Type: text/html; charset=UTF-8');

            if (wp_mail($to, $subject, $message, $headers)) {
                ++$num;
            }
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
            @$msgs[$post_type][6] .= ' - '.$num.__(' notifications sent.', 'k7');

        }

        return $msgs;
    }
}
