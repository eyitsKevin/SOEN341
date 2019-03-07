<?php

$current_user = wp_get_current_user();
$currID = $current_user->ID;

if(get_user_meta($currID, 'following', true)): 

?>

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
          <div style="margin:20px;font-weight:600;color:red;display:inline-block;vertical-align:top;>
            <?php echo the_author_nickname(); ?>
            <div style="color:black;font-weight:400;>
              <?php echo the_content(); ?>
            </div>
          </div>
          <div>
          <button data-authid="<?php echo the_author_id();?>" class="sub-btn" style="display:none;padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Tail
          </button>
          <button data-authid=""<?php echo the_author_id();?>" class="unsub-btn" style="padding:5px;background-color:blue;width:81px;color:white;border-radius:5px;">Un-leash
          </button>
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