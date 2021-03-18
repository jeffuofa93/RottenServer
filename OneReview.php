<!--
Jeff Wiederekehr
Server Rancid Reviews
      -->
<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <link href="movies.css" type="text/css" rel="stylesheet">

</head>
<body>
<div class="bannerHead">
    <img src="images/rancidbanner.png" alt="Rancid Tomatoes">
</div>
<?php

$folder = $_GET ['movieDir'];
$rating = 0;
echo printTitle($rating,$folder);
$freshRottenImg = freshOrRottenLarge($rating);
echo "<div class='container'>";
echo "<div class ='header'>{$freshRottenImg}<p>{$rating}%</p></div>";
echo "	<div class='overview'><img src='{$folder}/overview.png' alt='general overview' /></div>";
echo printReviews($folder);
echo printOverview($folder);
echo "<div class='fillbottom'></div>";
echo "</div>";


function freshOrRottenLarge($rating) {
    if ($rating >= 50) {
        return "<img src='images/freshlarge.png' alt='review' height='83px'/>";
    } return "<img src='images/rottenlarge.png' alt='review'/>";
}


function freshOrRottenSmall($reviewScoreString) {
    if ($reviewScoreString === "FRESH")
        return "images/fresh.gif";
    return "images/rotten.gif";
}

function printTitle(&$rating,$folder) {
    $infoFile = file("./{$folder}/info.txt");
    $title = "{$infoFile[0]}";
    $year = "{$infoFile[1]}";
    $rating = $infoFile[2];
    return "<h1 class='headerText'> {$title} ({$year})</h1><br><br>";
}

function trimVal(&$val) {
    $val = trim($val);
}

function printOverview($folder) {
    $overviewFile = file("./{$folder}/overview.txt");
    $retString = "<dl class='rightTable'>";
    foreach ($overviewFile as $line){
        $splitIndex = strpos($line,":");
        $lineHeader = substr($line,0,$splitIndex+1);
        $lineContent = substr($line,$splitIndex+1);
        $retString .= "<dt>{$lineHeader}</dt><dd>{$lineContent}</dd>";
    }
    $retString .= "</dl>";
    return $retString;
}

function printReviews($folder) {
    $retString = "";
    $reviewFiles = glob('./'.$folder.'/r*.txt');
    $midpoint = intdiv(count($reviewFiles),2);
    $counter = 0;
    $retString.= "<div class='right_column'>";
    foreach ($reviewFiles as $files) {
        if ($counter == $midpoint) {
            $retString .= "</div>";
            $retString .= "<div class='right_column'>";
        }
        $curRevFile = file($files);
        array_walk($curRevFile, 'trimVal');
        $reviewText = trim($curRevFile[0]);
        $freshRottenImg = freshOrRottenSmall($curRevFile[1]);
        $reviewerName = $curRevFile[2];
        $reviewerCompany = $curRevFile[3];
        $retString .= "<p class ='reviewTest'><img src='{$freshRottenImg}' alt='Review'><q>{$reviewText}</q></p>";
        $retString .= "<p class='person'><img src='images/critic.gif' alt='Critic'/> {$reviewerName} <br/> {$reviewerCompany}</p>";
        $counter++;
    }
    $retString .= "</div>";
    return $retString;
}

?>
