<header class="header">
    <button class="ch-tab" onclick="myFunction()"><?php esc_html_e( 'Complete the form to register for this event!', 'k7-church' );?></button>
</header>

<form id="church-participant-form" style="display: none;" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
        <?php global $event, $wpdb;


$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $event . "'" );
$getpost= get_post($postid);

?>
    <div class="field-container">
        <input id="post_event_id" class="field-input" type="hidden" name="post_event_id" value="<?php echo $getpost->ID;?>">
        <small class="field-msg error" data-error="invalidName"><?php _e('Your Name is Required', 'k7-church') ?></small>
    </div>

    <div class="field-container">
        <input type="text" class="field-input" placeholder="<?php esc_attr_e('Your Name', 'k7-church') ?>" id="name"
               name="name" required>
        <small class="field-msg error" data-error="invalidName"><?php _e('Your Name is Required', 'k7-church') ?></small>
    </div>

    <div class="field-container">
        <input type="email" class="field-input" placeholder="<?php esc_attr_e('Your Email', 'k7-church') ?>" id="email"
               name="email" required>
        <small class="field-msg error"
               data-error="invalidEmail"><?php _e('The Email address is not valid', 'k7-church') ?></small>
    </div>
    <div class="field-container">
        <input type="text" class="field-input" placeholder="<?php esc_attr_e('Your Telephone', 'k7-church') ?>" id="telephone"
               name="telephone" required>
        <small class="field-msg error"
               data-error="invalidTelephone"><?php _e('The Telephone is not valid', 'k7-church') ?></small>
    </div>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="party"><?php _e('I will participate', 'k7-church'); ?></label>
            <div class="text-right w-10 inline">
                <div class="ui-toggle inline field-input"><input type="checkbox" id="party"
                                                     name="party"
                                                     value="1" required>
                    <label for="party">
                        <div></div>
                                <small class="field-msg error"
               data-error="invalidChecked"><?php _e('The Check is not valid', 'k7-church') ?></small>

                    </label>
                </div>
            </div>
        </div>
    <div class="field-container">
        <div>
            <button type="stubmit" class="btn btn-default btn-lg btn-sunset-form">Submit</button>
        </div>
        <small class="field-msg js-form-submission"><?php _e('Submission in process, please wait&hellip;', 'k7-church') ?></small>
        <small class="field-msg success js-form-success"><?php _e('Message Successfully submitted, thank you!', 'k7-church') ?></small>
        <small class="field-msg error js-form-error"><?php _e('There was a problem with the Contact Form, please try again!', 'k7-church') ?></small>
    </div>

    <input type="hidden" name="action" value="submit_participant">
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce("participant-nonce") ?>">

</form>

<script type="text/javascript">
    function myFunction() {
  var x = document.getElementById("church-participant-form");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>