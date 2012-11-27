<?php
/* GMS database API 
 * 
 * REST functions using Slim framework for PHP (connects REST to php functions). Will return all database data in json objects
 * 2012-11-23 (JSW) created GET functions for problems (input table joined with subjare and fellow)
 *   users (fellow table) , subjectareas (subjectarea table),  inputs (input table)
 * Revisions
 * 2012-11-26 - added this revisions section of the comment
*/

# GMS basic fuctions.
require('functionsGMS.php');


# REST api setup using Slim : Rest Noun / object , corresponding local function to execute
require('Slim/Slim.php');
$app = new Slim();
$app->get('/problems',      'getProblems');
$app->get('/problems/:id',	'getProblem');
$app->post('/problems',	'addProblem');
$app->get('/users',     'getUsers');
$app->get('/users/:id',	'getUser');
$app->get('/subjectareas',      'getSubjectareas');
$app->get('/subjectareas/:id',	'getSubjectarea');
$app->get('/inputs',      'getInputs');
$app->get('/inputs/:id',	'getInput');
$app->run();





/**  
 * Database open based on gms config file 
 * @param 
 * @return $dbh     PDO database handle
 * @throws
*/
function getConnection() {
	require("config.db.php");
	$dbh = new PDO($pdo_connect, $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
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
	try {
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
 * REST GET function for all "PROBLEMS" (join of tables)
 * @param 
 * @return  via Echo : the json object for all "PROBLEMS" problems are a join of 'input' 
 *          'subjarea' and later the 'fellow' table
 * @throws
*/
function getProblems() {
	# include config for table names for query
	require("config.db.php");
	# select to join input, subjarea, fellow for all submitted problems.
	$sql = 
	'select f.nick, f.fname, f.lname, f.email as fellow_email, i.created_by, i.created_dt, i.email, sa.area, 
	i.suggestion, i.details, i.liked, i.disliked from '.
	$table_input. ' i '.
	'join '. $table_subjarea. ' sa on (i.idsubject = sa.idsubjarea) '.
	'join '. $table_fellow. ' f on (i.created_by = f.idfellow)'.
	' where idinput > 0'
	.' order by sa.area, i.created_dt '
	.';'
	;	
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
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);            # binds the "id" in sql to the "id" from API
		$stmt->execute();
		$problem = $stmt->fetchObject();  
		$db = null;
		gmsLog( 'getProblem', '', '', '' );		
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
	gmsLog('addProblem', '', '', '' );

	// get the form values posted and convert to json 
	$request = Slim::getInstance()->request();
	$problem = json_decode($request->getBody());

	$subject = strtoupper($problem->subjarea);
	// find the ID of the subject area from the db (what to do on error?)
	$subject_id = rand(10, 18);  # for testing
	$today_date_time = gmdate('Y-m-d H:i:s');     // using UTC for now. can decode to any timezone later.

	$db_table = 'input';
	$sql = "INSERT into input (suggestion, idsubject, email, details, created_dt) VALUES (:suggestion, :idsubject, :email, :details, :created_dt )";
				
	gmsLog( "POST Request : ". $request->getBody(),  '', '', '' );
	gmsLog( "DB Insert : ". $sql, '', '', '' );

	# example POST 
	# {"id":"", "suggestion":"It is a REAL problem", "email":"restpost@testemail.com", "subjarea":"Education", "details":"this is how we solve the POST data problem" }
	
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
#echo "email - ";

	} catch(PDOException $e) {
		gmsError( 'api.postProblem' , $e->getMessage(), '', '' );
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