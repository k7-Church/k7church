<?php

add_action( 'k7_user_panel_public', 'k7_panel_public', 10, 3);
function k7_panel_public($user_id, $length, $current_user)
{

       global $wpdb;
    $userId = $current_user->ID;
    $count = $wpdb->get_var(' SELECT COUNT(comment_ID)  FROM ' . $wpdb->comments. ' WHERE user_id = "' . $userId . '"' );


	?>     

<hr>
<div class="container bootstrap snippet">
    <div class="row">
        <div class="col-sm-7"><h3><?php echo $current_user->display_name; ?></h3></div>
        <!-- <div class="col-sm-2"><a href="/users" class="pull-right"><img title="profile image" class="img-circle img-responsive" src="http://www.gravatar.com/avatar/28fd20ccec6865e2d5f0e1f4446eb7bf?s=100"></a></div> -->
    </div>
    <div class="row">
        <div class="col-sm-3"><!--left col-->
              

      <div class="text-center">
                    <?php echo get_avatar( $user_id, 200, '', '', array('class' => 'mx-auto img-fluid img-circle d-block') ) ?? "<img src='http://ssl.gstatic.com/accounts/ui/avatar_2x.png' class='avatar img-circle img-thumbnail' alt='avatar'>";?>

        
        <!-- <h6>Upload a different photo...</h6> -->
        <!-- <input type="file" class="text-center center-block file-upload"> -->
      </div></hr><br>

               
          <div class="panel panel-default">
            <div class="panel-heading">Email <i class="fa fa-link fa-1x"></i></div>
            <div class="panel-body"><a href="<?php  echo $current_user->user_email; ?>"><?php  echo $current_user->user_email; ?></a></div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i></div>
            <div class="panel-body"><a href="<?php  echo $current_user->user_url; ?>"><?php  echo $current_user->user_url; ?></a></div>
          </div>
          
          
          <ul class="list-group">
            <li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong><?php echo __('Comments', 'k7');?></strong></span> <?php echo $count ; ?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong><?php echo __('Posts', 'k7');?></strong></span> <?php echo count_user_posts($user_id);?></li>
          </ul> 
               
          <!-- <div class="panel panel-default">
            <div class="panel-heading">Social Media</div>
            <div class="panel-body">
                <i class="fa fa-facebook fa-2x"></i> <i class="fa fa-github fa-2x"></i> <i class="fa fa-twitter fa-2x"></i> <i class="fa fa-pinterest fa-2x"></i> <i class="fa fa-google-plus fa-2x"></i>
            </div>
          </div> -->
          
        </div><!--/col-3-->
        <div class="col-sm-6">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home"><?php echo __('Home', 'k7');?></a></li>
                <li><a data-toggle="tab" href="#messages"><?php echo __('Recent Activity', 'k7');?></a></li>
                <!-- <li><a data-toggle="tab" href="#settings">Menu 2</a></li> -->
              </ul>

              
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                <hr>
                <h2><?php echo __('Description','k7');?></h2>
                            <h6><?php $authorDesc = the_author_meta('description'); echo $authorDesc; ?></h6>

              
              <hr>
              
             </div><!--/tab-pane-->
             <div class="tab-pane" id="messages">
               
                    <div class="col-md-12">
                            <h2 class="mt-2"><span class="fa fa-clock-o ion-clock float-right"></span><?php echo __('Recent Activity', 'k7');?></h2>
                            <table class="table table-sm table-hover table-striped">
                                <tbody>
                                <?php   
                                $args = array(
'user_id' => $user_id // use user_id
 );
$comments = get_comments($args);
foreach($comments as $comment) : ?>
  
                                  
                                    <tr>
                                        <td>
                                            <strong><?php echo($comment->comment_author);?><br></strong><a href="<?php echo get_comment_link($comment->comment_ID);?>"><?php echo $comment->comment_content; ?></a>
                                        </td>
                                    </tr>
                                    <?php 
                                      
endforeach;
?>
                                </tbody>
                            </table>
                        </div> 

               <hr>
                
               
             </div><!--/tab-pane-->
             <!-- <div class="tab-pane" id="settings">
                    
                
                  <hr>
                  
               
              </div> --><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->


                                                                               
        <?php

}