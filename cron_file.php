<?php
include_once('../config.php');
include_once('connect.php');
include_once('includes/helper_functions.php');

$query = "SELECT * FROM `".$table_prefix."rss2mysql` WHERE id=1";
$result = mysql_query($query);
while ($arr = mysql_fetch_array($result))
{
	$bot_username = $arr['username'];
	$rss_feed = $arr['rss_feed'];
	$rss_limit = $arr['rss_limit'];
	$forum_url = $arr['forum_url'];
	$forum_id = $arr['forum_id'];
	
}

$array = get_items($rss_feed, $rss_limit, $table_prefix);

$titles = $array[0];
$descriptions = $array[1];

//print_r ($array); exit;
//$post_title = $array[0];
//$post_content = $array[1];


if ($titles)
{
  foreach ($titles as $key =>$val)
  {
	// Post a topic
	$r='';
	$r = new_topic($forum_id, $titles[$key], $descriptions[$key],$table_prefix,$bot_username);
	
	echo "<b>Title:</b> $titles[$key]<br><br>";
	echo "<b>Post Body:</b> $descriptions[$key]";
	echo "<hr>";
  }

}













?>