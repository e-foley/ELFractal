<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
@import url(http://www.elfractal.com/styleguide.css);
</style>
<link rel="shortcut icon" href="http://www.elfractal.com/misc/favicon.ico" type="image/vnd.microsoft.icon" />
<title>Empyreal Light - Flame Fractal Gallery</title>
</head>
<body>

<div class="bigdiv">

<table class="content top"><tr><td>
<span class="title"><a href="http://www.elfractal.com" class="titlelink">Empyreal Light</a> -&nbsp;</span><span class="title smaller">Updates</span>

</td></tr></table>


<table class="content navigation"><tr><td>
<span class="navlink">Updates</span>
<a href="gallery.php" class="navlink">Gallery</a>
<a href="feedback.php" class="navlink">Feedback</a>
<a href="about.php" class="navlink">About</a>
<!--<a href="donate.php" class="navlink">Donate</a>-->
</td></tr></table>

<?php
if ($_COOKIE["VIP"])
  echo("<table class='content navigation VIP'><tr><td><a href='misc/addupdate.php' class='navlink'>Add&nbsp;Update</a></td></tr></table>");
?>

<table class="content body"><tr><td>

<?php

function getmicrotime() { 
    $temparray = explode(" ",microtime()); 
    $returntime = $temparray[0] + $temparray[1]; 
    return $returntime; 
}    
$starttime = getmicrotime();

$con = mysqli_connect("fdb2.awardspace.com","empyreal_fracts","junkpassword","empyreal_fracts");
if (!$con)
{
  die('Eek! there seems to be an error: ' . mysqli_connect_error());
}

function formatDate($date) {
  return substr($date, 5, 2) . "." . substr($date, 8, 2) . "." . substr($date, 2, 2);
  }

$DEFAULT_ENTRIES = 5;

if (is_Numeric($_GET['show']))
  $entries = max(0, floor($_GET['show']));
 else
  $entries = $DEFAULT_ENTRIES;

if ($_GET['show'] == "all")
  $updates = mysqli_query($con, "SELECT * FROM `EL Updates` ORDER BY `date` DESC, `updateInDay` DESC");
else
  $updates = mysqli_query($con, "SELECT * FROM `EL Updates` ORDER BY `date` DESC, `updateInDay` DESC LIMIT 0, " . $entries);

while($entry = mysqli_fetch_array($updates)) {
  echo("<span class='date'>" . formatDate($entry['date']));
  if ($entry['updateInDay'] > 1)
    echo("-" . $entry['updateInDay']);
  echo ("</span>\n<div class='update'>");
  echo(str_replace(". ", ".\n", $entry['text']));
  echo("</div>\n");
}

if ($_GET['show'] != "all")
  echo("<div style='text-align: center; margin-top: 10px;'><a href='updates.php?show=all'>See all " . "" . "updates</a></div>");
else
  echo("<div style='text-align: center; margin-top: 10px;'><a href='updates.php'>See " . $DEFAULT_ENTRIES . " most recent updates</a></div>");

mysqli_close($con);

$endtime = getmicrotime();
//echo "<span class='gentime'>Page generated in " . round(($endtime - $starttime),3) . " seconds.</span>";
?>

</td></tr></table>



<table class="content special"><tr><td>

<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" style="border:0;width:88px;height:31px" alt="Valid XHTML 1.0 Transitional" /></a>
<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://jigsaw.w3.org/css-validator/images/vcss" style="border:0;width:88px;height:31px" alt="Valid CSS!" /></a>

<!-- Start of StatCounter Code -->
<script type="text/javascript">
<!-- 
var sc_project=2575098; 
var sc_invisible=0; 
var sc_partition=25; 
var sc_security="40430d0f"; 
var sc_text=2; 
//-->
</script>

<a href="http://my.statcounter.com/project/standard/stats.php?project_id=2575098&amp;guest=1"><img src="http://www.statcounter.com/images/button2.png" alt="StatCounter" style="border:0;width:88px;height:31px" /></a><br />

<script type="text/javascript" src="http://www.statcounter.com/counter/counter_xhtml.js"></script><noscript><div class="statcounter"><a class="statcounter" href="http://www.statcounter.com/"><img class="statcounter" src="http://c26.statcounter.com/counter.php?sc_project=2575098&amp;java=0&amp;security=40430d0f&amp;invisible=0" alt="website metrics" /></a></div></noscript>
<!-- End of StatCounter Code -->
total page views

</td></tr></table>

</div>

<!--New layout 9/12/07 ("splash page")-->
<!--Site launched 5/20/07-->
<!--Site started 5/18/07-->
</body>
</html>
