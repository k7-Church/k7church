<?php 

class K7_My_Account
{


    public function __construct()
    {

        add_shortcode('my_informations', array($this, 'profil_info'));
        add_action( 'wp', array($this,'user_online_update' ));

    }

    public function profil_info()
    {
        ob_start();
        $this->k7_account_user();
        return ob_get_clean();
    }

    public function k7_account_user()
    {

        $user_id = get_current_user_id();
        $user_name = get_user_meta($user_id, 'first_name', true);
        $welcome_message = __('Welcome', 'k7') . ' ' . $user_name;

        if (is_user_logged_in()) {
            echo '<ul class="nav navbar-nav navbar-right">';

            echo '<li>';
            echo '<a href="' . home_url('profile') . '">' . $welcome_message . '</a>';
            echo '</li>';
            echo '<li>';
            echo '<a href="' . wp_logout_url(home_url()) . '">' . __('Log out', 'k7') . '</a>';
            echo '</li>';
            echo '</ul>';
            // $user_info = get_userdata($user_id);

            global $current_user;
            do_action('k7_user_panel_public', $user_id, 200, $current_user);


        } else {

            echo "<strong class='text-center'><h3>". __('You must be registered', 'k7') . "</h3></strong>" .'<h3><a class="text-center" href="' . wp_login_url() . '">' . __('Log in', 'k7') . '</h3</a>';
        }

    }

    function  user_online_update(){

    if ( is_user_logged_in()) {

        // get the user activity the list
        $logged_in_users = get_transient('online_status');

        // get current user ID
        $user = wp_get_current_user();

        // check if the current user needs to update his online status;
        // he does if he doesn't exist in the list
        $no_need_to_update = isset($logged_in_users[$user->ID])

            // and if his "last activity" was less than let's say ...1 minutes ago
            && $logged_in_users[$user->ID] >  (time() - (1 * 60));

        // update the list if needed
        if(!$no_need_to_update){
          $logged_in_users[$user->ID] = time();
          set_transient('online_status', $logged_in_users, (2*60)); // 2 mins
        }
    }
}

function display_logged_in_users(){
    // get the user activity the list
    $logged_in_users = get_transient('online_status');

    if ( !empty( $logged_in_users ) ) {
        echo "<br/><strong>Logged in users are as following :</strong>";
            foreach ( $logged_in_users as $key => $value) {
                    $user = get_user_by( 'id', $key );
                    echo '<br/> Looged in user name is ' . $user->display_name;
            }
    } else{
        echo "<br/><strong>No user is logged in.</strong>";
    }

}

}
new K7_My_Account();
