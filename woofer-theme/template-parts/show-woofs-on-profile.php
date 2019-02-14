<h2 style=""margin:50px;margin-top:200px;"">
  Displaying my woofs
</h2>
<?php
$current_user = wp_get_current_user();
$currID = $current_user->ID;
    $loop = new WP_Query( array( 'post_type' => 'woof','author'=> $current_user->ID) );
    if ( $loop->have_posts() ) :
        while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <div style="border:1px solid black;margin:75px;">
            <div style="margin:20px;font-weight:600;color:red;display:inline-block;vertical-align:top;">
            <?php echo the_author_nickname(); ?>
            <div style="color:black;font-weight:400;">
              <?php echo the_content(); ?>
            </div>
          </div>
        </div>
        <?php endwhile;
    endif;
    wp_reset_postdata();
?>
