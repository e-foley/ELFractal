<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
@import url(http://www.elfractal.com/styleguide.css);
</style>
<link rel="shortcut icon" href="http://www.elfractal.com/misc/favicon.ico" type="image/vnd.microsoft.icon" />
<title>Empyreal Light - Error</title>
</head>
<body>

<div align="center">

<table class="content top"><tr><td>
<span class="title"><a href="http://www.elfractal.com" class="titlelink">Empyreal Light</a> -&nbsp;</span><span class="title smaller">Error</span>

</td></tr></table>


<table class="content navigation"><tr><td>
<a href="/./gallery.php" class="navlink">Gallery</a>
<a href="/./about.php" class="navlink">About</a>
<a href="/./updates.php" class="navlink">Updates</a>
<a href="/./feedback.php" class="navlink">Feedback</a>
<!--<a href="donate.php" class="navlink">Donate</a>-->
</td></tr></table>

<table class="content body"><tr><td>

<div class="section">Oops! There was an error

<?php
$error = $_GET['error'];
if ($error == "")
  $error = 0;
if ($error != 0)
  echo("(#" . $error . ")");
echo("</div>\n<div>");
if ($error == 1203)
  echo("Due to an unusual number of connections, the database responsible for the gallery and site updates cannot be accessed. Please wait a moment, press the back button on your browser, and try again. I apologize for the inconvenience.");
else
  echo("An error has occurred. Please press the back button on your browser and try refreshing the page. If this error persists, please report the error number (" . $error . ") to the webmaster using the <a href='/./feedback.php'>feedback section</a>. I apologize for the inconvenience.");
echo("</div>")
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

<!--Site launched 5/20/07-->
<!--Site started 5/18/07-->
</body>
</html>