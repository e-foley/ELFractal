<?php 
if (is_numeric($_POST['code']))
  setcookie("permissionCode", $_POST['code'], time()+($_POST['days']*60*60*24), "/");
else if($_POST['code'] == "VIP")
  setcookie("VIP", 1, time()+($_POST['days']*60*60*24), "/");

echo("Permission code: " . $_POST['code']);
echo("<br />");
echo("Effective for " . $_POST['days'] . " days (or " . ($_POST['days']*60*60*24) . " seconds).");
echo("<p />");
?>

Hopefully the cookie was added.