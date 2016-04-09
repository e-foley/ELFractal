<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Russo+One" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
@import url(http://www.elfractal.com/styleguide.css);
.note {line-height: 10px; font-size: 9px;}
</style>
<link rel="shortcut icon" href="http://www.elfractal.com/misc/favicon.ico" type="image/vnd.microsoft.icon" />
<title>Empyreal Light - Update Adder</title>


<script type="text/javascript">

var badCharsForGuess = /[.,]$/g;
var badCharsForPhotobucket = /[^A-Za-z0-9\-]/g;
var listSeparator = /,/g;
var commaCombo = /,  /g;
var DEFAULT_GALLERY = 4;
var DEFAULT_QUALITY = 5000;
var DEFAULT_ASPECT = 1.25;

function guessFractals() {

var updateString = document.updateform.text.value;

var i = 0;
var list = new Array(0);
var begQuote = 0;
var endQuote = 0;
var add = "";

while (updateString.indexOf("\"", i) > -1 && i > -1) {
    //Set the beginning of the fractal name to the character after the first quote mark.
  begQuote = updateString.indexOf("\"", i)+1;
    //Set the end of the fractal name to the character before the second quote mark.
  endQuote = updateString.indexOf("\"", begQuote);
  if (endQuote > begQuote) {
      //Add the content between and including the two positions as the fractal name. Note, it may still have a punctuation mark.
    add = (updateString.substring(begQuote, endQuote)).replace(badCharsForGuess,""); 
  if (((String)(list)).indexOf(add) == -1)
    list.push(add);
    //If endQuote is equal to -1, as it could be if there is an odd number of quotes, keep it at -1, don't increase to 0.
  i = endQuote+(endQuote > -1);
  }
}

var listString = new String();
listString = (String)(list);
//Does some fancy footwork with the commas. See the dilemma I had when programming this?
listString = listString.replace(listSeparator, ", ").replace(commaCombo, ",");


document.updateform.fractals.value = listString;

}

function generateFractalTable(ID, title)
{
var tableString = "";
tableString += ("<table style='background-color: #115; border-size: 1px; border-style: solid; border-color: #AAA;'>");
tableString += ("<tr><td colspan='3' style='background-color: #002'>Fractal " + (parseInt(ID)+1) + ": " + title.replace(listSeparator, ', ') + "</td></tr>");
tableString += ("<tr><td>Visible</td><td><input type='checkbox' name='isVisible" + ID + "' checked /></td>");
tableString += ("<td rowspan='8' style='text-align: center;'><img src='http://i20.photobucket.com/albums/b210/lotht/EL" + DEFAULT_GALLERY + "/" + title.replace(badCharsForPhotobucket, '') + "-t.png' style='margin: 3px; border-size: 1px; border-style: solid; border-color: #FFF;' />Thumbnail</td></tr>");
tableString += ("<tr><td>Gallery</td><td><input type='text' name='gallery" + ID + "' value='" + DEFAULT_GALLERY + "'/></td></tr>");
tableString += ("<tr><td>For sale</td><td><input type='checkbox' name='isForSale" + ID + "'checked /></td></tr>");
tableString += ("<tr><td>Price</td><td><input type='text' name='price" + ID + "' value='-1'/></td></tr>");
tableString += ("<tr><td>Program</td><td><input type='radio' name='program" + ID + "' value='Apophysis' onclick='document.updateform.quality" + ID + ".value=" + DEFAULT_QUALITY +"' checked/>Apophysis<br/><input type='radio' name='program" + ID + "' value='Incendia' onclick='document.updateform.quality" + ID + ".value=-1' />Incendia</td></tr>");
tableString += ("<tr><td>Quality</td><td><input type='text' name='quality" + ID + "' value='" + DEFAULT_QUALITY + "'/></td></tr>");
tableString += ("<tr><td>Sizes</td><td><input type='text' name='addSizes" + ID + "' value='-g-t-p'/></td></tr>");
tableString += ("<tr><td>Aspect</td><td><input type='text' name='aspect" + ID + "' value='" + DEFAULT_ASPECT + "'/></td></tr>");
tableString += ("<tr><td>Comments</td><td><input type='text' name='renderComments" + ID + "' value=''/></td></tr>");
tableString += ("</table><br/>");
return tableString;
}

function populateFields()
{
  var fractalTable = "";
  var fractalNames = document.updateform.fractals.value.split(", ");
  for (i in fractalNames) {
    fractalTable += generateFractalTable(i, fractalNames[i]);
  }
  document.getElementById("fractalTD").innerHTML = fractalTable;
}

</script>


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
  echo("<table class='content navigation VIP'><tr><td><span class='navlink'>Add&nbsp;Update</span></td></tr></table>");
?>

<table class="content body"><tr><td>

<form action="addupdate2.php" method="post" name="updateform">

<table style="font-weight: bold;"><tr>

<td width="250">Update Title<br /><span class="note">Doesn't do anything yet.</span></td>
<td width="450"><input type="text" name="title" value="" /></td>

</tr><tr>

<td>Date Added<br /><span class="note">The date should be in YYYY-MM-DD format. This date is used both for the update and added fractals, if applicable.</span></td>
<script type="text/javascript">

var d = new Date();
var curdate = d.getDate();
var curmonth = d.getMonth() + 1;
var curyear = d.getFullYear();

if (curdate < 10)
  curdate = "0" + curdate;
if (curmonth < 10)
  curmonth = "0" + curmonth;

document.write("<td><input type='text' name='dateAdded' value='" + curyear + "-" + curmonth + "-" + curdate + "'></td>");

</script>
</tr><tr>

<td>Number of Updates Today<br \><span class="note">The number of updates posted today including this one.</span></td>
<td><input type="text" name="updateNo" value="1" /></td>

</tr><tr>

<td>Update Text<br /><span class="note">Enjoy!</span></td>
<td><textarea name="text" rows="15" cols="40"></textarea></td>

</tr><tr>

<td>Associated Fractals
<input type="button" name="guess" value="Guess" onclick="guessFractals();" style="font-size: 14px; font-family: arial; color: #FFFFFF; font-weight: bold; background-color: #000066; width: 80px; padding: 0px;" />
<br /><span class="note">Separate with comma and space. Use no spaces after commas contained within fractal names. They will be re-added automatically. Leave blank if no fractal is to be added.</span>

</td>
<td><textarea name="fractals" rows="5" cols="40"></textarea></td>

</tr><tr>

<!--<td>Fractals Visible</td>
<td><input type="checkbox" name="isVisible" checked /></td>

</tr><tr>

<td>Fractal Gallery Number</td>
<td><input type="text" name="gallery" value="3"/></td>

</tr><tr>

<td>Additional Sizes for Fractals<br /><span class="note">Type all sizes, preceding each with a hyphen. Use no spaces or commas.</span></td>
<td><input type="text" name="addSizes" value="-g-t-p"/></td>

</tr><tr>-->

<!--<td>Fractal Attributes</td>
<td>
   Start the fractal table
<table style='font-size: 10px;'>
<tr>
<td>Name</td>
<td>Gallery</td>
<td>Visible?</td>
<td>For sale?</td>
<td>Price</td>
<td>Program</td>
<td>Quality</td>
<td>Other sizes</td>
<td>Comments</td>
</tr>
</table>

</td>-->


<td style='vertical-align: top'><span>Fractal Attributes</span> <input type="button" name="populate" value="Populate" onclick="populateFields();" style="font-size: 14px; font-family: arial; color: #FFFFFF; font-weight: bold; background-color: #000066; width: 80px; padding: 0px;" /></td>

<td id='fractalTD'>

<!--<table style="background-color: #115; border-size: 1px; border-style: solid; border-color: #AAA;">
<tr><td colspan="2" style="background-color: #002">Fractal 1: Title</td></tr>
<tr><td>Visible</td><td><input type="checkbox" checked /></td></tr>
<tr><td>Gallery</td><td><input type="text" value="3"/></td></tr>
<tr><td>For sale</td><td><input type="checkbox" checked /></td></tr>
<tr><td>Price</td><td><input type="text" value="-1"/></td></tr>
<tr><td>Program</td><td><input type="radio" name="program" value="Apophysis" checked/>Apophysis<br/><input type="radio" name="program" value="Incendia" />Incendia</td></tr>
<tr><td>Render quality</td><td><input type="text" value="4000"/></td></tr>
<tr><td>Sizes</td><td><input type="text" value="-g-t-p"/></td></tr>
<tr><td>Comments</td><td><input type="text" value=""/></td></tr>
</table>-->

</td>


<!--<td>Fractals For Sale</td>

<td><input type="checkbox" name="isForSale" checked /></td>

</tr><tr>

<td>Price of Fractals<br /><span class="note">Enter "-1" for default fractal price.</span></td>
<td><input type="text" name="price" value="-1"/></td>

</tr><tr>

<td>Fractal Render Comments</td>
<td><input type="text" name="renderComments" value="Resized with Apo"/></td>

-->

</tr><tr>

<td></td>
<td style="text-align: right"><input type="submit" name="submit" value="Add Update" style="font-size: 20px; font-family: arial; color: #FFFFFF; font-weight: bold; background-color: #000066; width: 200px;" /></td>

</tr></table>
</form>

</table>



<table class="content special"><tr><td>

<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" style="border:0;width:88px;height:31px" alt="Valid XHTML 1.0 Transitional" /></a>
<a href="http://jigsaw.w3.org/css-validator/check/referer"><img src="http://jigsaw.w3.org/css-validator/images/vcss" style="border:0;width:88px;height:31px" alt="Valid CSS!" /></a>

</td></tr></table>

</div>

</body>
</html>