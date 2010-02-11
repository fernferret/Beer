<?php 
    include "config.php";
    include "db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."user.php";
    require_once $_UTIL."functions.php";
    
    if (empty($_SESSION['logged_in']) && empty($_SESSION['username'])) {
		echo '<meta http-equiv="refresh" content="0;login.php">';
	}
?>
<div class="container">
    <div class="column span-24">
        <div class="shadow">
            <div class="page">
                <div id="login">
					<h2>Edit your profile!</h2>
					<form method="post" action="profile.php" id="registerform">
						<ul>
							<li><label for="Form_Username">Username</label> <input type="text" id="Form_Username" name="Form_Username" value="" class="InputBox"><span>← Letters, numbers, and underscores only, please.</span></li>
	                        <li><label for="Form_Password">Password</label> <input type="password" id="Form_Password" name="Form_Password" value="" class="InputBox"><span>← Make it a good one.</span></li>
	                        <br />
	                 		<li><input type="submit" id="Form_Login" name="Form_Login" value="Login" class="Button"></li>
                 		</ul>
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
