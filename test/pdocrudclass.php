<?php 

#pdocrudclass.php
# example class for PDO based CRUD functions.
# from : 
# 
class MySQLDatabase{
    public $db;
    function __construct($dsn, $user=null, $pass=null){
        $this->dsn = $dsn;
        $this->user = $user;
        $this->pass = $pass;
        //Connect
        $this->connect();
    }

    function connect(){
        try{
            $this->db = new PDO($this->dsn, $this->user, $this->pass);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        }catch (Exception $e){
            die('Cannot connect to databse. Details:'.$e->getMessage());
        }
    }

    function Select($table, $fieldname=null, $fieldvalue=null){
        $sql = "SELECT * FROM $table"; 
        $sql .=($fieldname != null && $fieldvalue != null)?" WHERE $fieldname=:id":null;
        $statement = $this->db->prepare($sql);
        if($fieldname != null && $fieldvalue != null){$statement->bindParam(':id', $fieldvalue);}
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function Insert($table, $values){
        $fieldnames = array_keys($values[0]);

        $sql = "INSERT INTO $table";
        $fields = '( ' . implode(' ,', $fieldnames) . ' )';
        $bound = '(:' . implode(', :', $fieldnames) . ' )';
        $sql .= $fields.' VALUES '.$bound;

        $statement = $this->db->prepare($sql);
        foreach($values as $vals){
            $statement->execute($vals);
        }
    }

    function Update($table, $fieldname, $value, $where_key, $id){
        $sql = "UPDATE `$table` SET `$fieldname`= :value WHERE `$where_key` = :id";
        $statement = $this->db->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':value', $value);
        $statement->execute();
    }

    function Delete($table, $fieldname=null, $id=null){
        $sql = "DELETE FROM `$table`";
        $sql .=($fieldname != null && $id != null)?" WHERE $fieldname=:id":null;
        $statement = $this->db->prepare($sql);
        if($fieldname != null && $id != null){$statement->bindParam(':id', $id);}
        $statement->execute();
    }

}


//Sample Usage
$db = new MySQLDatabase('mysql:host=localhost;dbname=test','root','password');


//Multi insert:
$insert = array(array('some_col'=>'This was inserted by the $db->Insert() method'),
                array('some_col'=>'Test insert 2'),
                array('some_col'=>'Test insert 3'),
                );
$db->Insert('pdo_test', $insert);

//Select All
$result = $db->Select('pdo_test');
/*
Array
(
    [0] => Array
        (
            [id] => 2
            [some_col] => This was inserted by the $db->Insert() method
        )

    [1] => Array
        (
            [id] => 3
            [some_col] => Test insert 2
        )

    [2] => Array
        (
            [id] => 4
            [some_col] => Test insert 3
        )
)
*/

//Single select
$result = $db->Select('pdo_test','id','2');
/*
Array
(
    [0] => Array
        (
            [id] => 2
            [some_col] => This was inserted by the $db->Insert() method
        )

)
*/

/* Delete Single record*/
$db->Delete('pdo_test', 'id', '2');

/*Delete All*/
$db->Delete('pdo_test');

//Array ( ) 
$result = $db->Select('pdo_test');
?>