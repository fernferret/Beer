<?php

//include "Database.php";

class SQLUtil
{
	public $dbname = "pteebco_em	";

        //$db_info = new Database();
        //if($db_info == null) echo "Database info not found";

	// Array for the user database
	public $user = array(
//	"UserID" => array("NULL" => 0, "LABEL" => "User ID", "DISPLAY" => 0),
	"Username" => array("NULL" => 0, "LABEL" => "Username"),
	"Password" => array("NULL" => 0, "LABEL" => "Password"),
	"Password2" => array("NULL" => 0, "LABEL" => "Password2"),	
	"Firstname" => array("NULL" => 0, "LABEL" => "First Name"),
	"Lastname" => array("NULL" => 0, "LABEL" => "Last Name"),
	"EmailAddress" => array("NULL" => 0, "LABEL" => "Email Address")
//	"DateJoined" => array("NULL" => 0, "LABEL" => "Date Joined", "DISPLAY" => 0)
	);
	
	
	public $movie = array(
//	"movieID" => array("NULL" => 0, "LABEL" => "Movie ID", "DISPLAY" => 0),
        "movie_Title" => array("NULL" => 0, "LABEL" => "Title"),
	"movie_Actors" => array("NULL" => 0, "LABEL" => "Actors"),
        "movie_User" => array("NULL" => 0, "LABEL" => "Movie User", "DISPLAY" => 0, "PRIMARY KEY"=>"user_info(UserID)"),
	"movie_Director" => array("NULL" => 1, "LABEL" => "Director"),
	"movie_ReleaseYear" => array("NULL" => 1, "LABEL" => "Release Year"),
	"movie_Genre" => array("NULL" => 1, "LABEL" => "Genre"),
	"movie_Rating" => array("NULL" => 1, "LABEL" => "Rating"),
	"movie_Length" => array("NULL" => 1, "LABEL" => "Length"),
	"movie_Filename" => array("NULL" => 1, "LABEL" => "File Name"),
	"movie_Notes" => array("NULL" => 1, "LABEL" => "Notes"),
	);
	
	public $music = array(
	"music_Title" => array("NULL" => 0, "LABEL" => "Title"),
	"music_Artist" => array("NULL" => 0, "LABEL" => "Artist"),
	"music_Album" => array("NULL" => 1, "LABEL" => "Album"),
	"music_Genre" => array("NULL" => 1, "LABEL" => "Genre"),
	"music_Length" => array("NULL" => 1, "LABEL" => "Length"),
	"music_Filename" => array("NULL" => 1, "LABEL" => "File Name"),
	"music_Notes" => array("NULL" => 1, "LABEL" => "Notes")
	);
	
	/*
	 Function checks to see if the table given exists
	 in our mySQL database.
	*/
	public function table_exists($tablename, $database) {
		// Sets up and runs a query that tries to select form the
		// inputted databse.
		$sql = "SELECT * FROM $tablename";
		$res = mysql_query($sql);
	
		// If the query fails, return 0.
		if($res) return 1;
		return 0;
	}

	/*
	 All insert queries will sent to this file for processing,
	 here is where the connection to the SQL server will be made.
	*/
	public function insert($table_name,$values){
		// Test to make sure there is at least one value
		if(empty($values))
			return 0;
		
		// Test to make sure that the table exists
		if(!$this->table_exists($table_name, $dbname))	return 0;
		
		// Keys necessary to loop through values
		$values_keys = array_keys($values);
		
		//Creation of query string to insert
		$query_string = "INSERT INTO $table_name (";
		foreach ($values_keys as $value) {
			// Test to make sure values are not empty
			// If they are the value is ignored
			if(!empty($values[$value]))
			$query_string = $query_string . (string)$value . ", ";
		}
		
		$query_string = substr($query_string,0,strlen($query_string)-2) . ") VALUES ( ";
		foreach ($values_keys as $value) {
			$query_string = $query_string . '"' . $values[$value] . '", ';
		}
		$query_string = substr($query_string,0,strlen($query_string)-2) . ")";
		
		// Actual query
		$query = mysql_query($query_string);
		
		if(!$query) return 0;
		
		echo "<center><h1>Added!</h1><p>Added into the <strong>".substr($table_name,0,5)."</strong> library. <a style='background-color: #403524; padding: 10px; color: #fff' href='Index.php'>View your library here</a></p></center>";
		//echo "Successfully added!\n\n" . $query_string;
		return 1;
	}
	
	/*
	 All search queries will sent to this file for processing,
	 here is where the connection to the SQL server will be made.
	*/ 
	public function search($column,$search){
		// Tag on the _info for the tablename.
		$tablename = substr($column,0,6) . "info";
		
		// Echo searching for now.
		//echo "Searched for: " . $search ." in ". substr($column,6) ." in " . $tablename . " table.\n";
		
		// Test to make sure there is at least one condition
		if(empty($search)) {
			echo "<center><h1>INVALID SEARCH!</h1><p>Please enter a search criteria.</p></center>";
			return 0;
		}
		
		// Test to make sure that the table exists.
		if(!$this->table_exists($tablename, $dbname))	return 0;
		
		// Query with the stuff.
		$query = "select * from $tablename where $column like \"%$search%\" order by $column";
		
		//echo "QUERY: " .$query. "\n";
		
		$result = mysql_query($query);
		
		echo "<h1>Results:</h1>\n";
		
		// If the query succeeded..
		if($result) {
			//Grab content from mySQL.
			$r = mysql_fetch_array($result);
			
			$var=$r[$column];
		
			if(substr($column,0,5) == "music")  {
				$id = $r["music_ID"];
				$title = $r["music_Title"];
				echo '<span style="margin-left: 15px">Go to: <strong><a href="Edit.php?media=mu&id='.$id.'">'.$title.'</a></strong></span>';
			}else if(substr($column,0,5) == "movie") {
				$id = $r["movie_ID"];
				$title = $r["movie_Title"];
				echo '<span style="margin-left: 15px">Go to: <strong><a href="Edit.php?media=mo&id='.$id.'">'.$title.'</a></strong></span>';
			}
			return 1;
		} else {
			echo "Not in the database.. try again below!";
			return 0;
		}
	}

	public function edit($table_name, $values, $conditions){	
		$query_string = "UPDATE $table_name SET ";
		
		$values_keys = array_keys($values);
			
		foreach ($values_keys as $value_key){
			//if(empty($values[$value_key])) return 0;
			$query_string .= $value_key . "='" . $values[$value_key] . "', ";
		}
		$query_string = substr($query_string,0,strlen($query_string)-2) . " WHERE ";
		
		$conditions_keys = array_keys($conditions);
		
		//if(empty($conditions_keys)) return 0;
		
		foreach ($conditions_keys as $condition_key){
		//if(empty($conditions[$condition_key])) return 0;
			$query_string .= $condition_key . "='" . $conditions[$condition_key] . "' AND ";
		}
		$query_string = substr($query_string,0,strlen($query_string)-4);
		
		$query = mysql_query($query_string);
		
		echo '<meta http-equiv="refresh" content="0;Index.php">';
		
		if($query) 
			return 1;
		else
			return 0;
	}
		
	public function remove($table_name, $conditions) {
		$query_string = "DELETE FROM $table_name WHERE ";
	
		if(empty($conditions)) return 0;
	
		foreach ($conditions as $key => $value) {
			if(empty($key) || empty($value) || $value < 0 || $key < 0) return 0;
			
			$query_string .= $key . "='" . $value . "', ";
		}
	
		$query_string = substr($query_string,0,strlen($query_string)-2);
	
		$query = mysql_query($query_string);

		if ($query) 
			return 1;
		else
			return 0;
	}

	public function printMusicLibrary() {
		$query = "SELECT * FROM music_info";
		$result = mysql_query($query);
		$num = mysql_num_rows($result);
		$col = mysql_num_fields($result);
		$myrow = mysql_fetch_array($result);
		
		$i = 0; $j = 0;
		
		$html.="<center><h1>Music</h1></center><table style=\"width: 100%\"><thead><tr>";
		$mus = "music";
		foreach (array_keys($this->music) as $key) {
			$key_array = $this->music[$key];
			$label = $key_array["LABEL"];
			$html .= "<th scope='col'>$label</th>\n";
		}
		$html.="</tr></thead><tbody><tr class='hover'>";	
		while ($i < $num) {
			foreach (array_keys($this->music) as $key) {
				$key_array = $this->music[$key];
				$label = $key_array["LABEL"];
				$name = mysql_result($result,$i,"music_" . $label);
				$id = mysql_result($result,$i,"music_ID");
				$j++;
				if($j >= 7) {
					$j = 0;		
					if($i%2 == 0)
						$html .= "<td class='tab' onclick=\"window.location='Edit.php?media=mu&id=".$id."'\" >$name</td></tr>";
					else
						$html .= "<td class='tab_alt' style='cursor: hand;' onclick=\"window.location='Edit.php?media=mu&id=".$id."'\" >$name</td></tr>";	
				} else {
					if($i%2 == 0)
						$html .= "<td class='tab' style='cursor: hand;' onclick=\"window.location='Edit.php?media=mu&id=".$id."'\" >$name</td>";
					else
						$html .= "<td class='tab_alt' style='cursor: hand;' onclick=\"window.location='Edit.php?media=mu&id=".$id."'\" >$name</td>";	
				}
			}
			$i++;
		} 
		$html .= '</tr></table>';
		echo $html;
		return $html;
	}
	
	public function printMovieLibrary() {
		$query = "SELECT * FROM movie_info";
		$result = mysql_query($query);
		$num = mysql_num_rows($result);
		$col = mysql_num_fields($result);
		$myrow = mysql_fetch_array($result);
		
		$i = 0; $j = 0;
		
		$html.="<center><h1>Movie</h1></center><table style=\"width: 100%\"><thead><tr>";
		foreach (array_keys($this->movie) as $key) {
			$key_array = $this->movie[$key];
			$label = $key_array["LABEL"];
			$html .= "<th scope='col'>$label</th>\n";
		}
		$html.="</tr></thead><tbody><tr>";	
		while ($i < $num) {
			foreach (array_keys($this->movie) as $key) {
				$key_array = $this->movie[$key];
				$label = $key_array["LABEL"];
				$name = mysql_result($result,$i,"movie_" . $label);
				$id = mysql_result($result,$i,"movie_ID");
				$j++;
				if($j >= 9) {
					$j = 0;		
					if($i%2 == 0)
						$html .= "<td class='tab' onclick=\"window.location='Edit.php?media=mo&id=".$id."'\" >$name</td></tr>";
					else
						$html .= "<td class='tab_alt' onclick=\"window.location='Edit.php?media=mo&id=".$id."'\" >$name</td></tr>";	
				} else {
					if($i%2 == 0)
						$html .= "<td class='tab' onclick=\"window.location='Edit.php?media=mo&id=".$id."'\" >$name</td>";
					else
						$html .= "<td class='tab_alt' onclick=\"window.location='Edit.php?media=mo&id=".$id."'\" >$name</td>";	
				}
			}
			$i++;
		} 
		$html .= '</tr></table>';
		echo $html;
		return $html;
	}

	public function displayUserForm(){
		$result = "<table align='center' cellspacing='5' class='medit'>";
		foreach (array_keys($this->user) as $key){
			if (!$key_array["DISPLAY"]) break;
			$key_array = $this->user[$key];
			$label = $key_array["LABEL"];
			$name = $label;
			if ($key_array["NULL"])	$result .= "<tr><td><div class='head_title'>$label</div></td>";
			else $result .= "<tr><td><div class='head_title'>$label<font color='red'>*</font></div></td>";
			
			$result .= "<td><input type='text' class='lg_log' name='$name'/></td></tr>";
		}
		$result .= '</table><br><input type="submit" value="Signup" name="signup_button" class="button add_btn" />';
		echo $result;
		return $result;
	}

	public function displayMovieForm(){
		$result = "<table align='center' cellspacing='5' class='medit'>";
		foreach (array_keys($this->movie) as $key){
			if (!$key_array["DISPLAY"]) break;
			$key_array = $this->movie[$key];
			$label = $key_array["LABEL"];
			$name = $label;
			if ($key_array["NULL"])	$result .= "<tr><td><div class='head_title'>$label</div></td>";
			else $result .= "<tr><td><div class='head_title'>$label<font color='red'>*</font></div></td>";
			
			$result .= "<td><input type='text' id='$name' class='lg_log' name='$name'/></td></tr>";
		}
		$result .= '</table><br><input type="submit" value="Add Media" name="add" class="button add_btn" />';
		echo $result;
		return $result;
	}
	
	public function displayMusicForm() {
		$result = "<table align='center' cellspacing='5' class='medit'>";
		foreach (array_keys($this->music) as $key){
			if (!$key_array["DISPLAY"]) break;
			$key_array = $this->music[$key];
			$label = $key_array["LABEL"];
			$name = "M_".$label;
			if ($key_array["NULL"])	$result .= "<tr><td><div class='head_title'>$label</div></td>";
			else $result .= "<tr><td><div class='head_title'>$label<font color='red'>*</font></div></td>";
			
			$result .= "<td><input type='text' id='$name' class='lg_log' name='$name'/></td></tr>";
		}
		$result .= '</table><br><input type="submit" value="Add Media" name="add" class="button add_btn" />';
		echo $result;
		return $result;
	}	

	public function editMusicBox($id,$media) {	
		$sql = "SELECT * FROM music_info WHERE music_ID=$id";
      	$result = mysql_query($sql);        
		$myrow = mysql_fetch_array($result);

		$result = "<table align='center' cellspacing='5' class='medit'>";
		foreach (array_keys($this->music) as $key){
			if (!$key_array["DISPLAY"]) break;      
			$key_array = $this->music[$key];
			$label = $key_array["LABEL"];
			$name = "M_" . $label;
			$dbname = "music_" . $label;
			$value = $myrow[$dbname];
			if ($key_array["NULL"])	$result .= "<tr><td><div class='head_title'>$label</div></td>";
			else $result .= "<tr><td><div class='head_title'>$label<font color='red'>*</font></div></td>";
			
			$result .= "<td><input type='text' id='$name' class='lg_log' name='$name' value='$value' /></td></tr>";
		}
		$music = "music_ID";
		$mymusic = $myrow[$music];
		$result .= "</table><br><input type='submit' value='Save Changes' name='save' class='button add2_btn' /><input type='submit' value='Delete Record' name='delete' class='button add2_btn' /><input type=hidden name='id' value='$mymusic' /><input type=hidden name='media' value='$media' />";
		
		echo $result;
		return $result;
	}
	
	public function editMovieBox($id,$media) {	
		$sql = "SELECT * FROM movie_info WHERE movie_ID=$id";
      	$result = mysql_query($sql);        
		$myrow = mysql_fetch_array($result);

		$result = "<table align='center' cellspacing='5' class='medit'>";
		foreach (array_keys($this->movie) as $key){
			if (!$key_array["DISPLAY"]) break;      
			$key_array = $this->movie[$key];
			$label = $key_array["LABEL"];
			$name = $label;
			$dbname = "movie_" . $label;
			$value = $myrow[$dbname];
			if ($key_array["NULL"])	$result .= "<tr><td><div class='head_title'>$label</div></td>";
			else $result .= "<tr><td><div class='head_title'>$label<font color='red'>*</font></div></td>";
			
			$result .= "<td><input type='text' id='$name' class='lg_log' name='$name' value='$value' /></td></tr>";
		}
		$music = "movie_ID";
		$mymusic = $myrow[$music];
		$result .= "</table><br><input type='submit' value='Save Changes' name='save' class='button add2_btn' /><input type='submit' value='Delete Record' name='delete' class='button add2_btn' /><input type=hidden name='id' value='$mymusic' /><input type=hidden name='media' value='$media' />";
		
		echo $result;
		return $result;
	}

}
?>