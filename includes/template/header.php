<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php $start = microtime(); ?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <title>Beer.</title>

    <meta name="copyright" content="Copyright 2010">
    <meta name="description" content="Beer is a database for rating and finding new beers to enjoy.">

	<link rel="stylesheet" type="text/css" media="screen" href="includes/css/grid.css">
	<link rel="stylesheet" type="text/css" media="screen" href="includes/css/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="includes/css/style.css">

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function($) {  
		
		});
	</script>

</head>
<body>
<div class="wrapper">
	<div class="header">
		<div class="container">
			<h1><a href="http://spaceheater.dhcp.rose-hulman.edu/Beer/">Beer</a></h1>
			<ul>
				<li class="browse"><a href="index.php">Browse</a></li>
	    		<?php 
				if (!empty($_SESSION['logged_in']) || !empty($_SESSION['username'])) {
				?>
		    		<li class="submit"><a href="submit.php">Submit</a></li>
		    		<li class="search"><a href="search.php">Search</a></li>
					<li class="edit_profile"><a href="edit_profile.php">Edit Profile</a></li>
	    			<li class="logout"><a href="logout.php">Logout (<strong><?php echo $_SESSION['username']; ?></strong>)</a></li>
	 	 		<?php
	 	 		} else {
	 	 		?>
	 	 			<li class="register"><a href="register.php">Register</a></li>
	 	 			<li class="login"><a href="login.php">Login</a></li>
	 	 		<?php
	 	 		}
	 	 		?>
	 	 	</ul>
		</div>
	</div>