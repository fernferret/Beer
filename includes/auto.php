<?php
include "config.php";
include "db.php";

$q = strtolower( $_GET["q"] );
if (!$q) return;

$data = mssql_query( "SELECT * FROM beer_lovers" );

while( $row = mssql_fetch_array( $data )){
	if ( strpos( strtolower( $row['username'] ), $q ) !== false ) {
		echo $row['username'] . "\n";
	}
}

?>