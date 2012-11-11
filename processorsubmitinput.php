<?php

# Submit input Form - form281797 - converted to processorsubmitinput.php
# 

# get address of this page
/*
$where_form_is="http://".$_SERVER['SERVER_NAME'].strrev(strstr(strrev($_SERVER['PHP_SELF']),"/"));
 echo 'value of $where_form_is ' . $where_form_is;
 echo '<br>';
*/

$debuggms = 0;
if ( $debuggms ) { echo "<br> in processorsubmitinput.php<br>"; }

session_start();
if( ($_SESSION['security_code']==$_POST['security_code']) && (!empty($_POST['security_code'])) ) { 

	# Include the Mysql DB configuration file and all GMS functions file
	require_once("config.db.active.php");
	$gmsfunctions_page  = 'functionsGMS.php';
	require_once($gmsfunctions_page);

	$db_table = $table_input;

	# open database connection
	#$link = mysql_connect($db_host,$db_user,$db_pass);
	#if(!$link) die ('Could not connect to database: '.mysql_error());
	# mysql_select_db($db_name,$link);

	#----------------------------- db open and query using try, catch
	$db_link = null;
	$db_link  = dbopenGMS( $db_link, 'open' );
	if ($db_link) {

		// original                               suggest, subject, email,  details
		// $query = "INSERT into `".$db_table."` (field_1, field_2, field_3,field_4) VALUES ('" . $_POST['field_1'] . "','" . $_POST['field_2'] . "','" . $_POST['field_3'] . "','" . $_POST['field_4'] . "')";
		//                                     suggestion
	 
		# Find "subject area" - for text value passed in form, find that value in the db.
		#$subject_id = rand(10, 18);  # for testing
		$form_subject = trim( strtolower( $_POST['field_2'] ) );  # printf('subjectarea = $form_subject');

		# query DB for ID of the selected subject area text
		$query = "select sa.idsubjarea, sa.* from subjarea sa where lower(area) = '". $form_subject. "'" ;
		
		$qresult = dbqueryfetchallGMS( $db_link, $query, '', '' );
		#var_dump( $qresult ); #print_r($qresult);
		if ($qresult) {
			# From the resutling rows of the query, extract the First row's [0] IDsubjarea (fieldname from db)
			$subject_id = $qresult[0]['idsubjarea'];			
			if ($subject_id) {
				# now insert new row into Database
				# use the current date/time for the row we are going to store.  date("Y-m-d H:i:s");
				$today_date_time = gmdate('Y-m-d H:i:s'); 

				# setup query for final insert of new Submission into INPUT table
				# form : suggestion, subjarea, email, details
				$query = "INSERT into `".$db_table."` (suggestion, idsubject, email, details, created_dt)  VALUES ('" .
				$_POST['field_1'] . "','" . 
				$subject_id . "','" . 
				$_POST['field_3'] . "','" . 
				$_POST['field_4'] . "','" . 
				$today_date_time . "'" .  
				")";
				#echo ' Insert query = '. $query ;

				#$insert_result = 'test insert';
				$insertId = '';
				$insert_result = dbupdateGMS( $db_link, $query, $insertId, '');
				if ($insert_result) {
					$insertId = $db_link->lastInsertId();
					if ($debuggms) { printf(' row inserted into $db_table with ID = $insertId '); }
					# 'data form submitted';
					$confirmfile = "confirmSubmit.html";
					if (file_exists($confirmfile)) {
						include($confirmfile);
					} else {
						printf( "Submission accepted. Note :missing  $confirmfile ");
					}
				}
			} else {
				#formerror
				#echo "subject area not found. No ID. Subject: ". $form_subject ;
				printf( "subject area not found. No ID. Subject: $form_subject ");
				include("submitSubjectError.html");
			}
		} # if query for subjarea had result
		else {
			printf(  "subject area not found. No qresult. Subject: $form_subject ");
			include("submitSubjectError.html");
		}
	
		# --------------------------------------------------------------------------------------
		# close database
		if (isset($db_link)) { $db_link = null; }
				
	} # if db open
	else {
		printf( "Unable to open Database ");
		include("submitSubjectError.html");	
	}
} 
 else {
 # captcha string did not pass 
 echo "Invalid Captcha String. Please press your Back button to return and Re-enter<br/>";
}

?>