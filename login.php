<?php 
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."user.php";
    require_once $_UTIL."functions.php";
?>
<div class="container">
    <div class="column span-24">
        <div class="shadow">
            <div class="page" id="login">
                <?php
				if (!empty($_SESSION['logged_in']) && !empty($_SESSION['username'])) {
				?>
					<h2>But, you are already <strong>logged in</strong> as <strong><?php echo $_SESSION['username']; ?>!</strong></h2><h3><a href="Logout.php">Click here</a> to log out!</h3>
				<?php		
				} else { 					
				?>
				<h2>Login</h2>
				<form name="login" method="post" action="<?php ECHO $_SERVER['PHP_SELF']; ?>">
					<?php
					if (isset($_POST["Form_Login"])) {
						if (!empty($_POST['Form_Username']) && !empty($_POST['Form_LoginPassword'])) {		
							$usr = new User();
							$usr->login($_POST['Form_Username'],$_POST['Form_LoginPassword']);	
						}
					}
					?>
					<ul>
						<li><label for="Form_Username">Username</label> <input type="text" id="Form_Username" name="Form_Username" value="" class="inputbox"></li>
                        <li><label for="Form_LoginPassword">Password</label> <input type="password" id="Form_LoginPassword" name="Form_LoginPassword" value="" class="inputbox"></li>
                        <br />
                 		<li><input type="submit" id="Form_Login" name="Form_Login" value="Login" class="button"></li>
             		</ul>
				</form>
				<?php 
				} ?>
            </div>
        </div>
    </div>
</div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
