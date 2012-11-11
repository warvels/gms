<?php
# example of PDO - pdotest01.php

include("config.db.active.php");
 
# orig example string 
#$pdo = new PDO('mysql:host=example.com;dbname=database', 'user', 'password');

# COMES FROM includeed config.db...  $pdo_connect = 'mysql:host=' . $db_host. ';dbname='. $db_name;
echo $pdo_connect . '<br>';
# create a PDO object for this sql db
$pdo = new PDO( $pdo_connect, $db_user, $db_pass);

# setup the SQL string
$sql_cmd = "SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL"; 
$arr_ptr = '_message';

$sql_cmd = 'SELECT * FROM input i where idinput > 12';
$arr_ptr = 'email';

# execute SQL and fetch the results.
$statement = $pdo->query( $sql_cmd );

if (!$statement) {
    echo "\nPDO::errorInfo():\n";
    print_r($pdo->errorInfo());
} else {

	while  ( $row = $statement->fetch(PDO::FETCH_ASSOC) ) {
	  print_r( $row );   echo '<br><hr>';
	};


	echo 'executed SQL : '. $sql_cmd. ' <br>array element <b>'. $arr_ptr.'</b> <br><hr>';
	echo htmlentities($row[$arr_ptr]);
	echo '<br>';
	print_r( $row ) . '<br>';
}
?>