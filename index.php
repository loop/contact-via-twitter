<?php

/** start sessions **/
@session_start();

/** include config **/
include('config.php');

/** include functions **/
include('functions.php');

/** clean all $_POST vars **/
security($_POST);

/** reset error & success vars **/
$error = 0;
$success = 0;
$error_message = '';

/** set error message array **/
$error_message = array();

/** try to send message **/
if(isset($_POST['submit']))
{
   /** check if name is filled in **/
   if($_POST['name'] == '')
   {
      $error = 1;
      $error_message[] = 'Please fill in your full name.';
   }

   /** check if message is filled in **/
   if($_POST['message'] == '')
   {
      $error = 1;
      $error_message[] = 'Please write a message.';
   }

   /** check if captcha is correct **/
   if($_POST['captcha'] != $_SESSION['captcha'] || $_POST['captcha'] == '')
   {
      $error = 1;
      $error_message[] = 'Please enter the correct verification code.';
   }

   /** no error **/
   if($error != 1)
   {
      if(send_tweet($_POST['message'], $_POST['name']) == true){ $success = 1; }else{ $error = 1; $error_message[] = 'Please try again.'; }
   }
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="./css/styles.css" type="text/css" media="screen" />
<script type="text/javascript" src="./javascript/jquery.js"></script>
<script type="text/javascript" src="./javascript/jquery.charlimit.js"></script>
<title>Contact via Twitter</title>
</head>
<body>
<div id="contact" class="clearfix">
<center><img src="./images/logo.png" alt="Logo" /></center>
<br />
  <?php 
  if($error == 1)
  {
     echo '<div id="error">';
     foreach($error_message as $err){ echo $err . "<br>"; }
	 echo '</div>';
  }
  ?>
  
  <?php if($success != 1): ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="name">Name</label>
    <input name="name" id="name" size="60" type="text" value="<?php echo $_POST['name']; ?>" />
    <label for="message">Message</label>
    <textarea name="message" cols="46" rows="5" id="message"><?php echo $_POST['message']; ?></textarea>
	<br />Characters left: <span class="countbox"></span><br /><br />
	<label for="captcha">Can you type?</label>
    <img src="captcha.php" alt="" /> &nbsp; <input name="captcha" id="captcha" size="6" type="text" maxlength="6" />
    <hr>
    <input name="submit" value="" type="submit" title="Send Tweet" alt="Send Tweet" />
  </form>
  <?php else: ?>
  <div id="success">Thank you for your tweet. Click <a href="javascript:history.go(-1)">Here To Go Back</a></div>
  <?php endif; ?>
<br />
<span id="copyright">Contact via Twitter.</span>
</div>
</body>
</html>