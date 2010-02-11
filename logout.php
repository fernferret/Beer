<?php 
	include "includes/config.php";
	include "includes/db.php";
	include $_UTIL."user.php";

	$user = new User();
	$user->logout();
?>
<meta http-equiv="refresh" content="0;index.php">