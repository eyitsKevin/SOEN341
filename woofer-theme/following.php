<?php
/*
*
* Template Name: following
*
*
*/
get_header();
?>

<h1 style="margin:50px;">
  Woofs by people you tail
</h1>

<?php
$current_user = wp_get_current_user();
$currID = $current_user->ID;
if(get_user_meta($currID, 'following', true)): ?>
<?php
$usersfollowing = get_user_meta($currID, 'following');
$autharr = array();
foreach($usersfollowing[0] as $user){
  array_push($autharr,$user);
}
    $loop = new WP_Query( array( 'post_type' => 'woof','posts_per_page'=>200,'author__in'=> $autharr) );
    if ( $loop->have_posts() ) :
        while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <div style="border:1px solid black;margin:75px;padding:20px;">
          <?php if(get_user_meta(get_the_author_meta('ID') ,'profilepic')):?>
            <div style="margin:50px;border-radius:50px;width:75px;height:75px;display:inline-block;vertical-align:top;">
              <?php
              echo wp_get_attachment_image(get_user_meta(get_the_author_meta('ID') ,'profilepic')[0]);
              ?>
            </div>
          <?php endif; ?>
          <div style="margin:20px;font-weight:600;color:red;display:inline-block;vertical-align:top;">
            <?php echo the_author_nickname(); ?>
            <div style="color:black;font-weight:400;">
              <?php echo the_content(); ?>
            </div>
          </div>
          <div>
          <button data-authid="<?php echo the_author_id();?>" class="sub-btn" style="display:none;padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Tail
          </button>
          <button data-authid="<?php echo the_author_id();?>" class="unsub-btn" style="" style="padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Un-leash
          </button>
          <!-- check which btn needs to be printed out, the like or the unlike -->
          <?php
          $postsliked = get_user_meta($currID, 'likedposts');
          $inarr = false;
          foreach($postsliked[0] as $postl){
            if($postl == get_the_ID()){
              $inarr = true;
            }
          }
          if($inarr): ?>
            <img src="<?php echo get_template_directory_uri()?>/images/bone-hollow.png" data-postid="<?php echo the_ID();?>" class="like" width="40px" height="40px" style="float:right;margin:-6px;display:none;"></img>
            <img src="<?php echo get_template_directory_uri()?>/images/bone-filled.png" data-postid="<?php echo the_ID();?>" class="unlike" width="50px" height="25px" style="float:right;margin:0px;"></img>
          <?php else: ?>
            <img src="<?php echo get_template_directory_uri()?>/images/bone-hollow.png" data-postid="<?php echo the_ID();?>" class="like" width="40px" height="40px" style="float:right;margin:-6px;"></img>
            <img src="<?php echo get_template_directory_uri()?>/images/bone-filled.png" data-postid="<?php echo the_ID();?>" class="unlike" width="50px" height="25px" style="float:right;margin:0px;display:none;"></img>
          <?php endif; ?>
        </div>
      </div>
        <?php endwhile;
    endif;
    wp_reset_postdata();
?>
<?php else: ?>
  <h2 style="margin:50px;">
    You're not tailing anyone !!
  </h2>
<?php endif ?>