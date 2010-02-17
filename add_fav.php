<?php
    include "includes/db.php";
    include "util/user.php";

    $beer_id = $_POST['id'];
    $username = $_SESSION["username"];
    
    $user = new User();
    $user->add_favorite($beer_id, $username);
?>