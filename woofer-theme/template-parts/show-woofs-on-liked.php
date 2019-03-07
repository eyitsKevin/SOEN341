<?php
  $current_user = wp_get_current_user();
  $currID = $current_user->ID;
  if(get_user_meta($currID, 'likedposts', true)): ?>
  <?php
  $postsliked = get_user_meta($currID, 'likedposts');
  $postarr = array();
  foreach($postsliked[0] as $postl){
    array_push($postarr,$postl);
  }
      $loop = new WP_Query( array( 'post_type' => 'woof','posts_per_page'=>200,'post__in'=> $postarr) );
      if ( $loop->have_posts() ) :
          while ( $loop->have_posts() ) : $loop->the_post(); ?>
          <div style="border:1px solid black;margin:75px;padding:20px;">
            <div style="margin:20px;font-weight:600;color:red;display:inline-block;vertical-align:top;">
              <?php echo the_author_nickname(); ?>
              <div style="color:black;font-weight:400;">
                <?php echo the_content(); ?>
              </div>
            </div>
            <div>            <!-- check which btn needs to be printed out, the follow or the unfollow -->
            <?php
            $current_user = wp_get_current_user();
            $currID = $current_user->ID;
            $usersfollowing = get_user_meta($currID, 'following');
            $autharr = array();
            $inarr = false;
            foreach($usersfollowing[0] as $user){
              if($user == $post->post_author){
                $inarr = true;
              }
            }
            if($inarr): ?>
                <button data-authid="<?php echo the_author_id();?>" class="sub-btn" style="display:none;padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Tail
                </button>
                <button data-authid="<?php echo the_author_id();?>" class="unsub-btn" style="" style="padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Un-leash
                </button>
            <?php else: ?>
              <button data-authid="<?php echo the_author_id();?>" class="sub-btn" style="padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Tail
              </button>
              <button data-authid="<?php echo the_author_id();?>" class="unsub-btn" style="display:none;" style="padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Un-leash
              </button>
            <?php endif; ?>
            <img src="<?php echo get_template_directory_uri()?>/images/bone-hollow.png" data-postid="<?php echo the_ID();?>" class="like" width="40px" height="40px" style="float:right;margin:-6px;display:none;"></img>
            <img src="<?php echo get_template_directory_uri()?>/images/bone-filled.png" data-postid="<?php echo the_ID();?>" class="unlike" width="50px" height="25px" style="float:right;margin:0px;"></img>
          </div>
          </div>
          <?php endwhile;
      endif;
      wp_reset_postdata();
  ?>
  <?php else: ?>
    <h2 style="margin:50px;">
      You didn't give a bone to any woof :(
    </h2>
  <?php endif ?>
