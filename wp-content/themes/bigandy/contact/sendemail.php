<?php
  $email = $_REQUEST['email'] ;
  $message = $_REQUEST['message'] ;

  mail( "ahudson_24@hotmail.com", "Feedback Form Results",
    $message, "From: $email" );
  header( "Location: http://www.big-andy.co.uk/contact/feedback.php" );
?>
