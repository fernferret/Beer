<?php 
	include "Template/Header.php"; 
	include "Util/User.php";
	include "Util/SQLUtil.php";
?>

    <div id="headerWrapper">
        <div id="headerContent">
           	<?php include "Template/Navigation.php"; ?>
            <?php include "Template/LoginMenu.php"; ?>
        </div>
    </div>
    
    <div id="topContentWrapper">
<?php
	if (!empty($_SESSION['users_LoggedIn']) && !empty($_SESSION['users_Username'])) { ?>
	<?php include "Template/LoggedIn.php"; ?>
<?php 			
	} else { ?>
<?php } ?>	
        <div style="clear: both;"></div>
    </div>
    
    <div id="mainContentWrapper">
    	<div id="mainContent">
			<div id="mainContentDetail">
<?php
		if(!empty($_POST['Username']) && !empty($_POST['Password']))  { 
			if (isset($_POST['signup_button'])) {	
				// pull stuff from the fields.
				$username = mysql_real_escape_string($_POST['Username']);
				$password = mysql_real_escape_string($_POST['Password']);
				$password2 = mysql_real_escape_string($_POST['Password2']);
				$fname = mysql_real_escape_string($_POST['Firstname']);
				$lname = mysql_real_escape_string($_POST['Lastname']);
				$email = mysql_real_escape_string($_POST['EmailAddress']);
				$date = date('m/d/Y');
				
				$usr = new User();
				$usr->signup($username,$password,$password2,$fname,$lname,$email,$date);
			} 
		} else { ?>
					<h2>Signup</h2>
					<p>Use the form below to signup for entertainME's free services! <span style="font-size: small; color: gray">(all fields are required)</span></p>
					<form method="post" action="Signup.php" id="signupform">
						<?php 
							$form = new SQLUtil();
							print("<div id=\"signupbox\">");
								$form->displayUserForm();
							print("</div>");
						?>
					</form>
		<?php 
		} ?>
					<div style="clear: both;"></div>            
				</div>
			<div style="clear: both;"></div><br>
		</div>			
	</div>    

<?php include "Template/Footer.php"; ?>