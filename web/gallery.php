<?php
$notice = "";
$con = mysqli_connect("fdb2.awardspace.com","empyreal_fracts","junkpassword","empyreal_fracts");
$error = mysqli_connect_errno();
if ($error != 0) {
  header("Location: http://www.elfractal.com/misc/error.php?error=" . $error);
  exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
@import url(http://www.elfractal.com/styleguide.css);
</style>
<link rel="shortcut icon" href="http://www.elfractal.com/misc/favicon.ico" type="image/vnd.microsoft.icon" />
<title>Empyreal Light - Gallery</title>
</head>
<body>

<div class="bigdiv">

<table class="content top"><tr><td>
<span class="title"><a href="http://www.elfractal.com" class="titlelink">Empyreal Light</a> -&nbsp;</span><span class="title smaller">Gallery</span>
</td></tr></table>

<table class="content navigation"><tr><td>
<a href="updates.php" class="navlink">Updates</a>
<span class="navlink">Gallery</span>
<a href="feedback.php" class="navlink">Feedback</a>
<a href="about.php" class="navlink">About</a>
<!--<a href="donate.php" class="navlink">Donate</a>-->
</td></tr></table>

<?php
if ($_COOKIE["VIP"])
  echo("<table class='content navigation VIP'><tr><td><a href='misc/addupdate.php' class='navlink'>Add&nbsp;Update</a></td></tr></table>");

if ($notice != "")
  echo("<span style='color: #FFFF00; font-weight: bold;'>" . $notice . "</span>");

function getmicrotime() { 
    $temparray = explode(" ",microtime()); 
    $returntime = $temparray[0] + $temparray[1]; 
    return $returntime; 
}    
$starttime = getmicrotime();

//Constants to make displaying thumbnails easy.
//These constants may become obsolete if a more elegant design is implemented.
$MAX_THUMBNAIL_WIDTH = 80;
$MAX_THUMBNAIL_HEIGHT = 80;
//Others
$PAGE_NUMBER_BUFFER_LEFT = 3;
$PAGE_NUMBER_BUFFER_RIGHT = 3;
//Below variable accounts for how many numbers will be visible when either end of the number list is clicked
//In certain circumstances, one more than the value will be shown
$END_NUM_PAGES_SHOWN = 9;
//DEFAULTS AND CONSTANTS============================================================================================
$DEFAULT_PAGE = 1;
$DEFAULT_PERPAGE = 24;
$DEFAULT_NUMCOLS = 8;
$DEFAULT_SORT = "date";
//$DEFAULT_SORT = "dateAdded";
$DEFAULT_PROGRAM = "all";

if (!$con)
{
  die('Eek! there seems to be an error: ' . mysqli_connect_error());
}

//Returns the title, formatted so it has no spaces or exclamation points.
//The exclamation mark part is because of "Hey! It Looks like a Bubble!"
function getSimpleTitle($title) {
  $returnString = str_replace(" ", "", $title);
  $returnString = str_replace(array("!","'"), "", $returnString);
  return $returnString;
}

if (is_Numeric($_GET['page']))
  $page = max(1, floor($_GET['page']));
 else
  $page = $DEFAULT_PAGE;

if (is_Numeric($_GET['per']))
  $perPage = max(1, floor($_GET['per']));
 else
  $perPage = $DEFAULT_PERPAGE;

if (is_Numeric($_GET['cols']))
  $numCols = max(1, floor($_GET['cols']));
 else
  $numCols = $DEFAULT_NUMCOLS;

if ($_GET['sort']=="title") {
  $sort = "title";
  $sortStyle = "ASC";
}
 else {
  $sort = $DEFAULT_SORT;
  $sortStyle = "DESC";
}

if ($_GET['program'] != null)
  $program = $_GET['program'];
 else
  $program = $DEFAULT_PROGRAM;






//If there is a fractal called for in the URL, display it.
//$safename = urldecode($_GET['pic']);
//$fname = preg_replace("/[^a-zA-Z0-9s]/", "", $fname);
//$fgallery = preg_replace("/[^a-zA-Z0-9s]/", "", $_GET['gallery']);

//Should probably protect this better by using regular expression to protect from hackers.
$bigFractal = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `Fractal List` WHERE title='" . urldecode($_GET['pic']) . "' AND isVisible='1'"));

if ($bigFractal != false) {
  echo "<table class='content gallery'><tr><td>";
  echo "<img id='mainpic' class='mainpic' src='" . getFractalAddress($bigFractal['gallery'], $bigFractal['title'], "p", "png") . "' alt=\"" . $bigFractal['title'] . "\" title=\"" . $bigFractal['title'] . "\" onclick='this.src=\"http://i20.photobucket.com/albums/b210/lotht/EL1/select.gif\"' />";
  echo "<br /><div class='titletext'>&ldquo;" . $bigFractal['title'] . "&rdquo; - &copy; " . substr($bigFractal['date'], 0, 4) . " by Edward Foley</div>";
  echo "<div class='titletext'>Sizes: " . getSizesText($bigFractal['addSizes'], $bigFractal['title'], $bigFractal['gallery'], $bigFractal['aspect']) . "</div></td></tr>";
  echo "</table>";
}



$programClause = "";
if ($program !== "all")
  $programClause = "AND program='" . $program . "' ";

$numFractalsApplicable = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `Fractal List` WHERE 1 " . $programClause));
$fractals = mysqli_query($con, "SELECT * FROM `Fractal List` WHERE isVisible=1 " . $programClause . "ORDER BY `" . $sort . "` " . $sortStyle . ",`ID` DESC LIMIT " . ($perPage*($page-1)) . ", " . $perPage);
$numFractals = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `Fractal List` WHERE isVisible=1"));


//PAGE NUMBER FUNCTION================================================================================

function addMark($trigger) {
  if($trigger)
    return "&amp;";
  else
    return "?";
}

function condenseURL($page, $per, $cols, $pic, $gallery, $sort, $program) {
  global $DEFAULT_PAGE, $DEFAULT_PERPAGE, $DEFAULT_NUMCOLS, $DEFAULT_SORT, $DEFAULT_PROGRAM;
  $shortURL = "gallery.php";
  $ampTrigger = 0;
  if ($page != $DEFAULT_PAGE) {
    $shortURL .= (addMark($ampTrigger) . "page=" . $page);
    $ampTrigger = 1;
  }
  if ($per != $DEFAULT_PERPAGE) {
    $shortURL .= (addMark($ampTrigger) . "per=" . $per);
    $ampTrigger = 1;
  }
  if ($cols != $DEFAULT_NUMCOLS) {
    $shortURL .= (addMark($ampTrigger) . "cols=" . $cols);
    $ampTrigger = 1;
  }
  if ($sort != $DEFAULT_SORT) {
    $shortURL .= (addMark($ampTrigger) . "sort=" . $sort);
    $ampTrigger = 1;
  }
  if ($pic != "") {
    $shortURL .= (addMark($ampTrigger) . "pic=" . $pic);
    $ampTrigger = 1;
  }
  if ($program != $DEFAULT_PROGRAM) {
    $shortURL .= (addMark($ampTrigger) . "program=" . $program);
    $ampTrigger = 1;
  }

  return $shortURL;
}


function getSizesText($sizeString, $title, $gallery, $aspect) {
  $returnString = "";
  $sizesList = explode("-", $sizeString); 
 
  //I start at 1 so I skip the initial hyphen that would be included otherwise.
  for($i=1; $i < count($sizesList); $i++) {
    $sizesList[$i] = letterToLink($sizesList[$i], $title, $gallery, $aspect);
    $returnString .= $sizesList[$i];
    if ($i < (count($sizesList) - 1))
      $returnString .= " "; 
  }
 return $returnString;
}

function letterToLink($letter, $title, $gallery, $aspect) {

$extension = "";
$width = 0;
$height = 0;

//Pretend that the thumbnail and glimpse sizes don't exist...
switch($letter) {
  /*case "g":
    $extension = "jpg";
    $width = 80;
    $height = round($width / $aspect);
    break;
  case "t":
    $extension = "png";
    $width = 160;
    $height = round($width / $aspect);
    break;*/
  case "p":
    $extension = "png";
    if ($aspect < 1.0) {
       $width = round(640.0 * $aspect);
       $height = 640;
    } else {
       $width = 640;
       $height = round(640.0 / $aspect);
    }
    break;
  case "s":
    $extension = "jpg";
    if ($aspect < 1.0) {
       $width = round(1280.0 * $aspect);
       $height = 1280;
    } else {
       $width = 1280;
       $height = round(1280.0 / $aspect);
    }
    break;
  case "sw":
    $extension = "jpg";
    $width = 1920;
    $height = 1200;
    break;
  }
  



  if ($extension != "" && $width > 0 && $height > 0) {
    return ("<a href='" . getFractalAddress($gallery, $title, $letter, $extension) . "' class='sizelink'>" . $width . "x" . $height . " (" . strtoupper($extension) . ")</a>");
  }
  return "";

}

function getFractalAddress($gallery, $title, $size, $extension) {
  return ("http://i20.photobucket.com/albums/b210/lotht/EL" . $gallery . "/" . getSimpleTitle($title) . "-" . $size . "." . $extension);
}
    

function findThumbnailDimensions($aspect) {
  global $MAX_THUMBNAIL_HEIGHT, $MAX_THUMBNAIL_WIDTH;
  $dimensions = array("width" => 0, "height" => 0);
  if ($aspect >= ($MAX_THUMBNAIL_WIDTH / $MAX_THUMBNAIL_HEIGHT)) {
    $dimensions["width"] = $MAX_THUMBNAIL_WIDTH;
    $dimensions["height"] = ($MAX_THUMBNAIL_WIDTH / $aspect);
  }
  else if ($aspect < ($MAX_THUMBNAIL_WIDTH / $MAX_THUMBNAIL_HEIGHT)) {
    $dimensions["width"] = ($MAX_THUMBNAIL_HEIGHT * $aspect);
    $dimensions["height"] = $MAX_THUMBNAIL_HEIGHT;
  }
  return $dimensions;
}

function getNavText($page, $numFractals, $perPage, $numCols, $sort, $program) {

  global $PAGE_NUMBER_BUFFER_LEFT, $PAGE_NUMBER_BUFFER_RIGHT, $END_NUM_PAGES_SHOWN;
  $maxPages = ceil($numFractals/$perPage);
  $returnString = "<span class='pnavtext'>\n";
  
  //If it isn't the first page, activate the "Previous" link.
  if ($page > 1)
    $returnString .= "<a href='" . condenseURL($page-1, $perPage, $numCols, "", "", $sort, $program) . "' class='pnavlink'>Previous</a>\n";

  //Start the parentheses containing the page numbers
  $returnString .= "( ";

  //Always show the first page.
  if ($page != 1)
      $returnString .= "<a href='" . condenseURL(1, $perPage, $numCols, "", "", $sort, $program) . "' class='pnavlink'>". "1" ."</a>\n";
    else
      $returnString .= "1\n";

  $firstSelectablePage = max(min($page-$PAGE_NUMBER_BUFFER_LEFT, $maxPages-$END_NUM_PAGES_SHOWN+1), 2);
  $lastSelectablePage = min(max($page+$PAGE_NUMBER_BUFFER_RIGHT, $END_NUM_PAGES_SHOWN), $maxPages-1);
  //We don't want ellipses to cover just one page link, so we make adjustments as necessary.
  $firstSelectablePage -= ($firstSelectablePage == 3);
  $lastSelectablePage += ($lastSelectablePage == $maxPages-2);

  if ($firstSelectablePage >= 3)
    $returnString .= "...\n";

  for ($iPage=$firstSelectablePage; $iPage<=$lastSelectablePage; $iPage++) {
    if ($page != $iPage)
      $returnString .= "<a href='" . condenseURL($iPage, $perPage, $numCols, "", "", $sort, $program) . "' class='pnavlink'>". $iPage ."</a>";
    else
      $returnString .= $iPage;
    $returnString .= "\n";
  }

  if ($lastSelectablePage <= $maxPages - 2)
    $returnString .= "...\n";

  //Always show the last page, unless it is the only page, in which case it is taken care of
  if ($maxPages != 1) {
    if ($page != $maxPages)
      $returnString .= "<a href='" . condenseURL($maxPages, $perPage, $numCols, "", "", $sort, $program) . "' class='pnavlink'>". ($maxPages) ."</a>\n";
    else
      $returnString .= ($maxPages . "\n");
  }

  $returnString .= ") ";  
  if ($page < $maxPages)
    $returnString .= "<a href='" . condenseURL($page+1, $perPage, $numCols, "", "", $sort, $program) . "' class='pnavlink'>Next</a>\n";
  $maxPages = ceil($numFractals/$perPage);

  $returnString .= "</span>";
  return $returnString;
}

echo "<table class='gallerytable'>";

//First navigation bar (gone for now)
//echo "<tr><td colspan='" . min($numCols, $numFractalsApplicable-$start+1, $perPage) . "' class='pnavbox'>" . getNavText($page, $numFractalsApplicable, $perPage, $numCols, $sort, $program) . "</td></tr>\n";

$start = ($page-1)*$perPage+1;
$index = $start;

while($fractal = mysqli_fetch_array($fractals))
  {

  //if ($index >= $start && $index < ($start+$perPage)) {
  if (true) {
    $pageOrder = $index - $start + 1;
    //test if start of the row
    if (($pageOrder-1)%$numCols == 0)
      echo "<tr>";
    //$shortTitle = getSimpleTitle($fractal['title']);
    echo "<td class='thumbnailbox'>";
    echo "<a href='" . condenseURL($page, $perPage, $numCols, urlencode($fractal['title']), "", $sort, $program) . "' class='pnavlink'>";
    echo "<img src='" . getFractalAddress($fractal['gallery'], $fractal['title'], "g", "jpg") . "' class='thumbnail ";
    /*if (!strpos($fractal['addSizes'],"-g-t-p") && $fractal['addSizes'].strlen() > 6)
        echo("special");*/
    //echo ($fractal['addSizes'] . "595");
    if (strlen($fractal['addSizes']) > 6 && strpos($fractal['addSizes'],"-g-t-p") !== false)
        echo ("special");
        else echo ("normal");
    $thumbnailDimensions = findThumbnailDimensions($fractal['aspect']);
    echo "' style='width: " . $thumbnailDimensions["width"] . "px; height: " . $thumbnailDimensions["height"] . "px;'" . " alt=\"" . $fractal['title'] . "\" title=\"" . $fractal['title'] . "\" onclick=\"parent.document.getElementById('mainpic').src='" . getFractalAddress($fractal['gallery'], $fractal['title'], "p", "png") . "';\" />";
    echo "</a>";

     //cover name
    //echo "<br/><span class='name'>" . $index . "/" . $numFractals . ": ";
    //echo "<a href='http://i20.photobucket.com/albums/b210/lotht/EL";
    //echo $fractal['gallery'];
    //echo "/";
    //echo $shortTitle;
    //echo "-p.png' class='namelink'>";
    //echo $fractal['title'] . "</a></span>";
    echo("</td>\n");
    //test if end of the row
    if (($pageOrder)%$numCols == 0)
      echo "</tr>";
  }
  $index++;
}

//Need to end hanging row only if not filled
if (($pageOrder)%$numCols != 0)
  echo "</tr>";

$endtime = getmicrotime();

//Second navigation bar
echo "<tr><td colspan='" . min($numCols, $numFractalsApplicable-$start+1, $perPage) . "' class='pnavbox'><table class='pnavbox' style='border-collapse: collapse; padding: 0px;'><tr><td class='pnavleft'><span class='pnavtext'>Sort by: ";
if ($sort == "title") {echo "<a href='" . condenseURL(1, $perPage, $numCols, '', $gallery, "date", $program) . "' class='pnavlink'>Date</a> Title";}
if ($sort == "date")  {echo "Date <a href='" . condenseURL(1, $perPage, $numCols, '', $gallery, "title", $program) . "' class='pnavlink'>Title</a>";}
echo("</span>");

/*echo "</td><td style='text-align: center'><span class='gentime'>" . $numFractalsTotal . " fractals total. Page generated in " . round(($endtime - $starttime),3) . " seconds.</span>";*/
echo "</td><td class='pnavright'> " . getNavText($page, $numFractalsApplicable, $perPage, $numCols, $sort, $program) . "</td></tr>\n";

echo "<tr><td class='pnavleft'><span class='pnavtext'>Programs: ";
if ($program == "all") {echo "All ";} else {echo "<a href='" . condenseURL(1, $perPage, $numCols, '', $gallery, $sort, "all") . "' class='pnavlink'>All</a> ";}
if ($program == "Apophysis") {echo "Apophysis ";} else {echo "<a href='" . condenseURL(1, $perPage, $numCols, '', $gallery, $sort, "Apophysis") . "' class='pnavlink'>Apophysis</a> ";}
if ($program == "Incendia") {echo "Incendia ";} else {echo "<a href='" . condenseURL(1, $perPage, $numCols, '', $gallery, $sort, "Incendia") . "' class='pnavlink'>Incendia</a>";}
echo("</span></td>");

echo("<td></td></tr></table></td></tr>");

echo "</table>";


mysqli_close($con);
//echo("(Connection closed)");


//echo $endtime;
//echo "<span class='gentime'>" . $numFractalsTotal . " fractals total. Page generated in " . round(($endtime - $starttime),3) . " seconds.</span>";  
?>

<table class="content body"><tr><td>

<!--<div class="section">Page is currently under maintenance and will be unavailable. Thanks for your patience.</div>
<p />-->

<!--<div class="section">Purchasing a Fractal</div>
<div>To purchase a high resolution image of a fractal, click the blue "Buy Fractal" button below the thumbnail of the desired fractal, if it has one.
You will be taken to a PayPal page where you may pay via credit card or PayPal account.
Once I have confirmation of your payment, I will render your chosen fractal at 3000x2400 resolution at the same 4000 render quality and double oversample you see in the smaller preview images on this page.
This larger size is more than twenty times the resolution of the preview size above, and is suitable for printing 8"x10" printouts at 300DPI.
Be sure to enter your correct e-mail address, for I will send the fractal image to this account.
3000x2400 renders are currently priced at <b>$10.99</b>, but are subject to change.
</div>

<p />-->

<div class="section">Browsing Notes</div>
<div>Fractal thumbnails displayed with a white border have multiple download sizes available.
</div>

<p />

<div class="section">Copyright Notice and Redistribution Permissions</div>
<div>All <?php echo($numFractals);?> fractals presented on this website are &copy; 2007&ndash;2016 by Edward Foley.  You may use the images elsewhere provided you give credit and do not directly link to the image locations.
</div>

</td></tr></table>


<table class="content special"><tr><td>

<a href="http://validator.w3.org/check?uri=referer">
<img src="http://www.w3.org/Icons/valid-xhtml10"
style="border:0;width:88px;height:31px"
alt="Valid XHTML 1.0 Strict" />
</a>

<a href="http://jigsaw.w3.org/css-validator/check/referer">
<img src="http://jigsaw.w3.org/css-validator/images/vcss" 
style="border:0;width:88px;height:31px"
alt="Valid CSS!" />
</a>


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