<?php 

function alert($message, $success) {
	if($success)
		echo "<div class='errors success'><p>".$message."</p></div>";
	else
		echo "<div class='errors'><p>".$message."</p></div>";
}

function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}