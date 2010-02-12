<?php 
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    include $_UTIL."beer.php";
    
    if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		echo '<meta http-equiv="refresh" content="0;login.php">';
	}
	
?>
<div class="container">
    <div class="column span-24">
		<div class="shadow">
            <div class="page">
                <div id="register">
                    <h2>Add a new beer! Make it a good one.</h2>
                    <form name="register" method="post" action="submit.php">
	                    <?php 
						if (isset($_POST['Form_Submit'])) {	
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
							$beer->add_beer($name, $aroma, $filtered, $weight, $hoppiness, $finish, $color,
														$clarity, $type, $head, $alcohol, $username);
						} 
						?>
                        <div>
                            <ul>
                                <div style="float: left; width: 230px;">
	                            	<li><label for="Form_Name">Beer Name <strong>*</strong> </label> <input type="text" id="Form_Name" name="Form_Name" value="" class="inputbox"></li>
                                    <li><label for="Form_Aroma">Aroma</label> <input type="text" id="Form_Aroma" name="Form_Aroma" value="" class="inputbox"></li>
                                    <li><label for="Form_Filtered">Filtered</label> <input type="text" id="Form_Filtered" name="Form_Filtered" value="" class="inputbox"></li>
                                </div>
                                <div style="float: right; width: 230px;">
                                    <?php
										$res = mssql_query("SELECT * FROM property");
										for($i=0;$i<mssql_num_rows($res);$i++) {
											$row = mssql_fetch_assoc($res);
											echo '<li><label for="Form_'.$row["name"].'">'.$row["name"].'</label> <input type="text" id="Form_'.$row["name"].'" name="Form_'.$row["name"].'" value="" class="inputbox"></li>';
										}
									?>
                                    <br />
                             		<li><input type="submit" id="Form_Submit" name="Form_Submit" value="Submit" class="button"></li>
                                </div>
								<div class="clear"></div>
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
