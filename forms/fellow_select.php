<?php

 include("config.inc.form277862.php");
 $link = mysql_connect($db_host,$db_user,$db_pass);
 if(!$link) die ('Could not connect to database: '.mysql_error());
 
/*
 echo 'value of $where_form_is ' . $where_form_is;
 echo '<br>';
*/
 mysql_select_db($db_name,$link);

 $dlm = ';';
 $br = '<br>';
 $fellow_table = 'fellow';
 $query = 'SELECT * FROM ' . $fellow_table;
 $i = 0;
 
 if (mysql_query($query)) 
 {
	echo 'Searching all rows in Table '. $fellow_table . $br;
	$result = mysql_query($query);
	if($result) {
		while($row = mysql_fetch_array($result)){
			$i = $i + 1;
			$name = $row['nick']. $dlm. 
			$row['email']. $dlm.  
			$row['fname']. $dlm.  
			$row['lname']. $dlm.  
			$row['city']. $dlm.  
			$row['state']. $dlm.  
			$row['country']. $dlm.  
			$row[9]. $dlm.  
			$row[10]. $dlm.  
			$row[11]. $dlm.  
			$row[12]. $dlm.  
			$row[13]. $dlm.  
			$row[14]. $dlm.  
			$row[15]. $dlm.  
			$row['created_dt']. $dlm.
			$row['comments']. $dlm
//			$row[17]. $dlm
			;
			echo 'FELLOW # '. $i. '  '.  $name . '<br>';
			//echo 'row: '. $row;
		}
	mysql_close($link);
	}	
	
 } else {
	//die ('Could not Submit Registration: '.mysql_error() );
	$error_value = mysql_error();
	mysql_close($link);
	// perform ErrorHandler('formerror.html', $errordata)
	echo 'Could not Find table fellow. error '.$error_value;
 }


?>