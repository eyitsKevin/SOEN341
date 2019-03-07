<?php
/*
*
* Template Name: liked
*
*
*/
get_header();
?>
<?php if(is_user_logged_in()):?>
  <h2 style="margin:50px;margin-top:200px;">
    Displaying the woofs I gave a bone to.
  </h2>

<?php echo get_template_part( 'show-woofs-on-liked.php' ); ?>

  <?php else: ?>
  <h1 style="margin:50px;">
    You need to login !!
  </h1>

<?php endif;?>


<?php
get_footer();
?>
