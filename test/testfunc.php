<html>
<body>


<?php
# ==============================================================================================
# testing various GMS functions and other PHP stuff.
# ==============================================================================================

$debuggms = 1; 

#define('__ROOT__', dirname(dirname(__FILE__))); 
#echo '<br>'. __ROOT__; 

# ==============================================================================================
# include all gms functions
define('__ROOT__', dirname(dirname(__FILE__))); 

$gmsfunctions_page  = '../functionsGMS.php';

if (!file_exists( $gmsfunctions_page )) {
	printf('Severe Error including GMS php Functions');
	# cannot log using gmserror() is it's part of above functions.
} else {

	# header info
	echo '<br>Current date : '. date("Y-m-d H:i:s"). '<BR><hr>';

    #----------------------------- Include the functions php file
	if ($debuggms)  { printf( 'functions php found : '. $gmsfunctions_page );   echo '<br><hr>'; }
	require_once($gmsfunctions_page);
	#echo '<br>';echo "1 + 16 = " . funcjeff(1,16). '<br>';
	
    if ( funcjeff(1,16) < 10 ) {
	    printf('evaluate is true. less than 10 <br>');
	} else {
		gmsError( 'TESTING error.code', 'error.text', 1, 'av1', 'av2' );
	}
	
    #----------------------------- db open and query using try, catch
	$db_link = null;
	echo '<br> dbopenGMS - using try catch <br> ';
	$db_link  = dbopenGMS( $db_link, 'open' );
	if ($db_link) {
	
		# query test.
		$query = 'SELECT i.created_dt, i.* FROM input i where idsubject > 216';
		$rowcnt = '';
		echo '<br> query test :'. $query.' <br>';
		$qresult = dbqueryGMS( $db_link, $query, $rowcnt, '' );
		if ($qresult) {
			# use admin tool to look at data in a table.
			$newhtml = quickdblist( $db_link, $qresult, '', ''  );
			#while ($row = $qresult->fetch()) { 	var_dump($row); }
			if ($rowcnt < 1)  {
				echo '<br> query was empty <br>';
			}
		}
goto EndofTestFunc;

		#----------------------------- db insert
		echo '<hr> insert test <br>';
		$insert_query = "INSERT INTO `fellow` ( `nick`, `password`, `fname`, `lname`, `email`, `address`, `city`, `country`, `state`, `zip`, `disputer`, `moderator`, `member`, `expert`, `volunteer`, `comments`, `created_dt`)
   VALUES ('tinsert3', NULL, NULL, NULL, '3mail@jjj.com',
   NULL, NULL, NULL, NULL, NULL, 'N', 'N', 'N', 'N', 'N', NULL,
   '2012-09-14 00:02:00')"
		;
		$insertId='';
		$ins_res = dbupdateGMS( $db_link, $insert_query, $insertId, '');
		if ($ins_res) {
			echo '<br> Insert sucess : '. $insertId. '<br>';
		} else {
			echo '<br> Insert failed : '. $ins_res. '<br>';
		}
		// close connection
		$db_link = null;
	} else { 
		printf('<br> !!!! NO DB !!!!! <BR>');
	}

goto EndofTestFunc;
	
    #----------------------------- using try, catch
	$db_link = null;
	echo '<br> dbtest - using try catch <br> ';
	dbtest( $db_link, 'open' );
	

	
    #----------------------------- db open and query using functions and error handling
	# testing db open
	echo '<br>attempting first DB open <br> ';
	$db_link = null;
	$db_link = dbfunc_gms( $db_link, 'open', '', '' );
	if ( $db_link ) {
		printf(' DB IS OPEN ');
	} else {
		printf(' DB IS NOT OPEN ');
	}

#echo '<br>list out db channel <br> ';
#var_dump( $db_link );	
	echo '<br>attempting 2nd DB open <br> ';
	$db_link = dbfunc_gms( $db_link, 'open', '', '' );
	if ( $db_link ) {
		printf(' 2nd DB IS OPEN ');
	} else {
		printf(' 2nd DB IS NOT OPEN ');
	}	

#echo '<br>list out db channel <br> ';
#var_dump( $db_link );	

	echo '.... Closing db <br>';
	$db_link = null;
	
	
}
 
EndofTestFunc:

?>


</body>
</html>