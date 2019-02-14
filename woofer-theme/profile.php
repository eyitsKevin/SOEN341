<?php
/*
*
* Template Name: profile
*
*
*/
get_header();
?>
<?php if(is_user_logged_in()):?>
  <h1 style="margin:50px;">
    Post a woof
  </h1>
  <form method="POST" style=padding:50px;>
      <label>Woof :</label>
      <textarea value="" rows="3" class="input-xlarge" name='text'>

      </textarea>

      <div>
        <button class="btn btn-primary">Woof!</button>
      </div>
      <input type="hidden" name="action" value="woof" />
   </from>

<?php echo get_template_part( 'show-woofs-on-profile.php' ); ?>

<?php else: ?>
  <h1 style="margin:50px;">
    You need to login !!
  </h1>

<?php endif;?>


<?php
get_footer();
?>
