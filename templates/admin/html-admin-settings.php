

        <div class='wrap'>
            <h2>Church Admin Settings</h2>

            <nav class="teste">
                <ul>
                    <li><a href="#"><?php  echo apply_filters ('jacks_boast', "Eu sou o melhor do mundo");?></a></li>
                   
            </nav>

            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <input type="hidden" name="k7_nonce_drop" value="<?php echo wp_create_nonce( 'k7_nonce_field_drop' ); ?>">

                <input type="submit" name="dop_database" class="button button-primary" value="DROP DATABASE">
            </form>
        </div>

        <?php

        edd_checkout_cart();


