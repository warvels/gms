<?php
/* GMS database API   	/gms/api/index.php
 * 
 * REST functions using Slim framework for PHP (connects REST to php functions). Will return all database data in json objects
 * 2012-11-23 (JSW) created GET functions for problems (input table joined with subjare and fellow)
 *   users (fellow table) , subjectareas (subjectarea table),  inputs (input table)
 * Revisions
 * 2012-11-26 - added this revisions section of the comment
 * 2012-12-04 - added the get functions for new table 'comment' 
 * 2012-12-08 - added GET calls for :   /api/problems?subjarea=Education		/api/problems/2/comments
 *              added function : testParameters()  to test parameter passing  /api/testing
 *              added GET calls for :  "announcements"   /api/rostrums
 * 2012-12-09 - added INPUT.IDINPUT as for element in json object for getProblems
 * 				added POST to add a comment to a problem  : /gms/api/comments
 * 				POST to problems will now find subjarea ID based on passed subjarea text
 * 2012-12-12 - added rostrusm (ANNOUNCEMENT) GET.  also optional parameter to select recent last=N : gms/api/rostrums?last=3
 * 2012-12-30 - new default category/subjarea "general" will be used if passed is not valid
 * 2012-12-31 - added 2 parameters for /api/problems  : To allow sorting and Filtering on the 'approved' column
 *                ?sort=category  or [fellow | suggestion | email | liked ]
 *                ?status=approved  or [ rejected | pending | all ]
 * 2012-12-31 - added api to Like / Dislike a problem  :  /api/problems/<id>/like  :
 * 2013-01-06 - cleanup and logging during all functions
*/


# GMS basic fuctions.
require('functionsGMS.php');


# REST api setup using Slim : Rest Noun / object , corresponding local function to execute
require('Slim/Slim.php');
$app = new Slim();
$app->get('/problems',      'getProblems');
$app->get('/problems/:id',	'getProblem');
$app->get('/problems/:id/comments',	'getProblemComments');
$app->post('/problems',	'addProblem');
$app->put('/problems/:id/like',	'likeProblem');
$app->put('/problems/:id/dislike',	'dislikeProblem');
$app->get('/users',     'getUsers');
$app->get('/users/:id',	'getUser');
$app->get('/subjectareas',      'getSubjectareas');
$app->get('/subjectareas/:id',	'getSubjectarea');
$app->get('/inputs',      'getInputs');
$app->get('/inputs/:id',	'getInput');
$app->get('/comments',      'getComments');
$app->get('/comments/:id',	'getComment');
$app->post('/comments',	'addComment');
$app->get('/rostrums',      'getAnnouncements');
$app->get('/rostrums/:id',	'getAnnouncement');
$app->get('/testing',	'testParameters');
$app->run();


/**  
 * testing:  REST GET function to test parameter passing
 * @param 
 * @return  via Echo : 
 * @throws
 *   http://localhost/gms/api/testing?jeff=hi
 *   http://localhost/gms/api/testing?param1=hi&param2=education
*/
# JSW
# get parameters (if they exist) = ?param1=xxx?param2=yyy
#  localhost/gms/api/subjectareas?jeff=j1
#  http://localhost/gms/api/subjectareas?jeff=whatdoyousee&jill=any
#   will show both params

function testParameters() {
	$p1='param1';
	echo 'test full param get '. Slim::getInstance()->request()->get($p1);
    echo '<br>';
	$request = Slim::getInstance()->request();
	echo 'Parameters : '.$p1.' = '.$request->get($p1);  echo '<br>';
	$p1='param2';
	echo 'Parameters : '.$p1.' = '.$request->get($p1);  echo '<br>';
	if ($request->get($p1)) {
		$found = 1;
	} 

	# see if you can find param to in subjarea table
	$y=$request->get($p1);	
	echo 'search for sa = '.$y.' ID= '.findSubjectArea($y);

	#echo '{results}';
}



/* 
 ----------------------------------------------------------------------
 GET functions
 ----------------------------------------------------------------------
*/


/**  
 * REST GET function for all rows in the table 'input' 
 * @param 
 * @return  via Echo : the json object for all 'input' rows. will return {error:error text}
 * @throws
*/
function getInputs() {
	$sql = "select * FROM input ORDER BY created_dt";

	gmsLog('GET ting input '.$sql, '', '', '');		
	
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$inputs = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"input": ' . json_encode($inputs) . '}';
	} catch(PDOException $e) {
		gmsError( 'api.getInputs' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


/**  
 * REST GET function for a single row form the table 'input' 
 * @param 
 * @return  via Echo : the json object for the row from table 'input'. will return {error:error text}
 * @throws
*/
function getInput($id) {
	$sql = "SELECT * FROM input WHERE idinput=:id";

	gmsLog('GET ting input ID '.$sql, '', '', '');		
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);            # binds the "id" in sql to the "id" from API
		$stmt->execute();
		$input = $stmt->fetchObject();  
		$db = null;
		echo json_encode($input); 
	} catch(PDOException $e) {
		gmsError( 'api.getInput' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


/**  
 * REST GET function for all rows in the table 'fellow'   (users to you and me)
 * @param 
 * @return  via Echo : the json object for all 'fellow' rows. will return {error:error text}
 * @throws
*/
function getUsers() {
	$sql = "select * FROM fellow ORDER BY updated_dt desc";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"user": ' . json_encode($users) . '}';
	} catch(PDOException $e) {
		gmsError( 'api.getUsers' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



/**  
 * REST GET function for a single row form the table 'fellow' - user to you and me
 * @param 
 * @return  via Echo : the json object for the row from table 'fellow'. will return {error:error text}
 * @throws
*/
function getUser($id) {
	$sql = "SELECT * FROM fellow WHERE idfellow=:id";
	
	gmsLog('GET ting fellow ID  '.$sql, '', '', '');		
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);            # binds the "id" in sql to the "id" from API
		$stmt->execute();
		$user = $stmt->fetchObject();  
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		gmsError( 'api.getUser' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


/**  
 * REST GET function for all rows in the table 'subjarea
 * @param 
 * @return  via Echo : the json object for all 'subjarea' rows. will return {error:error text}
 * @throws
*/
function getSubjectareas() {
	$sql = "select * FROM subjarea ORDER BY area";

	gmsLog('GET ting subjarea '.$sql, '', '', '');		
	
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$subjectareas = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"subjectarea": ' . json_encode($subjectareas) . '}';
	} catch(PDOException $e) {
		gmsError( 'api.getSubjectareas' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

/**  
 * REST GET function for all rows in the table 'subjarea'   (available Categories for problems)
 * @param 
 * @return  via Echo : the json object for all 'subjarea' rows. will return {error:error text}
 * @throws
*/
function getSubjectarea($id) {
	$sql = "SELECT * FROM subjarea WHERE idsubjarea=:id";

	gmsLog('GET ting subjarea ID '.$sql, '', '', '');		
	
	try {
		#debug printf($id);	
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);            # binds the "id" in sql to the "id" from API
		$stmt->execute();
		$subjectarea = $stmt->fetchObject();  
		$db = null;
		echo json_encode($subjectarea); 
	} catch(PDOException $e) {
		gmsError( 'api.getSubjectarea' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



/**  
 * REST GET function for all rows in the table 'subjarea
 * @param 
 * @return  via Echo : the json object for all 'subjarea' rows. will return {error:error text}
 * @throws
*/
function getComments() {
	$sql = "select * FROM comment ORDER BY related_to, created_dt";

	gmsLog('GET ting comments '.$sql, '', '', '');		

	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$subjectareas = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"comments": ' . json_encode($subjectareas) . '}';
	} catch(PDOException $e) {
		gmsError( 'api.getComments' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

/**  
 * REST GET function for all rows in the table 'subjarea'   (available Categories for problems)
 * @param 
 * @return  via Echo : the json object for all 'subjarea' rows. will return {error:error text}
 * @throws
*/
function getComment($id) {
	$sql = "SELECT * FROM comment WHERE idcomment=:id";

	gmsLog('GET ting comment ID '.$sql, '', '', '');		
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);            # binds the "id" in sql to the "id" from API
		$stmt->execute();
		$subjectarea = $stmt->fetchObject();  
		$db = null;
		echo json_encode($subjectarea); 
	} catch(PDOException $e) {
		gmsError( 'api.getComment' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}




/**  
 * REST GET function for all rows in the table 'rostrum'  (Announcements)		gms/api/rostrums?last=1
 * @param 
 * @return  via Echo : the json object for all 'rostrum' rows
 * @throws
*/
function getAnnouncements() {
	$sql = "select * FROM rostrum ORDER BY created_on desc";

	gmsLog('GET ting announcements '.$sql, '', '', '');		

	# get the parameter from util via Slim 
	$request = Slim::getInstance()->request();
	$limit_search  = $request->get('last');	
	if ($limit_search) {
		if (is_numeric($limit_search)) {
			gmsLog( "GET Announcements. Limit to ". $limit_search,  '', '', '' );
			$sql .= " limit ". $limit_search;
		}
	}
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$announcements = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"announcements": ' . json_encode($announcements) . '}';
	} catch(PDOException $e) {
		gmsError( 'api.getAnnouncements' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

/**  
 * REST GET function for all rows in the table 'rostrum'  (Announcements)
 * @param 
 * @return  via Echo : the json object for all 'rostrum' rows
 * @throws
*/
function getAnnouncement($id) {
	$sql = "select * FROM rostrum WHERE idrostrum=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);            # binds the "id" in sql to the "id" from API
		$stmt->execute();
		$subjectarea = $stmt->fetchObject();  
		$db = null;
		echo json_encode($subjectarea); 
	} catch(PDOException $e) {
		gmsError( 'api.getAnnouncement' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}






/**  
 * REST GET function for all "PROBLEMS" (join of tables)
 * @param  
 * @return  via Echo : the json object for all "PROBLEMS" 
 *          problems are a join of 'input' 'subjarea' and later the 'fellow' table
 * @throws
 * @usage   /api/problems  /api/problems?subjarea=Education		http://localhost/gms/api/problems?subjarea=Education
 *          /api/problems?sort=category  [fellow | suggestion | liked ]
 *          /api/problems?status=approved [ rejected | pending | all ]
*/
function getProblems() {
	# include config for table names for query
	require("config.db.php");

	# get the GET string and parameters from Slim 
	$request = Slim::getInstance()->request();
	
	gmsLog( "GET Problems : request : ",  '', '', '' );

	# setup the sql to search for INPUT table and join with FELLOW and SUBJAREA
	# select to join input, subjarea, fellow for all submitted problems.
	$sql = 
	'select i.idinput, f.nick, f.fname, f.lname, f.email as fellow_email, i.created_by, i.created_dt, i.email, sa.area, 
	i.suggestion, i.details, i.liked, i.disliked from '.
	$table_input. ' i '.
	'join '. $table_subjarea. ' sa on (i.idsubject = sa.idsubjarea) '.
	'join '. $table_fellow. ' f on (i.created_by = f.idfellow)'.
	' where idinput > 0';
	
	# Passed  REST  parameter:  ?status=xxxxxxx		to find All problems or approved problems
	$problem_status = $request->get('status');
	# for testing, allow override of any approved filter when this file exists
	$showall_problems = 'showallproblems.txt';
	if (file_exists($showall_problems)) {
		$problem_status = 'all';
	}
	if ($problem_status ) { 
		# validate passed status 
		$problem_status = strtolower( $problem_status );
		switch ($problem_status) {
			case 'all';			;	break;
			case 'approved';	$sql .= ' and i.approved = "A"'; 	break;
			case 'rejected';	$sql .= ' and i.approved = "R"'; 	break;
			case 'pending';		$sql .= ' and i.approved = "W"'; 	break;
	    break;
		}
	} 	
	
	# if passed param "subjarea filter" (text name of subjarea), then only return those INPUT rows for that subjrea.
	# if passed subjarea is not found, then will return no problems.
	$subjarea_str = $request->get('subjarea');
	#echo 'Parameters : '.$p1.' = '.$request->get($p1);  echo '<br>';
	if ($subjarea_str) { 
		$sa_id = findSubjectArea($subjarea_str); 
		if ($sa_id) {
			$sql .= ' and sa.idsubjarea = "'. $sa_id . '"';
		} else {
			return;
		}
	}
	# Determine sorting of resulting prolbems. Can have a REST passed parameter:  ?sort=name
	$sortcol = 'i.created_dt desc';        // default
	$sortcol_passed = $request->get('sort');
	if ($sortcol_passed ) { 
		# validate passed sort col
		$sortcol_passed = strtolower( $sortcol_passed );
		switch ($sortcol_passed) {
			case 'category';	$sortcol = 'sa.area';	break;
			case 'fellow';		$sortcol = 'f.nick';	break;
			case 'email';		$sortcol = 'f.email';	break;
			case 'suggestion';	$sortcol = 'i.suggestion';	break;
			case 'liked';		$sortcol = 'i.liked desc';	break;
	    break;
		}
	} 
	// append selected or default sorting column
	$sql .= ' order by '. $sortcol . ';' ;	
		
	gmsLog( "GET Problems sql = $sql",  '', '', '' );	
	
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$problems = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"problem": ' . json_encode($problems) . '}';
	} catch(PDOException $e) {
		gmsError( 'api.getProblems' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	return;
}

/**  
 * REST GET function for all COMMENTS for a single PROBLEMS (based on passed $id of INPUT table)
 * @param 
 * @return  via Echo : the json object for all COMMENTS for a single PROBLEMS 
 *          
 * @throws
 * @usage   http://localhost/gms/api/problems/2/comments
*/
function getProblemComments($id) {
	#$request = Slim::getInstance();
	#var_dump( $request );	
	$sql = 'select * from comment co where co.related_to=:id Order by created_dt desc ';
	
	gmsLog('GET ting problems comments '.$sql, '', '', '');		

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  		# preparse
		$stmt->bindParam("id", $id);        # binds the "id" in sql to the "id" from API (parameter passed)
		$stmt->execute();						# execute the query
		$problemsComments = $stmt->fetchAll(PDO::FETCH_OBJ);	# fetch all rows found

		$db = null;
		echo json_encode( $problemsComments ); 
	}  catch(PDOException $e) {
		gmsError( 'api.getProblemComments' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	return;
}


/**  
 * REST GET function to get single "PROBLEM" (join of tables)
 * @param 
 * @return  via Echo : the json object the submitted "PROBLEMS" which is a join of 'input' 
 *          'subjarea' and later the 'fellow' table
 * @throws
*/

function getProblem($id) {
	$sql = "SELECT * FROM input WHERE idinput=:id";

	gmsLog('GET ting problem ID '.$sql, '', '', '');		
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);            # binds the "id" in sql to the "id" from API
		$stmt->execute();
		$problem = $stmt->fetchObject();  
		$db = null;
		echo json_encode($problem); 
	} catch(PDOException $e) {
		gmsError( 'api.getProblem' , $e->getMessage(), '', '' );
		# returns error as json objects
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



/* 
 ----------------------------------------------------------------------
 POST functions
 ----------------------------------------------------------------------
*/

/**  JSW
 * REST function to POST a new 'problem' - Problem will update the table 'input' and link via FK to the 
 *  table 'subjarea' for the category selected.
 * @param 
 * @return  via Echo : the resulting json 'problem' object for the row commited to table 'input' (with id :)
 * @throws
*/
function addProblem() {
	// get the form values posted and convert to json 
	$request = Slim::getInstance()->request();
	$problem = json_decode($request->getBody());

	$subject = $problem->subjarea;
	// find the ID of the subject area from the db (what to do on error?)
	# $subject_id = rand(10, 18);  # for testing
	if ( findSubjectArea($subject) ) {
		$subject_id = findSubjectArea($subject);
	} else {
		gmsLog('addProblem could not find subjarea: '. $subject, '', '', '' );
		$subject_id = 9;     # default is : "General"
	}
	
	$today_date_time = gmdate('Y-m-d H:i:s');     // using UTC for now. can decode to any timezone later.

	$db_table = 'input';
	$sql = "INSERT into input (suggestion, idsubject, email, details, created_dt) VALUES (:suggestion, :idsubject, :email, :details, :created_dt )";

	gmsLog( "POST addProblem sql = ". $sql, '', '', '' );

	# example POST  a submitted item 
	# {"id":"", "suggestion":"It is a REAL problem", "email":"restpost@testemail.com", "subjarea":"Education", "details":"this is how we solve the POST data problem" }
	# {"id":"", "suggestion":"New problem", "email":"restme@testemail.com", "subjarea":"Education", "details":"this is the best problem" }
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  

		// Bind the values to the parameters in the Sql statment
		$stmt->bindParam("suggestion", $problem->suggestion );
		$stmt->bindParam("idsubject", $subject_id );
		$stmt->bindParam("email", $problem->email );
		$stmt->bindParam("details", $problem->details );
		$stmt->bindParam("created_dt", $today_date_time);
		
		// commit to db and return the ID used 
		$stmt->execute();
		$lastID = $db->lastInsertId();
		$problem->id = $lastID;
		$db = null;
		
		// return json object to ui
		echo json_encode($problem); 

	} catch(PDOException $e) {
		gmsError( 'api.postProblem' , $e->getMessage(), '', '' );
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	return;
}


/**  JSW
 * REST function to POST a new 'comment' - for a single problem 
 * @param 
 * @return  via Echo : the resulting json 'comment' object for the row commited to table 'comment'
 * @throws
*/
function addComment() {

	// get the form values posted and convert to json 
	$request = Slim::getInstance()->request();
	$comment = json_decode($request->getBody());
	
	$today_date_time = gmdate('Y-m-d H:i:s');     // using UTC for now. can decode to any timezone later.
	
	$db_table = 'comment';
	$sql = "INSERT into comment (comment_txt, related_to, liked, disliked, created_by) VALUES (:comment_txt, :related_to, :liked, :disliked, :created_by)";
				
	gmsLog( "POST addComment sql = ". $sql, '', '', '' );

	# example POST to add a comment to a problem
	# {"id":"", "comment_txt":"I do not like green eggs and ham", "related_to":"3", "created_by":"1"}
	# 

	// find the user that is posting this comment 
#	$created_by = 1;    // initially, all are by anonymous
	$created_by = $comment->created_by;
	$liked = 0; $disliked = 0;
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  

		// Bind the values from the POST request that are stored in the json object "comment" to the parameters in the Sql statment
		$stmt->bindParam("comment_txt", $comment->comment_txt );
		$stmt->bindParam("related_to", $comment->related_to  );
		$stmt->bindParam("liked", $liked );
		$stmt->bindParam("disliked", $disliked );
		$stmt->bindParam("created_by", $created_by );
		# db col is TIMESTAMP   $stmt->bindParam("created_dt", $today_date_time);

		// commit to db and return the ID used 
		$stmt->execute();
		$lastID = $db->lastInsertId();
		$comment->id = $lastID;
		$db = null;
		
		// return json object from POST 
		echo json_encode($comment); 

	} catch(PDOException $e) {
		gmsError( 'api.postComment' , $e->getMessage(), '', '' );
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



/**  JSW
 * REST function to PUT a like (increment this integer) to an existing problem (input table)
 * @param 
 * @return  via Echo : the resulting json 'problem' object with like and dislike counts for the row updated to the table 'input'
 * @throws
*/
function likeProblem($id) {

	// get the form values posted and convert to json 
	$request = Slim::getInstance()->request();
	$liked_form = json_decode($request->getBody());
	$problem_id = $id;
	
	# example PUT to increment the liked count for a problem
	# {"id":"107", "liked":"33"}   - no form data needed for this.            http://localhost/gms/api/problems/107/like

	$db_table = 'input';
	$liked = 0; $disliked = 0;

	// get the current liked count for this problem - will also validate we have been give a good input.idinput  value (problem identifier)
	$sql = "SELECT idinput, liked, disliked from ". $db_table. " where idinput = :idinput ;"; 
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  

		// Bind the values from the PUT request that are stored in the json object to the parameters in the Sql statment
		$stmt->bindParam("idinput", $problem_id );
		// commit to db and return the ID used 
		$stmt->execute();
		$problem = $stmt->fetchObject(); 
		$liked = $problem->liked;
				
	} catch(PDOException $e) {
		gmsError( 'api.putLike - Get current problem.liked' , $e->getMessage(), '', '' );
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
		return;
	}
	
	// increment counter and setup sql for UPDATE to db
	$liked += 1;
	$sql = "UPDATE ". $db_table. " SET liked = ". $liked. " where idinput = ". $problem_id. ";"; 
	gmsLog( "DB update liked count of $liked to $db_table id $problem_id ". $sql, '', '', '' );
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		// commit to db and return the ID used 
		$stmt->execute();
		//$problem_updated = $stmt->fetchObject(); 
		$db = null;
		$problem->liked = $liked;
		// return json object of the problem and both liked/disliked values
		echo json_encode($problem); 
	} catch(PDOException $e) {
		gmsError( 'api.putLiked' , $e->getMessage(), '', '' );
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



/**  JSW
 * REST function to PUT a dislike (increment this integer) to an existing problem (input table)
 * @param 
 * @return  via Echo : the resulting json 'problem' object with like and dislike counts for the row updated to the table 'input'
 * @throws
*/
function dislikeProblem($id) {
	// get the form values posted and convert to json 
	$request = Slim::getInstance()->request();
	$disliked_form = json_decode($request->getBody());
	$problem_id = $id;
	
	# example PUT to increment the liked count for a problem
	# {"id":"107", "disliked":"33"}   - no form data needed for this.            http://localhost/gms/api/problems/107/dislike

	$db_table = 'input';
	$liked = 0; $disliked = 0;

	// get the current liked count for this problem - will also validate we have been give a good input.idinput  value (problem identifier)
	$sql = "SELECT idinput, liked, disliked from ". $db_table. " where idinput = :idinput ;"; 
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  

		// Bind the values from the PUT request that are stored in the json object to the parameters in the Sql statment
		$stmt->bindParam("idinput", $problem_id );
		$stmt->execute();
		$problem = $stmt->fetchObject(); 
		$disliked = $problem->disliked;
				
	} catch(PDOException $e) {
		gmsError( 'api.putDislike - Get current problem.liked' , $e->getMessage(), '', '' );
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
		return;
	}
	
	// increment counter and setup sql for UPDATE to db
	$disliked += 1;
	$sql = "UPDATE ". $db_table. " SET disliked = ". $disliked. " where idinput = ". $problem_id. ";"; 
	gmsLog( "DB update disliked count of $disliked to $db_table id $problem_id ". $sql, '', '', '' );

	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->execute();
		$db = null;
		$problem->disliked = $disliked;
		// return json object of the problem and both liked/disliked values
		echo json_encode($problem); 
	} catch(PDOException $e) {
		gmsError( 'api.putDislked' , $e->getMessage(), '', '' );
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	
	
}



# ========================================================================================================
# ========================================================================================================
#
#  Wine Cellar - EXAMPLES only --- for REST crud functions
#
# ========================================================================================================
# ========================================================================================================

function addWine() {
	error_log('addWine\n', 3, '/var/tmp/php.log');
	$request = Slim::getInstance()->request();
	$wine = json_decode($request->getBody());
	$sql = "INSERT INTO wine (name, grapes, country, region, year, description) VALUES (:name, :grapes, :country, :region, :year, :description)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $wine->name);
		$stmt->bindParam("grapes", $wine->grapes);
		$stmt->bindParam("country", $wine->country);
		$stmt->bindParam("region", $wine->region);
		$stmt->bindParam("year", $wine->year);
		$stmt->bindParam("description", $wine->description);
		$stmt->execute();
		$wine->id = $db->lastInsertId();
		$db = null;
		echo json_encode($wine); 
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateWine($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$wine = json_decode($body);
	$sql = "UPDATE wine SET name=:name, grapes=:grapes, country=:country, region=:region, year=:year, description=:description WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $wine->name);
		$stmt->bindParam("grapes", $wine->grapes);
		$stmt->bindParam("country", $wine->country);
		$stmt->bindParam("region", $wine->region);
		$stmt->bindParam("year", $wine->year);
		$stmt->bindParam("description", $wine->description);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($wine); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteWine($id) {
	$sql = "DELETE FROM wine WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByName($query) {
	$sql = "SELECT * FROM wine WHERE UPPER(name) LIKE :query ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"wine": ' . json_encode($wines) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



?>