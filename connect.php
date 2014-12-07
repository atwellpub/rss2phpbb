<?php
//set up your database connect variables
//code to connect to database
$conn = mysql_pconnect($dbhost, $dbuser, $dbpasswd) or trigger_error(mysql_error(),E_USER_ERROR); 
@mysql_select_db($dbname) or die( "Unable to select database");

?>