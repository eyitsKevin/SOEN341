<?php
/*
*
* Template Name: registration
*
*
*/
get_header();
  if(defined('REGISTRATION_ERROR'))
    foreach(unserialize(REGISTRATION_ERROR) as $error)
      echo "<div class=\"error\">{$error}</div>";
  // errors here, if any

  elseif(defined('REGISTERED_A_USER'))
    echo 'a email has been sent to '.REGISTERED_A_USER;
?>
<h1 style="margin:100px;">
  Register
</h1>

<form method="post" style="margin:50px;" action="<?php echo add_query_arg('do', 'register', home_url('/')); ?>">
  <label>
    User:
    <input type="text" name="user" value=""/>
  </label>

  <label>
    Password:
    <input type="text" name="pass" value=""/>
  </label>


  <input type="submit" value="register" />
</form>
<?php
get_footer();
?>
