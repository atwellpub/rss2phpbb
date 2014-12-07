<?php
//******************************************************************************
//HELPER FUNCTIONS*******************************************************
//******************************************************************************

function str_replace_once($remove , $replace , $string)
{
	// Looks for the first occurence of $needle in $haystack
	// and replaces it with $replace.
	$pos = strpos($string, $remove);
	if ($pos === false) 
	{
	// Nothing found
	return $haystack;
	}
	return substr_replace($string, $replace, $pos, strlen($remove));
}  

function quick_curl($link)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$link");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
    $string = curl_exec($ch);
    return $string;
}

function clean_cdata($input)
{
  if (strstr($input, "<![CDATA["))
  {
	$start = "<![CDATA[";
	$end  = "]]>";
	$input = get_string_between($input, $start, $end);
  }
  return $input;
}

function fix_encoding($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
}

function redundant_work($input){

  $input = str_replace("Ã©","é", $input);
  $input = str_replace("Ã£", "ã",$input);
  $input = str_replace("Ã§", "ç",$input);
  $input = str_replace("Ã³", "ó",$input);
  $input = str_replace("Ãµ", "a",$input);
  $input = str_replace('â', '"',$input);
  $input = str_replace("Ã´", "ô",$input);
  $input = str_replace("Ã", "í",$input);
  $input = str_replace("Ãº", "ú",$input); 
  $input = str_replace("íª", "ê",$input);
  $input = str_replace("í¡", "á",$input);
  $input = str_replace("íº", "ú",$input);
  
  
   return $input;
} 


function special_htmlentities($data)
{
   //$data = htmlentities($data);
   $data = str_replace("&lt;","<",$data);
   $data = str_replace("&gt;",">",$data);
   $data = str_replace("&amp;","&",$data);
   $data = str_replace('&quot;','"', $data);
   $data = fix_encoding($data);
   $data = redundant_work($data);
   return $data;
}

function array_clean($input)
{
  foreach($input as $key => $value) 
  {
	  if(trim($value) =="") 
	  {
		unset($input[$key]);
	  }
  }
  return $input;
}


function replace_trash_characters($input)
{
   $input = str_replace('—', '-',$input);
	$input = str_replace('’', '',$input);
	$input = str_replace('“', '"',$input);
	$input = str_replace('”', '" ',$input);
	$input = str_replace('&amp;', '&',$input);	
	$input = str_replace('&amp;rsquo;', '',$input);
	$input = str_replace('&amp;#x2019;', '',$input);
	$input = str_replace('&#x2019;', '',$input);
	$input = str_replace('&amp;amp;', '&amp;',$input);
	$input = str_replace('&amp;#x2018;', '',$input);
	$input = str_replace('&amp;#x201C;', '"',$input);
	$input = str_replace('&amp;#x201D;', '"',$input);
	$input = str_replace('&amp;#xE9;', '�',$input);
	$input = str_replace('&amp;quot;', '"',$input);
	$input = str_replace("&#039;", "",$input);
	$input = str_replace("–", "-",$input);	
	return $input;
}

function string_url_prepare($string)
{ 
        $string = trim($string);
	$replace = array(" ","&","*","%","#","@","~","/","'");
	$prepared_string = str_replace($replace,"-",$string);
	$replace = array('amp;','.',';',':','?','!','"','(',')','[',']',',','+','|');
	$prepared_string = str_replace($replace,"",$prepared_string);
	while (strstr($prepared_string, "--"))
	{
	   $prepared_string = str_replace("--","-", $prepared_string);
	}
	while (substr($prepared_string, -1)=="-")
	{
	  $prepared_string = substr($prepared_string, 0, -1);
	}
	while (substr($prepared_string, 0, 1)=="-")
	{
	  $prepared_string = substr($prepared_string, 1);
	}					
	
	return $prepared_string;
}

function spyntax($content)
{
  while (substr_count($content, "[")>0)
  { 
        $array = explode("[",$content);
	foreach ($array as $k=>$v)
	{ 
	
	  if ($k!=0)
	  {	  
		  
		  $array[$k] = "[".$array[$k];
		  //if ($k==2){  echo $array[$k]; echo "<br>"; }
		  
		  if (strstr($array[$k], "]"))
		  {
			  $meat = get_string_between($array[$k], "[","]");
			  
			  $options = explode("|", $meat);
			  $rand = array_rand($options, 1);
			  $winner = $options[$rand];
			  
			  //if ($k==2){  echo $meat; echo "<br>"; }
			  
			  $array[$k] = str_replace("[$meat]", $winner, $array[$k]);
		  }
		  //if ($k==2){  echo $array[$k]; exit; }
		 //echo $array[$k];
	  }
	  
	}
	//exit;
	$content = implode(" ", $array);
	//echo $content; exit;
  }
  return $content;
}


function strip_links($input)
{
    $input = html_entity_decode($input);
    $count = substr_count ($input, "<a");
    
    //echo $count; exit;
    //echo $input; exit;
	for ($i=0;$i<$count;$i++)
	{
	     $start = "<a";
	     $end =  "</a>";
	     $get = get_string_between($input, $start, $end);
	     $anchor = get_string_between("$get</a>",">","</a>");
	     $remove = "$start$get$end";
	     $input = str_replace_once($remove, $anchor, $input);
	}
	//echo $input; exit;
	return $input;
}


function get_string_between($string, $start, $end)
{
   $string = " ".$string;
     $ini = strpos($string,$start);
     if ($ini == 0) return "";
     $ini += strlen($start);   
     $len = strpos($string,$end,$ini) - $ini;
     return substr($string,$ini,$len);
}




function bbencode_images($description)
{
   
    
    $image_count = substr_count($description, "<img");

    //perpare arrays
    $image_urls = array();

    
    //prepare image name base
    $image_name = replace_trash_characters($title);
    $image_name = string_url_prepare($image_name);   
    $allowed = "/[^a-z0-9\\040\\.\\-\\_\\\\]/i"; 
    $image_name =  preg_replace($allowed,"",$image_name);
    $image_name = trim($image_name);
    
    for ($ic = 0; $ic < $image_count; $ic++) 
    {
        //get src string
	    $src_string = get_string_between($description, "<img", ">");
        $src_string = "<img$src_string>";
	
	   
        //determine image urls
	if (!strstr($src_string, 'src="'))
	{		
		$start = "src='";
		$end = "'";
	}
	else
	{
		$start = 'src="';
		$end = '"';
	}
	
    $image_urls[] = get_string_between($src_string, $start, $end);
	$description = str_replace_once($src_string, "image[$ic]", $description);
	}
	
	//insert new local urls  into text
	foreach($image_urls as $key =>$value) 
	{
		$value = trim($value);
		$description = str_replace_once($image[$key], '[img]$value[/img]', $description);
    }		
    return $description;
}



 
function new_topic($forum_id, $topic_title, $topic_content,$tp , $bot_username)
{		
	        $time = time();
		//get username id
		$query = "SELECT * FROM ".$tp."users WHERE username = '$bot_username'";
		$result = mysql_query($query);
		if (!$result){echo $query; echo mysql_error(); exit;}
		while ($arr = mysql_fetch_array($result))
		{
		   $user_id = $arr['user_id'];
		   //echo $user_id;exit;
		}
		//echo $bot_username;exit;
		$insert_query = "INSERT INTO ".$tp."topics (`topic_id` ,`forum_id` ,`icon_id` ,`topic_attachment` ,`topic_approved` ,`topic_reported` ,`topic_title` ,`topic_poster` ,`topic_time` ,`topic_time_limit` ,`topic_views` ,`topic_replies` ,`topic_replies_real` ,`topic_status` ,`topic_type` ,`topic_first_post_id` ,`topic_first_poster_name` ,`topic_first_poster_colour` ,`topic_last_post_id` ,`topic_last_poster_id` ,`topic_last_poster_name` ,`topic_last_poster_colour` ,`topic_last_post_subject` ,`topic_last_post_time` ,`topic_last_view_time` ,`topic_moved_id` ,`topic_bumped` ,`topic_bumper` ,`poll_title` ,`poll_start` ,`poll_length` ,`poll_max_options` ,`poll_last_vote` ,`poll_vote_change`)";
		$insert_query .= "VALUES (NULL , '$forum_id', '0', '0', '1', '0', '$topic_title', '$user_id', '$time', '0', '0', '0', '0', '0', '0', '', '$bot_username', '','', '$user_id', '$bot_username', '', '', '$time', '', '0', '0', '0', '', '0', '0', '1', '0', '0');";
		$insert_result = mysql_query($insert_query);
		if (!$insert_result){echo $insert_query; echo mysql_error(); exit;}
		$topic_id = mysql_insert_id();
		
		$insert_query = "INSERT INTO `".$tp."topics_watch` (`user_id`, `topic_id` ,`notify_status`)";
		$insert_query .= "VALUES ('$user_id', '$topic_id', '1');";
		$insert_result = mysql_query($insert_query);
		if (!$insert_result){echo $insert_query; echo mysql_error(); exit;}
		
		$insert_query = "INSERT INTO `".$tp."topics_track` (`user_id`, `topic_id` ,`forum_id`,`mark_time`)";
		$insert_query .= "VALUES ('$user_id', '$topic_id', '$forum_id', '$time');";
		$insert_result = mysql_query($insert_query);
		if (!$insert_result){echo $insert_query; echo mysql_error(); exit;}
		
		$insert_query = "INSERT INTO ".$tp."topics_posted (`user_id`, `topic_id`,`topic_posted`)";
		$insert_query .= "VALUES ('$user_id', '$topic_id', '1');";
		$insert_result = mysql_query($insert_query);
		if (!$insert_result){echo $insert_query; echo mysql_error(); exit;}
		
		$insert_query = "INSERT INTO ".$tp."posts (`post_id`, `topic_id` ,`forum_id`,`poster_id`,`icon_id`,`poster_ip`,`post_time`,`post_approved`,`post_reported`,`enable_bbcode`,`enable_smilies`,`enable_magic_url`,`enable_sig`,`post_username`,`post_subject`,`post_text`,`post_checksum`,`post_attachment`,`bbcode_bitfield`,`bbcode_uid`,`post_postcount`,`post_edit_time`,`post_edit_reason`,`post_edit_user`,`post_edit_count`,`post_edit_locked`)";
		$insert_query .= "VALUES ('', '$topic_id', '$forum_id','$user_id','0','','$time','1','0','1','1','1','1','','$topic_title','$topic_content','','0','','','1','0','','0','0','0');";
		$insert_result = mysql_query($insert_query);
		if (!$insert_result){echo $insert_query; echo mysql_error(); exit;}
		
		return 1;
}



function get_items($feed, $rss_limit,$table_prefix)
{	
    //echo $feed; exit;
    $string =quick_curl($feed);
    //echo $string; exit;  
	//prepare haircut
	if (strstr($string, "<item>"))
	{
		$entry_start = "<?xml";
		$entry_end ="<item>";
	}

	if (strstr($string, "<entry>"))
	{
		$entry_start = "<?xml";
		$entry_end ="<entry>";
	}  
		
	if (strstr($string, "http://www.google.com/reader"))
	{
		$entry_start = "";
		$entry_end = "";
	}		

	//scoop-out feed header information
	if ($entry_start)
	{
		$start = $entry_start;
		$end =  $entry_end;
		$remove = get_string_between($string, $start, $end);
		$string = str_replace ($remove, "" , $string);
	}
		
	//determine what the title opener looks like		   
	if (strstr($string, "<title>"))
	{
		$title_start =  "<title>";
		$title_end =  "</title>";
	}
	
	if (strstr($string, '<title type="html"'))
	{
		$title_start =  '<title type="html">';
		$title_end =  "</title>";
	}
		
	if (strstr($string, "<title type='text'>"))
	{
		$title_start =  "<title type='text'>";
		$title_end =  "</title>";
	}
		
	if (strstr($string, '<title type="text">'))
	{
		$title_start =  '<title type="text">';
		$title_end =  "</title>";
	}
				
	//echo $title_start;exit;
	//echo $string; exit;
	//determine what the description opener looks like
	if (strstr($string, "<description type='text'>"))
	{
		$description_start = "<description type='text'>";
		$description_end =  "</description>";
	}
	if (strstr($string, "<description>"))
	{
		$description_start =  "<description>";
		$description_end =  "</description>";
	}
	if (strstr($string, "<summary>"))
	{
		$description_start =  "<summary>";
		$description_end =  "</summary>";
	}
	if (strstr($string, "<content type='html'>"))
	{
		$description_start = "<content type='html'>";
		$description_end =  "</content>";
	}
	if (strstr($string, '<content type="html">'))
	{
		$description_start = '<content type="html">';
		$description_end =  "</content>";
	}
	if (strstr($string, '<content type="html">'))
	{
		$description_start = '<content type="html">';
		$description_end =  "</content>";
	}
			 
	//determine what the link opener looks like
	$close='';
	if (strstr($string, "<link>"))
	{
		$link_start = "<link>";
		$link_end = "</link>";
	}    
	if (strstr($string, "<link rel='alternate' type='text/html' href='"))
	{
		$link_start = "<link rel='alternate' type='text/html' href='";
		$link_end = "'";
	}
	if (strstr($string, '<feedburner:origLink>'))
	{
		$link_start = "<feedburner:origLink>";
		$link_end = "</feedburner:origLink>";
		$close =1;
	}

	if(strstr($string, '<link rel="alternate" type="text/html" href=')&&$close!=1)
	{
		//echo 1; exit;
		$link_start =  '<link rel="alternate" type="text/html" href="';
		$link_end = '"';
	}
		
	if(strstr($string, '<link rel="alternate" href=')&&$close!=1)
	{
		$link_start =  '<link rel="alternate" href="';
		$link_end = '"';
	}
		
	//count the # of articles in feed
	$item_count = substr_count($string, $title_start);
		
	//if limit imposed, bypass item count
	$true_limit = $rss_feeds_limit[$key];
	if ($true_limit!=0)
	{
		if ($true_limit<$item_count)
		{
			$item_count = $true_limit; 
		}
	}
		
	if ($rss_limit<$item_count&&$rss_limit!=0)
	{
	$item_count = $rss_limit;
	}
	//echo $item_count; exit;
	//*****************get rss items**********************************************//
	//for each item		
	for ($r=0;$r<$item_count;$r++)
	{	
		//pull the title from rss
		$start = $title_start;		
		$end = $title_end;
		$title = get_string_between($string, $start, $end);
		$remove = "$start$title$end";
		$replace = "";			   
		$string = str_replace_once($remove, $replace, $string);
									
		//pull the description from rss		
		$start = $description_start;
		$end = $description_end;					   
		$description = get_string_between($string, $start, $end);			   
		$remove = "$start$description$end";
		$string = str_replace ($remove, "" , $string); 
		//echo $description; exit;		
						
		//pull the link from rss
		$start = $link_start;
		$end = $link_end;
		$link = get_string_between($string, $start, $end); 
		$remove = "$start$link$end";
		$string = str_replace ($remove, "" , $string);
		//echo $link; exit;		
					
		//pull attached media files from rss if wordpress rss
		if (strstr("$value","wordpress.com"))
		{
			$start = "<item>";
			$end = "</item>";
			$media = get_string_between($string, $start, $end); 
			$remove = "$start$media$end";
			$string = str_replace_once ($remove, " " , $string);				

			//create an array of media urls from list if found
			$media_urls = array();
			$img_count = substr_count($media, '<media:content url="');
					
			for ($m=0;$m<$img_count;$m++)
			{
				 $start = '<media:content url="';
					$end ='"';
					$media_url = get_string_between($media, $start, $end); 
					$remove = "$start$media_url$end";
					$media = str_replace_once ($remove, "" , $media);				  
					$media_urls[] =  $media_url;
			}
				
			//attach media to bottom of description
			if (count($media_urls)>0)
			{
					for ($m=0;$m<count($media_urls);$m++)
					{
						 $description .="<br><center> [img]".$media_urls."[/img]<br></center>";
					}						
			}
		}      
				
		//clean description, title, and link of cdata tags if necceccary				
		$title = clean_cdata($title);
		$link = clean_cdata($link);
		$description = clean_cdata($description);

		//echo $title;exit;
		$title = strip_tags($title);
		$title = replace_trash_characters($title);
		$description = replace_trash_characters($description);
		$description = strip_tags($description, '<b><h1><h2><p><br><h3><a><span><li><ul><span><img><u><i><strong>');	
		$description = special_htmlentities($description);
		//echo $description;exit;
		//see if item is already posted
		$query = "SELECT * FROM ".$table_prefix."topics WHERE topic_title='$title'";
		$result = mysql_query($query);
		$count = mysql_num_rows($result);
		if ($count==0)
		{
		   $titles[] = $title;
		   $descriptions[] = $description;
		}
		
		
	}//foreach item
	$array = array($titles,$descriptions);
	return $array;
}
?>