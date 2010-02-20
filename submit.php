<?php 
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."beer.php";
    
    if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		redirect("login");
	}
								
	$beer = new Beer();
?>
<div class="container">
    <div class="column span-24">
		<div class="shadow">
            <div class="page">
                <div id="submit">
                    <h2>Add a new beer! Make it a good one.</h2>
                    <form name="register" method="post" action="<?php ECHO $_SERVER['PHP_SELF']; ?>">
	                    <?php 
						if (isset($_POST['Form_Submit'])) {	
							$name = $_POST['Form_Name'];
							$aroma = $_POST['Form_Aroma'];
							$filtered = $_POST['Form_Filtered'];
							$weight = $_POST['Form_Weight'];
							$hoppiness = $_POST['Form_Hoppiness'];
							$bitterness = $_POST['Form_Bitterness'];
							$color = $_POST['Form_Color'];
							$clarity = $_POST['Form_Clarity'];
							$type = $_POST['Form_Type'];
							$head = $_POST['Form_Head'];
							$alcohol = $_POST['Form_AlcoholContent'];
							$username = $_SESSION["username"];

							$beer->add_beer($name, $aroma, $filtered, $weight, $hoppiness, $bitterness, $color, $clarity, $type, $head, $alcohol, $username);
						} 
						?>
                        <div>
                            <ul>
                            	<li><label for="Form_Name">Beer Name<strong>*</strong> </label> <input type="text" id="Form_Name" name="Form_Name" value="" class="inputbox"><span>← The name of the beer.</span></li>
                                <li><label for="Form_Aroma">Aroma</label> <input type="text" id="Form_Aroma" name="Form_Aroma" value="" class="inputbox"><span>← The aroma of the beer.</span></li>
                                <li><label for="Form_Filtered">Filtered</label> <input type="text" id="Form_Filtered" name="Form_Filtered" value="" class="inputbox"><span>← If the beer is filtered or not.</span></li>
								<?php 
								$res = mssql_query("SELECT * FROM property");
								while ($row = mssql_fetch_assoc($res)) {
									$beer->property_values('', $row["property_id"]);
								}
								?>
                                <br /><br />
                         		<li><input type="submit" id="Form_Submit" name="Form_Submit" value="Submit" class="button"></li>
                            </ul>
                            <br />
                            Fields marked with a <strong>*</strong> are required.<br />
							Please enter all of the data!
                        </div>
                    </form>                 
                </div>
            </div>
        </div>
    </div>
</div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
