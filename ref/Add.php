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
                <h2>Add Media</h2>
					<p>Select a category of media below, and a form will appear below. Fill out as many fields as you wish! <span style="font-size: small; color: gray">(use IMDB to make your entries full of information! *support coming soon.)</span></span></p>
				<h3>Choose a category<span style="color: red">*</span></h3>
						<div id="searchNavigation">
						 	<ul>
								<li><a href="#" id="moviebox-toggle" onclick="document.forms['addform'].media.value = 'movie'; return false;">Movies</a></li>
								<li><a href="#" id="musicbox-toggle" onclick="document.forms['addform'].media.value = 'music'; return false;">Music</a></li>
							</ul>
						</div>		
				<p><hr style="color: #fff"></p>
					
				<?php 
					// If the Add button is pressed.
					if (isset($_POST['add'])) {
						// Pull the data from the forms. All forms are accounted for, regardless if empty.
						$media = mysql_real_escape_string($_POST['media']);
						
						$movie_Title = mysql_real_escape_string($_POST['Title']);
						$movie_Actors = mysql_real_escape_string($_POST['Actors']);
						$movie_Director = mysql_real_escape_string($_POST['Director']);
						$movie_ReleaseYear = mysql_real_escape_string($_POST['ReleaseYear']);
						$movie_Genre = mysql_real_escape_string($_POST['Genre']);
						$movie_Rating = mysql_real_escape_string($_POST['Rating']);
						$movie_Length = mysql_real_escape_string($_POST['Length']);
						$movie_Filename = mysql_real_escape_string($_POST['Filename']);
						$movie_Notes = mysql_real_escape_string($_POST['Notes']);
						
						$music_Title = mysql_real_escape_string($_POST['M_Title']);
						$music_Artist = mysql_real_escape_string($_POST['M_Artist']);
						$music_Album = mysql_real_escape_string($_POST['M_Album']);
						$music_Genre = mysql_real_escape_string($_POST['M_Genre']);
						$music_Length = mysql_real_escape_string($_POST['M_Length']);
						$music_Filename = mysql_real_escape_string($_POST['M_Filename']);
						$music_Notes = mysql_real_escape_string($_POST['M_Notes']);
						
						// Initialize the Util class.
						$sql = new SQLUtil();
						
						// Inserts the data dependant on $media, either music or movie.
						if($media == "music") {
							if(empty($music_Title) || empty($music_Artist)) 
								echo "<br><div id='fail'><h1 class='fail'>ADD FAILURE!</h1><p>Please make sure all required fields are filled out.</p></div>";					
							$array = array("music_Title" => $music_Title, "music_Artist" => $music_Artist, "music_Album" => $music_Album, "music_Genre" => $music_Genre, "music_Length" => $music_Length, "music_Filename" => $music_Filename, "music_Notes" => $music_Notes);
							$sql->insert("music_info", $array);
						} else if ($media == "movie") {
							if(empty($movie_Title) || empty($movie_Actors))
								echo "<br><div id='fail'><h1 class='fail'>ADD FAILURE!</h1><p>Please make sure all required fields are filled out.</p></div>";
							$arr = array("movie_Title" => $movie_Title, "movie_Actors" => $movie_Actors, "movie_Director" => $movie_Director, "movie_ReleaseYear" => $movie_ReleaseYear, "movie_Genre" => $movie_Genre, "movie_Rating" => $movie_Rating, "movie_Length" => $movie_Length, "movie_Filename" => $movie_Filename, "movie_Notes" => $movie_Notes);
							$sql->insert("movie_info", $arr);
						}
					}
				?>
				<center><div id='errmsg'></div></center>
				<form method="post" action="Add.php" id="addform">

						<?php 
							$form = new SQLUtil();
						
							print("<div id=\"moviebox\">");
								$form->displayMovieForm();
							print("</div>");
							
							print("<div id=\"musicbox\">");
								$form->displayMusicForm();
							print("</div>");
						?>

					<input type="hidden" name="media" value="">
				</form>
			</div>
       		<div style="clear: both;"></div>            
        </div>	
<?php } ?>		
    </div>    
<?php include "Template/Footer.php"; ?>