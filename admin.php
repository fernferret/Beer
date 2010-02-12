<?php 
/*
	add_beer_to_vendor
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
	                        <fieldset>
	                            <select id="beer" name="beer">
	                            	<?php 
									$beer_query = mssql_query("SELECT * FROM view_all_beer_names");
									$beers_arr = mssql_fetch_assoc($beer_query);
									?>
                                    <?php if(isset($_POST['beer']) && ($beers_arr['id'] == $_POST['beer'])){ ?>
									<option selected="selected" value="<?php echo $beers_arr['id'] ?>"><?php echo $beers_arr['beer'] ?></option>
									<?php
									}
									else
									{?>
									<option value="<?php echo $beers_arr['id'] ?>"><?php echo $beers_arr['beer'] ?></option>
									<?php }
									while ($beers_arr = mssql_fetch_assoc($beer_query))
									{
										if(isset($_POST['beer']) && ($beers_arr['id'] == $_POST['beer'])){?>
                                    <option selected="selected" value="<?php echo $beers_arr['id'] ?>"><?php echo $beers_arr['beer'] ?></option>
                                    <?php 
										}
										else
										{?>
									<option value="<?php echo $beers_arr['id'] ?>"><?php echo $beers_arr['beer'] ?></option>
									<?php }}?>
	                           	</select>
	                            <span class="label">to</span>
	                            <select id="vendor" name="vendor">
	                            	<?php 
									$vendor_query = mssql_query("SELECT * FROM view_all_vendor_names");
									$vendor_arr = mssql_fetch_assoc($vendor_query);
									?>
									<option selected="selected" value="<?php echo $vendor_arr['id'] ?>"><?php echo $vendor_arr['vendor'] ?></option>
									<?php
	                				
									while ($vendor_arr = mssql_fetch_assoc($vendor_query))
									{?>
									<option value="<?php echo $vendor_arr['id'] ?>"><?php echo $vendor_arr['vendor'] ?></option>
									<?php }?>
	                           	</select>
                                <br /><br />
                                <input type="submit" value="Add Beer to Vend" class="button" />
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