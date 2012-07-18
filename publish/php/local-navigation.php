<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

$current_url = str_replace("www.", "", curPageURL());


echo $PageURL;
?>

<?php

//if ($current_url == "

?>


	
<?php
function curPageName() {
 return ($_SERVER["SCRIPT_NAME"]);
}

echo "The current page name is ".curPageName();
?>
