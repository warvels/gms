<?php

$where_form_is="http://".$_SERVER['SERVER_NAME'].strrev(strstr(strrev($_SERVER['PHP_SELF']),"/"));

session_start();
if( ($_SESSION['security_code']==$_POST['security_code']) && (!empty($_POST['security_code'])) ) { 
 include("config.inc.form277862.php");
 $link = mysql_connect($db_host,$db_user,$db_pass);
 if(!$link) die ('Could not connect to database: '.mysql_error());
 
/*
 echo 'value of $where_form_is ' . $where_form_is;
 echo '<br>';
*/

 mysql_select_db($db_name,$link);
 // $query = "INSERT into `".$db_table."` (field_1,field_2,field_3,field_4,field_5,field_6,field_7,field_8) VALUES ('" . $_POST['field_1'] . "','" . $_POST['field_2'] . "','" . $_POST['field_3'] . "','" . $_POST['field_4'] . "','" . $_POST['field_5'] . "','" . $_POST['field_6'] . "','" . $_POST['field_7'] . "','" . $_POST['field_8'] . "')";
 $query = "INSERT into `".$db_table."` (nick, password, email, fname, lname, city, state, country) VALUES ('" . $_POST['field_1'] . "','" .
   $_POST['field_2'] . "','" . $_POST['field_3'] . "','" . $_POST['field_4'] . "','" . $_POST['field_5'] . "','" . 
   $_POST['field_6'] . "','" . $_POST['field_7'] . "','" . $_POST['field_8'] . "')";
 if (mysql_query($query)) 
 {
	echo 'data form submitted';
	mysql_close($link);
	include("confirm.html");
 } else {
	//die ('Could not Submit Registration: '.mysql_error() );
	$error_value = mysql_error();
	mysql_close($link);
	// perform ErrorHandler('formerror.html', $errordata)
	echo 'Could not Submit Registration: '.$error_value;
 }

}
 else {
 echo "Invalid Captcha String.";
}

?>