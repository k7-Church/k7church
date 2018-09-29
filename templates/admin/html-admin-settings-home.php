<?php

//add_action('ver', 'teste');
add_filter('oiii', 'teste', 10);
function teste($d){

    return  $ola = "ola mundo" . "  $d   ";

}


//echo teste();

//echo apply_filters('oiii', " ooooooooooooooo");




// do_action('ver');
