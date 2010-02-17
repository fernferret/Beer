<?php 
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."beer.php";

	if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		echo '<meta http-equiv="refresh" content="0;login.php">'; 
	}
	
	//MAKE SURE ONLY THE BEER LOVER ASSOCIATED WITH LAST_USERNAME IS THE ONLY ONE WHO CAN EDIT.
	
	$beer_id = $_GET['id'];
	$res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
    if(!$beer_id || mssql_num_rows($res) == 0) {
	    echo '<meta http-equiv="refresh" content="0;error.php">';
    }
   	
    $res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
	$row = mssql_fetch_assoc($res);
	
	$name = $row["name"];
	$aroma = $row["aroma"];
	$filtered = $row["filtered"];
	$last_username = $_SESSION["username"];

	$pres = mssql_query("SELECT * FROM has_property WHERE beer_id = '".$beer_id."'");
	for($i=0;$i<mssql_num_rows($pres);$i++) {
		$prow = mssql_fetch_assoc($pres);
		$description[$i] = $prow["description"];
		$property_name[$i] = $prow["property_name"];	
	}

?>
<div class="container">
    <div class="column span-24">
		<div class="shadow">
            <div class="page" id="edit_beer">
                <h2>Edit <strong><?php echo $name; ?></strong>, you rascal <strong><?php echo $last_username; ?></strong></h2>
                <form style="float: left;" name="edit_beer" method="post" action="<?php ECHO $_SERVER['PHP_SELF']; ?>">
                    <?php 
					if (isset($_POST['Form_Edit'])) {	
						$name = $_POST['Form_Name'];
						/*if (!empty($_GET['Form_Aroma']))*/ $aroma = $_POST['Form_Aroma'];
						/*if (!empty($_GET['Form_Filtered']))*/ $filtered = $_POST['Form_Filtered'];
						/*if (!empty($_GET['Form_Weight']))*/ $weight = $_POST['Form_Weight'];
						/*if (!empty($_GET['Form_Bitterness']))*/ $hoppiness = $_POST['Form_Hoppiness'];
						/*if (!empty($_GET['Form_Finish']))*/ $finish = $_POST['Form_Finish'];
						/*if (!empty($_GET['Form_Color']))*/ $color = $_POST['Form_Color'];
						/*if (!empty($_GET['Form_Clarity']))*/ $clarity = $_POST['Form_Clarity'];
						/*if (!empty($_GET['Form_Type']))*/ $type = $_POST['Form_Type'];
						/*if (!empty($_GET['Form_Head']))*/ $head = $_POST['Form_Head'];
						/*if (!empty($_GET['Form_AlcoholContent']))*/ $alcohol = $_POST['Form_AlcoholContent'];
						$username = $_SESSION["username"];
						
						$beer = new Beer();
						$beer->modify_property_of_beer($name, $aroma, $filtered, $weight, $hoppiness, $finish, $color,
													$clarity, $type, $head, $alcohol, $username) ;
					}
					?>
                    <div>
						<ul>
                            <div style="float: left; width: 230px; margin-right: 40px;">
                            	<li><label for="Form_Name">Beer Name <strong>*</strong> </label> <input type="text" id="Form_Name" name="Form_Name" value="<?php echo $name; ?>" class="inputbox"></li>
                                <li><label for="Form_Aroma">Aroma</label> <input type="text" id="Form_Aroma" name="Form_Aroma" value="<?php echo $aroma; ?>" class="inputbox"></li>
                                <li><label for="Form_Filtered">Filtered</label> <input type="text" id="Form_Filtered" name="Form_Filtered" value="<?php echo $filtered; ?>" class="inputbox"></li>
                            </div>
                            <div style="float: right; width: 230px;">
                            	<?php

                            		$res = mssql_query("SELECT * FROM has_property WHERE beer_id = '".$beer_id."'");
                            		for($i=0;$i<mssql_num_rows($res)	;$i++) {
                            			echo '<li><label for="Form_'.$property_name[$i].'">'.$property_name[$i].'</label> <input type="text" id="Form_'.$property_name[$i].'" name="Form_'.$property_name[$i].'" value="'.$description[$i].'" class="inputbox"></li>';
                            		}
                            	?>
                                <br />
                         		<li><input type="submit" id="Form_Submit" name="Form_Submit" value="Submit" class="button"></li>
                            </div>
							<div class="clear"></div>
						</ul>
                    </div>
                </form>  
				<div class="clearfooter"></div>               
            </div>
        </div>
    </div>
</div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
