<?php 
include "Template/Header.php"; 
include "Util/SQLUtil.php";
?>
    <div id="headerWrapper">
        <div id="headerContent">
           	<?php include "Template/Navigation.php"; ?>
            <?php include "Template/LoginMenu.php"; ?>
        </div>
    </div>
    
    <div id="topContentWrapper">
<?php
	if (!empty($_SESSION['users_LoggedIn']) && !empty($_SESSION['users_Username'])) { ?>
	<?php include "Template/LoggedIn.php"; ?>
<?php 			
	} else { ?>	
        <div id="topContent">
        	<?php include "Template/NotLoggedIn.php"; ?>
        </div>
<?php 		
	} ?>
    	<div style="clear: both;"></div>
    </div>
    
    <div id="mainContentWrapper">
<?php 
		if (!empty($_SESSION['users_LoggedIn']) && !empty($_SESSION['users_Username'])) { ?>
		<div id="mainContent">
            <div id="mainContentDetail">
                <h2>Search</h2>
					<p>Use the form below to search for various media files in your personal library! You have to click on a category of media first. Use any keyword <span style="font-size: small; color: gray">(such as Artist, Title, Genre, etc..)</span></p>
	
					<?php 
						// If the search button is pressed.
						if (isset($_POST['searchs'])) {
							$search = mysql_real_escape_string($_POST['search']);
							$media = mysql_real_escape_string($_POST['media']);
							$column =  mysql_real_escape_string($_POST['media_column']);
							
							// Setup the SQLUtil class.
							$sql = new SQLUtil();
							
							// Search for the item.
							if($media == "music") {
								$column = "music_" . $column;
								$sql->search($column,$search);
							} else if ($media == "movie") {
								$column = "movie_" . $column;
								$sql->search($column,$search);
							}						
						}
					?>
					<form method="post" action="Search.php" id="searchform">
						<h3>Choose a category<span style="color: red">*</span></h3>
						<div id="searchNavigation">
						 	<ul>
								<li><a href="#" id="moviebox-toggle" onclick="document.forms['searchform'].media.value = 'movie'; return false;">Movies</a></li>
								<li><a href="#" id="musicbox-toggle" onclick="document.forms['searchform'].media.value = 'music'; return false;">Music</a></li>
							</ul>
						</div>				
						<p><hr style="color: #fff"></p>
		
						<table align='center' cellspacing='5' class='medit'>
							<tr>
								<td><div id="searchbox1" class='head_title'>Search</div></td>	
								<td><div id="searchbox2"><input type='text' id ='search' class='lg_log' name='search'/></div></td>
								<td>
									<div id="moviebox">
										<?php 
											$movies = mysql_list_fields("EM", "movie_info");
											$result = mysql_query("SELECT * FROM movie_info");
											$nummovies = mysql_num_fields($result);
											echo '<select id="movies">';
												for ($i = 0; $i < $nummovies; $i++) {
													echo "<option>";
														echo substr(mysql_field_name($result, $i),6);
													echo "</option>";
												}
											echo "</select>"; 
										?>
									</div>
									
									<div id="musicbox">
										<?php 
											$music = mysql_list_fields("EM", "music_info");
											$result = mysql_query("SELECT * FROM movie_info");
											$nummusic = mysql_num_fields($result);
											echo '<select id="music">';
												for ($i = 0; $i < $nummusic; $i++) {
													echo "<option>";
														echo substr(mysql_field_name($result, $i),6);
													echo "</option>";
												}
											echo "</select>"; 
										?>
									</div>
								</td>
								<td><div id="searchbox3"><input class="button" type="submit" name="searchs" id="searchs" value="Search" /></div></td>
							</tr>
						</table><br>
		
						<input type="hidden" name="media" value="">
						<input type="hidden" id="media_column" name="media_column" value="">
				</form>
			</div>
<?php } ?>
            <div style="clear: both;"></div>            
        </div>
    </div>    
<?php include "Template/Footer.php"; ?>