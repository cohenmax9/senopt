<?php
	$host = "localhost";		//Connects the site to the senopt database. This stuff probably shouldn't be changed unless you know what you're doing.
	$user = "root";
	$pass = "";
	$conn = mysql_connect($host,$user,$pass);
	mysql_select_db("senopt");
?>