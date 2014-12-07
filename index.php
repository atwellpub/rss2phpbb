<?php
include_once('../config.php');
include_once('connect.php');
$query = "SELECT * FROM `".$table_prefix."rss2mysql` WHERE id=1";
$result = mysql_query($query);
if ($result)
{
	while ($arr = mysql_fetch_array($result))
	{
		$bot_username = $arr['username'];
		$rss_feed = $arr['rss_feed'];
		$rss_limit = $arr['rss_limit'];
		$forum_url = $arr['forum_url'];
		$forum_id = $arr['forum_id'];
	}
}
?>
<html>
<head>
<title>RSS 2 PHPBB</title>
<meta name=description content="">
<meta name=keywords content="">
<style type="text/css">@import url("style.css");</style>
<link href="./includes/colorbox/colorbox.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="./includes/jquery.js"></script>
<script src="./includes/colorbox/colorbox.js" type="text/javascript"></script>  
   
<script type="text/javascript"> 
                                    
$(document).ready(function() 
{

}); 
</script>

</head>
<body>

<div class="header_container">
    <table>
	    <tr>
		    <td align=left> 
				<a id="setup" href="index.php?p=1" class="jquery_header_link">setup</a>  &nbsp;&nbsp;  
				<a id="loader" href="<?php echo $forum_url; ?>" class="jquery_header_link" target=_blank>forum</a>  &nbsp;&nbsp;
				<a id="loader" href="http://www.rss2mysql.com/forums/" class="jquery_header_link" target=_blank>support</a>  &nbsp;&nbsp; 					
			</td>
			<td  width=438>
		
			</td>
	    </tr>
    </table>
</div>
<div class="main_container">
<div id=wordpress_settings>	
		<?php
		if ($_GET['saved']==y)
		{
			echo "<center><font color=green><i>saved!</i></font></center>";
		}
		?>

		<form id='form_load' action="includes/db_functions.php" method=POST>
		<table width=335 style="margin-left:auto;margin-right:auto;border: solid 1px #eeeeee"> 
		  <tr>
			 <td  align=left style="font-size:13px;">
				Bot Username:
			 </td>
			 <td align=right style="font-size:13px;">
				<input name=bot_username size=30 value="<?php echo $bot_username; ?>">
			 </td>
		  </tr>		  
		</table> 
		<br>
		
		<table width=335 style="margin-left:auto;margin-right:auto;border: solid 1px #eeeeee"> 
		  <tr>
			 <td  align=left style="font-size:13px;">
				Forum URL:
			 </td>
			 <td align=right style="font-size:13px;">
				<input name=forum_url size=30 value="<?php echo $forum_url; ?>">
			 </td>
		  </tr>
		  <tr>
			 <td  align=left style="font-size:13px;">
				Forum ID:
			 </td>
			 <td align=right style="font-size:13px;">
				<input name=forum_id size=30 value="<?php echo $forum_id; ?>">
			 </td>
		  </tr>
		  <tr>
			 <td  align=left style="font-size:13px;">
				RSS Feed:
			 </td>
			 <td align=right style="font-size:13px;">
				<input name=rss_feed size=30 value="<?php echo $rss_feed; ?>">
			 </td>
		  </tr>
		 <tr>
			 <td  align=left style="font-size:13px;">
				Limit Items:<br> <i> 0 for nolimit</i>
			 </td>
			 <td align=right valign=top style="font-size:13px;">
				<input name=rss_limit size=1 value="<?php echo $rss_limit; ?>">
			 </td>
		  </tr>			  
		</table> 
		<br>

		<br>
		<center><input type=submit value='Save' style='color:#000000'></center>
		</form>		
</div>
<br>





<!------------------------------->
</div>
<div class="footer_container">
<br><br><br><br><br>
</div>
<br>

</body>
</html>