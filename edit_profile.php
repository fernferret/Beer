<?php 
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."user.php";

	if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		echo '<meta http-equiv="refresh" content="0;login.php">'; 
	}
	
	$username = $_SESSION["username"];
	
    $res = mssql_query("SELECT * FROM beer_lovers WHERE username = '".$username."'");
	$row = mssql_fetch_assoc($res);
	
	$name = $row["name"];
	$address = $row["address"];
	$email = $row["email"];
	$picture = $row["picture"];
	$date_joined = $row["date_joined"];
	
	$region_query = mssql_query("SELECT * FROM regions WHERE region_id = '".$row["region_id"]."'");
	$region_arr = mssql_fetch_assoc($region_query);
	$region = $region_arr["city"];
?>
<div class="container">
    <div class="column span-24">
		<div class="shadow">
            <div class="page">
                <div id="edit_profile">
                    <img src="<?php echo $picture; ?>" alt="<?php echo $username; ?>" style="float:right;"/>
                    <h2>Edit your profile, <strong><?php echo $username; ?></strong></h2>
                    <form style="float: left;" name="edit_profile" method="post" action="edit_profile.php">
	                    <?php 
						if (isset($_POST['Form_Edit'])) {	
							$name = $_POST['Form_Name'];
							$email = $_POST['Form_Email'];
							$address = $_POST['Form_Address'];
							$region = $_POST['Form_NewRegion'];
							$new_picture = $_POST['Form_Picture'];
							$password = $_POST['Form_Password'];
							
							$rq = mssql_query("SELECT * FROM regions WHERE city = '".$region."'");
							$ra = mssql_fetch_assoc($rq);
							$new_region = $region_arr["region_id"];
							
							$user = new User();
							$user->modify_beer_lover($name, $email, $address, $username, $password, $new_region, $new_picture) ;
						} 
						?>
                        <div>
                            <ul>
                            	<li><label for="Form_Name">Name</label> <input type="text" id="Form_Name" name="Form_Name" value="<?php echo $name; ?>" class="inputbox"></li>
                                <li><label for="Form_Address">Address</label> <input type="text" id="Form_Address" name="Form_Address" value="<?php echo $address; ?>" class="inputbox"></li>
                                <li><label for="Form_Email">Email Address</label> <input type="text" id="Form_Email" name="Form_Email" value="<?php echo $email; ?>" class="inputbox"></li>
								<li><label for="Form_Password">New Password</label> <input type="text" id="Form_Password" name="Form_Password" value="" class="inputbox"></li>
								<li><label for="Form_Picture">Picture</label> <input type="text" id="Form_Picture" name="Form_Picture" value="<?php echo $picture; ?>" class="inputbox"></li>
								<span>The picture should be 170px by 200px.</span>
								<li><label for="Date_Joined">Date Joined</label> <div style="font-size: 18px"><?php echo $date_joined; ?></div></li>
								<li><label for="Form_Region">Region</label> <div style="font-size: 18px"><?php echo $region; ?></div></li>
                                <li><label for="Form_NewRegion">New Region</label>
                                <?php
                                    echo'<select name="Form_NewRegion">';
									$res = mssql_query("SELECT * from regions");
									for($i=0;$i<mssql_num_rows($res);$i++) {
										$row = mssql_fetch_assoc($res);
										echo "<option>".$row["city"]."</option>";
									}
									echo'</select>';
								?>
                                </li>
                                <p><br/></p>
                         		<li><input type="submit" id="Form_Edit" name="Form_Edit" value="Edit Profile" class="button"></li>
                            </ul>
                        </div>
                    </form>  
    				<div class="clearfooter"></div>               
                </div>
            </div>
        </div>
    </div>
</div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
