<?php 
/*
	Xadd_beer_to_vendor
	add_property
	add_rating
	add_vendor
	modify_property
	modify_region
	remove_beer
	remove_beer_from_vendor
	remove_beer_lover
	remove_property
	remove_rating
	remove_region
	remove_vendor
*/
	include "";
	include "includes/config.php";
	include "includes/db.php";
	include "util/adminhelp.php";
	include $_TEMPLATE."header.php";
?>
	<div class="container">
		<div class="column span-24">			
			<div class="shadow">
				<div class="page" id="browse">                
	                <div class="admin_function">
	                	<label>Add a beer to a vendor!</label>
	 					<form id="admin" name="add_beer_to_vendor" method="post" action="admin.php">
	                     	<?php 
							if (isset($_POST['beer'])) {	
								$beer = $_POST['beer'];
								$vendor = $_POST['vendor'];
								
								$admHelp = new Adminhelp();
								$admHelp->add_beer_to_vendor($beer, $vendor);
							} 
							?>
	                        <?
                            echo '<fieldset>';
	                        echo '<select id="beer" name="beer">';
							$beer_query = mssql_query("SELECT * FROM view_all_beer_names");
							$beers_arr = mssql_fetch_assoc($beer_query);
                            if(isset($_POST['beer']) && ($beers_arr['id'] == $_POST['beer'])){
								$id = $beers_arr["id"];
								$beer = $beers_arr['beer'];
								echo "<option selected=\"selected\" value=\"$id\">$beer</option>";
							}
							else
							{
								$id = $beers_arr["id"];
								$beer = $beers_arr['beer'];
								echo "<option value=\"$id\">$beer</option>";
							}
							while ($beers_arr = mssql_fetch_assoc($beer_query))
							{
								$id = $beers_arr["id"];
								$beer = $beers_arr['beer'];
								if(isset($_POST['beer']) && ($beers_arr['id'] == $_POST['beer'])){
                               		echo "<option selected=\"selected\" value=\"$id\">$beer</option>";
								}
								else
								{
									echo "<option value=\"$id\">$beer</option>";
								}
							}
	                        echo '</select> ';
	                        echo " <span class=\"label\">to</span> ";
	                        echo " <select id=\"vendor\" name=\"vendor\">";
							$query = mssql_query("SELECT * FROM view_all_vendor_names");
							$result = mssql_fetch_assoc($query);
                            if(isset($_POST['vendor']) && ($id == $_POST['vendor'])){
								$id = $result["id"];
								$value = $result['vendor'];
								echo "<option selected=\"selected\" value=\"$id\">$value</option>";
							}
							else
							{
								$id = $result["id"];
								$value = $result['vendor'];
								echo "<option value=\"$id\">$value</option>";
							}
							while ($result = mssql_fetch_assoc($query))
							{
								$id = $result["id"];
								$value = $result['vendor'];
								if(isset($_POST['vendor']) && ($id == $_POST['vendor'])){
                               		echo "<option selected=\"selected\" value=\"$id\">$value</option>";
								}
								else
								{
									echo "<option value=\"$id\">$value</option>";
								}
							}
	                        echo '</select>';?>
                                <br /><br />
                                <input type="submit" value="Add Beer to Vendor" class="button" />
	                        </fieldset>
	                    </form>
	                </div>
                    
                    <div class="admin_function">
	                	<label>Remove a beer from a vendor!</label>
	 					<form id="admin" name="remove_beer_from_vendor" method="post" action="admin.php">
	                     	<?php 
							
							?>
	                        <?
                            echo '<fieldset>';
	                        echo '<select id="rbfv_vendor" name="rbfv_vendor" onchange="this.form.submit();">';
							$query = mssql_query("SELECT DISTINCT vendor, vend_id FROM view_all_beers_at_vendors");
							$array = mssql_fetch_assoc($query);
								$id = $array["vend_id"];
								$value = $array['vendor'];
								if(isset($_POST['rbfv_vendor']) && $_POST['rbfv_vendor'] == $id)
								{
									echo "<option selected=\"selected\" value=\"$id\">$value</option>";
								}
								else
								{
									echo "<option value=\"$id\">$value</option>";
								}
							while ($array = mssql_fetch_assoc($query))
							{
								$id = $array["vend_id"];
								$value = $array['vendor'];
								if(isset($_POST['rbfv_vendor']) && $_POST['rbfv_vendor'] == $id)
								{
									echo "<option selected=\"selected\" value=\"$id\">$value</option>";
								}
								else
								{
									echo "<option value=\"$id\">$value</option>";
								}
							}
							echo "<input type=\"button\" value=\"Select Vendor\" class=\"button\" />";
	                        echo '</select> ';
							if(isset($_POST['rbfv_vendor']))
							{
								if (isset($_POST['rbfv_beer'])) {	
									$beerid = $_POST['rbfv_beer'];
									$vendorid = $_POST['rbfv_vendor'];
								
									$admHelp = new Adminhelp();
									$admHelp->remove_beer_from_vendor($beerid, $vendorid);
								} 
								$vendor = $_POST['rbfv_vendor'];
								echo "<br /><span class=\"label\">Remove the following beer:</span> ";
								echo " <select id=\"rbfv_beer\" name=\"rbfv_beer\">";
								$querystring = "SELECT * FROM view_all_beers_at_vendors WHERE vend_id = '$vendor'";
								echo $querystring;
								$query = mssql_query($querystring);
								$result = mssql_fetch_assoc($query);
								$id = $result["beer_id"];
								$value = $result['beer'];
								echo "<option selected=\"selected\" value=\"$id\">$value</option>";
								while ($result = mssql_fetch_assoc($query))
								{
									$id = $result["beer_id"];
									$value = $result['beer'];
									echo "<option value=\"$id\">$value</option>";
	
								}
								echo '</select>';
								echo '<br /><br />';
                                echo "<input type=\"submit\" value=\"Remove Beer\" class=\"button\" />";
							}?>
                                
	                        </fieldset>
	                    </form>
	                </div>
				</div>
			</div>
		</div>
		<div class="clearfooter"></div>
	</div>

<?php
	include $_TEMPLATE."footer.php"; 
?>