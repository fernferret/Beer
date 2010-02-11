<?php 
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."user.php";
?>
<div class="container">
    <div class="column span-24">
    	<div class="page-container column span-26">
			<div class="shadow">
	            <div class="page">
	                <div id="register">
	                    <h2>Sign up to get your own Beer account.</h2>
	                    <form name="regster" method="post" action="register.php">
		                    <?php 
							if (isset($_POST['Form_Register'])) {	
								$username = $_POST['Form_Username'];
								$password = $_POST['Form_Password'];
								$name = $_POST['Form_Name'];
								$email = $_POST['Form_Email'];
								$address = $_POST['Form_Address'];
								$region = $_POST['Form_Region'];
								
								$user = new User();
								$user->add_beer_lover($name, $email, $address, $username, $password, $region);
							} 
							?>
	                        <div>
	                            <ul>
	                                <div style="float: left; width: 230px;">
		                            	<li><label for="Form_Name">Name</label> <input type="text" id="Form_Name" name="Form_Name" value="" class="inputbox"></li>
	                                    <li><label for="Form_Address">Address</label> <input type="text" id="Form_Address" name="Form_Address" value="" class="inputbox"></li>
	                                    <li><label for="Form_Email">Email Address<strong>*</strong></label> <input type="text" id="Form_Email" name="Form_Email" value="" class="inputbox"></li>
	                                </div>
	                                <div style="float: right; width: 230px;">
	                                    <li><label for="Form_Username">Username<strong>*</strong></label> <input type="text" id="Form_Username" name="Form_Username" value="" class="inputbox"></li>
	                                    <li><label for="Form_Password">Password<strong>*</strong></label> <input type="password" id="Form_Password" name="Form_Password" value="" class="inputbox"></li>
	                                    <li><label for="Form_Region">Region<strong>*</strong></label>
	                                    <?php
		                                    echo'<select name="Form_Region">';
											$res = mssql_query("SELECT * from regions");
											for($i=0;$i<mssql_num_rows($res);$i++) {
												$row = mssql_fetch_assoc($res);
												echo "<option>".$row["city"]."</option>";
											}
											echo'</select>';
										?>
	                                    </li>
	                                    <br />
	                             		<li><input type="submit" id="Form_Register" name="Form_Register" value="Register" class="button"></li>
	                                </div>
									<div class="clear"></div>
	                            </ul>
	                            <br />
	                            If your region isn't available, it will be soon!<br />
	                            Fields marked with a <strong>*</strong> are required.
	                        </div>
	                    </form>                 
	                </div>
	            </div>
	        </div>
	        
	    </div>

    </div>
</div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
