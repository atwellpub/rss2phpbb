<?php
include_once('../../config.php');
include_once('../connect.php');


//add tables if dont exits
$query = "SELECT * FROM `".$table_prefix."rss2mysql`";
$result = @mysql_query($query);
if (!$result)
{

	$query = "CREATE TABLE `".$table_prefix."rss2mysql` (";
	$query .= "`id` INT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,";
	$query .= "`username` VARCHAR( 300 ) NOT NULL ,";
	$query .= "`forum_url` VARCHAR( 300 ) NOT NULL,";
	$query .= "`forum_id` VARCHAR( 300 ) NOT NULL,";
	$query .= "`rss_feed` VARCHAR( 300 ) NOT NULL,";
	$query .= "`rss_limit` INT( 3 ) NOT NULL)";

	$result = mysql_query($query);
	if (!$result){echo $query; echo mysql_error();exit;} 
}

$bot_username = $_POST['bot_username'];
$forum_url = $_POST['forum_url'];
$forum_id = $_POST['forum_id'];
$rss_feed = $_POST['rss_feed'];
$rss_limit = $_POST['rss_limit'];

$query = "SELECT * FROM `".$table_prefix."rss2mysql`";
$result = @mysql_query($query);
$count = mysql_num_rows($result);
if ($count==0)
{
$query =  "INSERT INTO ".$table_prefix."rss2mysql (`username`, `forum_url`, `forum_id`,`rss_feed`,`rss_limit`) VALUE ('$bot_username' , '$forum_url' ,'$forum_id','$rss_feed','$rss_limit')";
$result = mysql_query($query);
if (!$result){echo $query; echo mysql_error();exit;}
}
else
{
$query =  "UPDATE ".$table_prefix."rss2mysql SET username='$bot_username' , forum_url = '$forum_url' , forum_id = '$forum_id' , rss_feed = '$rss_feed', rss_limit = '$rss_limit'  WHERE id='1'";
$result = mysql_query($query);
if (!$result){echo $query; echo mysql_error();exit;}
} 


//done send back
header ("Location: ../index.php?saved=y");
?>