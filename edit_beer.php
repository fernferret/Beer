<?php 
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."beer.php";
    
    if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		redirect("login");
	}
 
 	$beer_id = $_GET['id'];
    
    $username = $_SESSION['username'];
    
    $res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
	$row = mssql_fetch_assoc($res);
	
    if(mssql_num_rows($res) == 0) {
		redirect("error");
    }
			
	$beer = new Beer();
	
	$name = $row['name'];
	$aroma = $row['aroma'];
	$filtered = $row['filtered'];
?>
<div class="container">
    <div class="column span-24">
		<div class="shadow">
            <div class="page">
                <div id="edit_beer">
                    <h2>Edit the delicious, <strong><?php echo $beer->beer_name($beer_id); ?></strong></h2>
                    <form name="register" method="post" action="../edit/<?php echo $beer_id; ?>">
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
							if(empty($name)) {
								alert("Beer name cannot be empty!", FALSE);
							} else
								$beer->modify_beer($beer_id, $name, $aroma, $filtered, $weight, $bitterness, $hoppiness, $color, $clarity, $head, $type, $alcohol, $username);
						} 
						?>
                        <div>
                            <ul>
                            	<li><label for="Form_Name">Beer Name<strong>*</strong> </label> <input type="text" id="Form_Name" name="Form_Name" value="<?php echo $name; ?>" class="inputbox"></li>
                                <li><label for="Form_Aroma">Aroma</label> <input type="text" id="Form_Aroma" name="Form_Aroma" value="<?php echo $aroma; ?>" class="inputbox"></li>
                                <li><label for="Form_Filtered">Filtered</label> <input type="text" id="Form_Filtered" name="Form_Filtered" value="<?php echo $filtered; ?>" class="inputbox"></li>
								<?php 
								$res = mssql_query("SELECT * FROM property");
								while ($row = mssql_fetch_assoc($res)) {
									$beer->property_values($beer_id, $row["property_id"]);
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
