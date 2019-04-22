<form id="church-auth-form" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
    <div class="auth-btn">
        <input class="submit_button" type="button" value="<?php esc_attr_e( 'Login', 'k7');?>" id="church-show-auth-form">
    </div>
    <div id="church-auth-container" class="auth-container">
        <a id="church-auth-close" class="close" href="#"><?php esc_html_e( '&times;', 'k7');?></a>
        <h2><?php esc_html_e( 'Site Login', 'k7');?></h2>
        <label for="username"><?php esc_html_e( 'Username', 'k7');?></label>
        <input id="username" type="text" name="username">
        <label for="password"><?php esc_html_e( 'Password', 'k7');?></label>
        <input id="password" type="password" name="password">
        <input class="submit_button" type="submit" value="<?php esc_attr_e( 'Login', 'k7');?>" name="submit">
        <p class="status" data-message="status">Satus the messages</p>

        <p class="actions">
            <a href="<?php echo wp_lostpassword_url(); ?>"><?php esc_html_e( 'Forgot Password?', 'k7');?></a> - <a href="<?php echo wp_registration_url(); ?>"><?php esc_html_e( 'Register', 'k7');?></a>
        </p>

        <input type="hidden" name="action" value="church_login">
        
        <?php wp_nonce_field( 'ajax-login-nonce', 'church_auth' ); ?>
    </div>
</form>