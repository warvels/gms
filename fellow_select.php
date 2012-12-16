<!-- 
 quick select of FELLOW - 

-->
<html>
<head>
<link href="css/styleforms.css" rel="stylesheet" type="text/css">
<link href="css/stylegms.css"   rel="stylesheet" type="text/css">

<style type="text/css"> 
/* extra styles that we dont have in styleform.css */
body 
{
	background-color: #989F99;  	
}
</style>
</head>

<body>

<p>


<?php

 include("config.db.php");
 
 # Old mysql connect before PDO
 #$link = mysql_connect($db_host,$db_user,$db_pass);
 #if(!$link) die ('Could not connect to database: '.mysql_error());
 
 $debuggms = 0; 
 
 # html table building values
 $dlm = '';
 $br = '<br>';
 $prec = '<td>'; 
 $postc = '</td>';
 $preh = '<th>'; 
 $posth = '</th>';
 
 
 # header about database and other stuff for Debugging
 $select_header_page = 'db_header_info.php';
 if (file_exists($select_header_page)) { include $select_header_page ; }
 
   
 $i = 0;
 $db_table = $table_fellow;
 $query = 'SELECT * FROM ' . $db_table;
 
 # --------------------------------------------------------------------------------------
 # setup database link using PDO object
 
 # old mysql_connect - allows sql injection 
 #$link = mysql_connect($db_host,$db_user,$db_pass);
 #if(!$link) die ('Could not connect to database: '.mysql_error());
 #mysql_select_db($db_name,$link);

 # create a PDO database link for the sql db (TODO test for no link)
 $pdo_dblink = new PDO( $pdo_connect, $db_user, $db_pass );

 
 if ($pdo_dblink) 
 {
    echo $query . $br; 
	echo '<p class="formInfo"> ';
	echo 'Searching all rows in Table '. $db_table. $br;
    echo 'Current UTC date '. gmdate('Y-m-d H:i:s'). $br;
    $fellowsSlectver = '1.1';
    echo 'Version = '.$fellowsSlectver. '<a href=./> Return to Previous</a>' . $br;
		
		
  	# execute SQL query 
	$sql_cmd = $query;    /* passed value for query */
	$statement = $pdo_dblink->query( $sql_cmd );
	if (!$statement) {
	   echo "\nPDO::errorInfo():\n";
	   print_r($pdo->errorInfo());
	} else {

		echo "<table border='2'>";
		$theader = '<thead>'. $preh. 'FELLOW '. $posth. 
		$preh. 'Nick '. $posth. 
		$preh. 'email '. $posth. 
		$preh. 'first '. $posth. 
		$preh. 'last '. $posth. 
		$preh. 'city '. $posth. 
		$preh. 'state '. $posth. 
		$preh. 'country '. $posth. 
		$preh. 'zip '. $posth. 
		$preh. 'D '. $posth. 
		$preh. 'MD '. $posth. 
		$preh. 'MB '. $posth. 
		$preh. 'EX '. $posth. 
		$preh. 'VL '. $posth. 
		$preh. 'Created '. $posth. 
		$preh. 'updated_dt '. $posth. 
		$preh. 'Comments '. $posth. 
		'</thead>'; 
	    echo $theader;
	
		while ( $row = $statement->fetch(PDO::FETCH_ASSOC) ) {
			$i = $i + 1;
			if ($debuggms)  { print_r( $row );   echo '<br><hr>';	}
			
			$hrow = "test<br>";
			$hrow = $prec. 'FELLOW # '. $i. $postc.
			$prec. $row['nick']. $dlm. $postc.
			$prec. $row['email']. $dlm.   $postc.
			$prec. $row['fname']. $dlm.   $postc.
			$prec. $row['lname']. $dlm.   $postc.
			$prec. $row['city']. $dlm.   $postc.
			$prec. $row['state']. $dlm.   $postc.
			$prec. $row['country']. $dlm.   $postc.
			$prec. $row['zip']. $dlm.   $postc.
			$prec. $row['disputer']. $dlm.   $postc.
			$prec. $row['moderator']. $dlm.   $postc.
			$prec. $row['member']. $dlm.   $postc.
			$prec. $row['expert']. $dlm.   $postc.
			$prec. $row['volunteer']. $dlm.   $postc.
			$prec. $row['created_dt']. $dlm. $postc.
			$prec. $row['updated_dt']. $dlm. $postc.
			$prec. $row['comments']. $dlm. $postc  ;
			
			echo "<tr>";
			echo $hrow;
			echo "</tr>";
			//echo 'row: '. $row;
		}
		echo "</table>";	
		
	}	
	$pdo_dblink = null;
	
 } else {
	$pdo_dblink = null;
	// perform ErrorHandler('formerror.html', $errordata)
	echo 'Could not Open database connection ';
 }


?>

</p>
</body>
</html>