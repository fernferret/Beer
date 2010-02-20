<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<?php header("Content-Type: text/xml") ?>
<root>
<?php	
	$username = $_GET['buname'];
	$password = $_GET['bpass'];
	$confirm_password = $_GET['bpassc'];
	$name = $_GET['myname'];
	$email = $_GET['bemail'];
	$region = $_GET['userregion'];
	$address = $_GET['baddress'];
	
	if(strcmp($password,$confirm_password) == 0) {					
		$user = new iPhone();
		$return = $user->add_beer_lover($_GET['buname'], $_GET['bemail'], $_GET['baddress'], $username, $password, "11");
		$ecode = substr($return,0,1);
		$message = substr($return,1,strlen($return));
	} else {
		$ecode = 1;
		$message = "Those passwords don't match!";
		//alert("Passwords do not match!", FALSE);
	}
?>
	<go to="waDoRegister" /> 
	<part> 
		<destination mode="replace" zone="waDoRegister" create="true" />
		
		<data><![CDATA[
			<div class="iBlock">
			<h3>Search Results</h3> 
				<?php
					if($ecode == 1)
					{
						echo "<p>$message</p>";
					}
					else if($ecode == 0)
					{
						echo "
						<script>
							location.hash = \"#_Home\";
							location.reload(true);
						</script>";
					}
				?>
				
			</div> 
		]]></data> 
	</part> 
</root> 