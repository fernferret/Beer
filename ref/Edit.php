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
		// Grab the information from the URL (?media=#&id=#)
		$media = $_GET["media"];
		$id = $_GET["id"];
	
		// If the user is logged in...
		if (!empty($_SESSION['users_LoggedIn']) && !empty($_SESSION['users_Username'])) {		
	
			// If the delete button is pressed..
			if ($_POST['delete']) {
				// Pull the information from the forms
				$id = mysql_real_escape_string($_POST['id']);
				$mu_id = array("music_ID"=>$id);
				$mo_id = array("movie_ID"=>$id);
				$media = mysql_real_escape_string($_POST['media']);

				// Setup the SQLUtil class.
				$sql = new SQLUtil();
				
				// Remove the entry, dependant on media choice.
				if($media == "mu") {
					$sql->remove("music_info", $mu_id);
				} else if($media == "mo") {
					$sql->remove("movie_info", $mo_id);
				}
				
				// Refresh the page to Index.php.
				echo '<meta http-equiv="refresh" content="0;Index.php">';
			}
	
			// If the save button is pressed..
			if ($_POST['save']) {		
				// Pull all the data from the forms.
				$media = mysql_real_escape_string($_POST['media']);

				$movie_ID = mysql_real_escape_string($_POST['id']);
				$movie_Title = mysql_real_escape_string($_POST['Title']);
				$movie_Actors = mysql_real_escape_string($_POST['Actors']);
				$movie_Director = mysql_real_escape_string($_POST['Director']);
				$movie_ReleaseYear = mysql_real_escape_string($_POST['ReleaseYear']);
				$movie_Genre = mysql_real_escape_string($_POST['Genre']);
				$movie_Rating = mysql_real_escape_string($_POST['Rating']);
				$movie_Length = mysql_real_escape_string($_POST['Length']);
				$movie_Filename = mysql_real_escape_string($_POST['Filename']);
				$movie_Notes = mysql_real_escape_string($_POST['Notes']);
			
				$music_ID = mysql_real_escape_string($_POST['id']);
				$music_Title = mysql_real_escape_string($_POST['M_Title']);
				$music_Artist = mysql_real_escape_string($_POST['M_Artist']);
				$music_Album = mysql_real_escape_string($_POST['M_Album']);
				$music_Genre = mysql_real_escape_string($_POST['M_Genre']);
				$music_Length = mysql_real_escape_string($_POST['M_Length']);
				$music_Filename = mysql_real_escape_string($_POST['M_Filename']);
				$music_Notes = mysql_real_escape_string($_POST['M_Notes']);
				
				// Setup the SQLUtil class.
				$sql = new SQLUtil();

				// Update the mySQL rows with the data, dependant on the media type.
				if($media == "mu") {
					$array = array("music_Title" => $music_Title, "music_Artist" => $music_Artist, "music_Album" => $music_Album, "music_Genre" => $music_Genre, "music_Length" => $music_Length, "music_Filename" => $music_Filename, "music_Notes" => $music_Notes);
					$conditions = array("music_ID" => $music_ID);
					$sql->edit("music_info", $array, $conditions);
				} else if ($media == "mo") {
					$arr = array("movie_Title" => $movie_Title, "movie_Actors" => $movie_Actors, "movie_Director" => $movie_Director, "movie_ReleaseYear" => $movie_ReleaseYear, "movie_Genre" => $movie_Genre, "movie_Rating" => $movie_Rating, "movie_Length" => $movie_Length, "movie_Filename" => $movie_Filename, "movie_Notes" => $movie_Notes);
					$conditions = array("movie_ID"=>$movie_ID);
					$sql->edit("movie_info", $arr, $conditions);
				}
			}
				
		?>
		<div id="mainContent">
            <div id="mainContentDetail">
                <h2>Edit</h2>
					<p>Use the form below to edit your various media files in your personal library! To delete, click the button below.</p>
					
					<form method="post" action="Edit.php" id="editform">
					<?php 
						// Setup the SQLUtil class.
						$form = new SQLUtil();
						// Draw the forms.
						if($media=="mo") {
							print("<div id=\"editmovie\">");
								$form->editMovieBox($id,$media);
							print("</div>");
						} else if($media=="mu") {
							print("<div id=\"editmusic\">");
								$form->editMusicBox($id,$media);
							print("</div>");
						}
					?>	
				</form>
			</div>
<?php } ?>
            <div style="clear: both;"></div>            
        </div>
    </div>    
<?php include "Template/Footer.php"; ?>