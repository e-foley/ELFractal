<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
@import url(http://www.elfractal.com/styleguide.css);
</style>
<link rel="shortcut icon" href="http://www.elfractal.com/misc/favicon.ico" type="image/vnd.microsoft.icon" />
<title>Empyreal Light - Update Adder</title>
</head>
<body>

<div class="bigdiv">

<table class="content top"><tr><td>
<span class="title"><a href="http://www.elfractal.com" class="titlelink">Empyreal Light</a> -&nbsp;</span><span class="title smaller">Update Adder</span>
</td></tr></table>

<table class="content navigation"><tr><td>
<a href="http://www.elfractal.com/gallery.php" class="navlink">Gallery</a>
<a href="http://www.elfractal.com/about.php" class="navlink">About</a>
<a href="http://www.elfractal.com/updates.php" class="navlink">Updates</a>
<a href="http://www.elfractal.com/feedback.php" class="navlink">Feedback</a>
<!--<a href="../donate.php" class="navlink">Donate</a>-->
</td></tr></table>

<?php
if ($_COOKIE["VIP"])
  echo("<table class='content navigation VIP'><tr><td><a href='misc/addupdate.php' class='navlink'>Add&nbsp;Update</a></td></tr></table>");
?>

<table class="content body"><tr><td>

<?php
 
//CHECKS TO ENSURE FRACTAL CAN BE ADDED=====================================================================

$ENABLE_UPDATE_ADDING = true;
$ENABLE_FRACTAL_ADDING = true;
//Two constants below can be modified iff the if statement qualifies it.
$AUTHORIZED_UPDATE = false;
$AUTHORIZED_FRACTAL = false;

if (substr_count($_COOKIE["permissionCode"], "334554321123322") >= 1)
  $AUTHORIZED_UPDATE = true;

if (substr_count($_COOKIE["permissionCode"], "11556654433221") >= 1)
  $AUTHORIZED_FRACTAL = true;

//CONNECT===================================================================================================
$con = mysqli_connect("fdb2.awardspace.com","empyreal_fracts","junkpassword","empyreal_fracts");
if (!$con)
{
  die('Could not connect: ' . mysqli_connect_error());
}

//USEFUL METHODS============================================================================================

function formatStatus($name, $isSuccessful, $reason) {
  $message = "";
  if($isSuccessful)
    $message .= $name . " was <span style='color: #00FF00'>successfully</span> added.";
  else
    $message .= $name . " was <span style='color: #FF0000'>not</span> added.";

  if($reason != "")
      $message .= " (" . $reason . ")";
  $message .= "<br />\n";
  return $message;
}

//UPDATE ADDING PORTION=====================================================================================
echo("<div><span style='font-weight: bold'>Update Status</span><br />");
$numUpdatesBefore = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `EL Updates`"));
$escapedTitleText = addslashes($_POST['title']);
$escapedUpdateText = addslashes($_POST['text']);
$queryText = "INSERT INTO `EL Updates` ( `ID` , `name` , `date` , `updateInDay` , `text` ) VALUES ( NULL, '" . $escapedTitleText . "','" . $_POST['dateAdded'] . "','" . $_POST['updateNo'] . "','" . $escapedUpdateText . "')";
if ($_POST['text'] == "")
  echo("No update specified.<br />\n");
else if (!$ENABLE_UPDATE_ADDING)
  echo(formatStatus("The update", false, "Update adding disabled."));
else if (!$AUTHORIZED_UPDATE) 
  echo(formatStatus("The update", false, "You are not authorized to make updates."));
else {
  mysqli_query($con, $queryText);
  if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM `EL Updates`")) != ($numUpdatesBefore + 1))
    echo(formatStatus("The update", false, "The query was sent but it failed to add a new entry to the update table."));
  else echo(formatStatus("The update", true, ""));
}
echo("</div>");

//FRACTAL ADDING PORTION====================================================================================
echo("<p /><div><span style='font-weight: bold'>Fractal Status</span><br />");
$fractals = explode(", ", $_POST['fractals']);
if ($fractals[0] != "") {
  for ($index=0; $index<count($fractals); $index++) {
    //This adds a space to commas with no following space.
    //This is done so fractals with commas in their names (entered without a space) get the space back.
    str_replace(",", ", ", $fractals[$index]);
    $numFractalsBefore = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `Fractal List`"));
    $queryText = "INSERT INTO `Fractal List` ( `ID` , `title` , `isVisible` , `gallery` , `addSizes` , `isForSale` , `price` , `renderComments` , `date` , `program` , `quality` , `aspect`) VALUES ( NULL,'" . $fractals[$index] . "','" . ($_POST[('isVisible' . $index)] == 'on') . "','" . $_POST[('gallery' . $index)] . "','" . $_POST[('addSizes' . $index)] . "','" . ($_POST[('isForSale' . $index)] == 'on') . "','" . $_POST[('price' . $index)] . "','" . $_POST[('renderComments' . $index)] . "','" . $_POST['dateAdded'] . "','" . $_POST[('program' . $index)] . "','" . $_POST[('quality' . $index)] . "','" . $_POST[('aspect' . $index)] . "')";
    if (!$ENABLE_FRACTAL_ADDING)
      echo(formatStatus("\"" . $fractals[$index] . "\"", false, "Fractal adding disabled."));
    else if (!$AUTHORIZED_FRACTAL) 
      echo(formatStatus("\"" . $fractals[$index] . "\"", false, "You are not authorized to add fractals."));
    else {
      mysqli_query($con, $queryText);
      if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM `Fractal List`")) != ($numFractalsBefore + 1))
        echo(formatStatus("\"" . $fractals[$index] . "\"", false, "The query was sent but it failed to add a new entry to the fractal table."));
      else echo(formatStatus("\"" . $fractals[$index] . "\"", true, ""));
    }
  }
}
else
  echo("No fractals specified.<br />\n");

echo("</div>");
//ENDING MESSAGES===========================================================================================

mysqli_close($con);
?>

<br /><a href="addupdate.php">Back</a>

</td></tr></table>

<table class="content special"><tr><td>

<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" style="border:0;width:88px;height:31px" alt="Valid XHTML 1.0 Transitional" /></a>
<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://jigsaw.w3.org/css-validator/images/vcss" style="border:0;width:88px;height:31px" alt="Valid CSS!" /></a>

</td></tr></table>

</div>

</body>
</html>