<?php
include "config.php";
include "db.php";

$q = strtolower( $_GET["q"] );
if (!$q) return;

$data = mssql_query( "SELECT * FROM beers" );

while( $row = mssql_fetch_array( $data )){
	if ( strpos( strtolower( $row['name'] ), $q ) !== false ) {
		echo $row['name'] . "\n";
	}
}

?>