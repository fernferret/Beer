<?php
if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== FALSE || strpos($_SERVER['HTTP_USER_AGENT'], 'iPod') !== FALSE)
{
	header("Location: iPhone");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php 
include "includes/config.php";
$start = microtime(); ?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <title>Beer.</title>

    <meta name="copyright" content="Copyright 2010">
    <meta name="description" content="Beer is a database for rating and finding new beers to enjoy.">

	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_HOME; ?>includes/css/grid.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_HOME; ?>includes/css/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_HOME; ?>includes/css/style.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_HOME; ?>includes/css/rating.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_HOME; ?>includes/css/auto.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $_HOME; ?>includes/css/table.css">

	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jquery.jstepper.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jquery.livequery.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jquery.password.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jquery.constrain.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jquery.rating.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jquery.meta.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/global.js"></script>
	<script type="text/javascript" src="<?php echo $_HOME; ?>includes/js/jtable/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="main_container">

	<div id="header">
		<div class="container">
			<h1><a href="<?php echo $_HOME; ?>">Beer</a></h1>
			<ul>
				<li class="browse"><a href="<?php echo $_HOME; ?>">Browse</a></li>
				<li class="search"><a href="<?php echo $_HOME; ?>search">Search</a></li>
	    		<?php 
				if (!empty($_SESSION['logged_in']) || !empty($_SESSION['username'])) {
				?>
		    		<li class="submit"><a href="<?php echo $_HOME; ?>submit">Submit</a></li>
					<li class="edit_profile" style="color: #fff"><a href="<?php echo $_HOME; ?>profiles/<?php echo $_SESSION['username']; ?>">View</a>/<a href="<?php echo $_HOME;?>profiles/edit">Edit</a> Profile</li>
	    			<li class="logout"><a href="<?php echo $_HOME; ?>logout">Logout (<strong><?php echo $_SESSION['username']; ?></strong>)</a></li>
	 	 		<?php
	 	 		} else {
	 	 		?>
	 	 			<li class="register"><a href="<?php echo $_HOME; ?>register">Register</a></li>
	 	 			<li class="login"><a href="<?php echo $_HOME; ?>login">Login</a></li>
	 	 		<?php
	 	 		}
	 	 		?>
	 	 	</ul>
		</div>
	</div>
	