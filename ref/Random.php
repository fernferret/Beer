<?php 
include "Template/Header.php"; 
include "Util/RandomUtil.php";
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
		<?php include "Template/LoggedIn.php" ?>
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
                <h2>Random Media</h2>
					<p>Select a category of media below, a random record from your personal library will be generated, as well as a random playlist of 10 records. <span style="font-size: small; color: gray">(If you need help, see our help section!)</span></span></p>
				<h3>Choose a category<span style="color: red">*</span></h3>
					<div id="searchNavigation">
						<ul>
							<li><a href="#" id="moviebox-toggle" onclick="document.forms['randform'].media.value = 'movie'; return false;">Movies</a></li>
							<li><a href="#" id="musicbox-toggle" onclick="document.forms['randform'].media.value = 'music'; return false;">Music</a></li>
							<li>
								<form method="post" action="Random.php" id="randform">
									<table class='medit'>
									<tr>
										<p>
											<td><div class='head_title'># of records: </div></td>
											<td><input type='text' id='length' class='lg_log' name='length' value='10' /></td>	
											<input type="hidden" name="media" value="">
										</p>
									</tr>
						
									</table>
									<p><input type='submit' value='Randomize!' name='randomer' class='button add2_btn' /></p>
								</form>
							</li>
						</ul>
					</div>		
				<p><hr style="color: #fff"></p>
				<?php 
					// If the search button is pressed.
					if (isset($_POST['randomer'])) {
						$length = mysql_real_escape_string($_POST['length']);
						$media = mysql_real_escape_string($_POST['media']);
						$rand = new RandomUtil();
						
						if(empty($media)) echo "<h2>Please pick a category!</h2>";
						
						if($media=="movie") {
							print("<div id=\"randommovie\">");
								$rand->random("movie_info",$media);
								echo "<p><hr style=\"color: #fff\"></p>";
								$rand->random_playlist("movie_info",$media,$length);
							print("</div>");
						} else if($media=="music") {
							print("<div id=\"randommusic\">");
								$rand->random("music_info",$media);
								echo "<p><hr style=\"color: #fff\"></p>";
								$rand->random_playlist("music_info",$media,$length);
							print("</div>");
						}
					}	
				?>
				
			</div>
       		<div style="clear: both;"></div>            
        </div>	
<?php } ?>		
    </div>    
<?php include "Template/Footer.php"; ?>