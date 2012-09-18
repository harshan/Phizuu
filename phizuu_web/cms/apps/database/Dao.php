<?php
class Dao {

    private $dbConn;

    public function Dao($host=NULL, $username = NULL, $password = NULL, $database = NULL) {
	if ($host == NULL) {
	    $host = CONF_DATABASE_HOST;
	    $username = CONF_DATABASE_USERNAME;
	    $password = CONF_DATABASE_PASSWORD;
	    $database = CONF_DATABASE_NAME;
	}
	
	$dbConn = mysql_connect($host, $username, $password);
	
	if (!$dbConn) {
	    throw new Exception("Couldn't connect to the Database server: " . mysql_error());
	}

	if (!mysql_select_db($database, $dbConn)) {
	    throw new Exception("Couldn't select database: " . mysql_error());
	}
	
	mysql_set_charset('utf8');
	$this->dbConn = $dbConn;
    }

    public function query($sql) {
	$result = mysql_query($sql, $this->dbConn);

	if (!$result) {
	    $message = 'Invalid query: ' . mysql_error() . "\n";
	    $message .= 'Whole query: ' . $sql;
	    throw new Exception($message);
	}

	return $result;
    }

    /* Creates array from resutls */

    public function getArray($result, $type = MYSQL_BOTH) {
	$array = array();
	while ($row = mysql_fetch_array($result, $type)) {
	    $array[] = $row;
	}
	mysql_free_result($result);

	return $array;
    }

    public function toArray($sql, $type = MYSQL_BOTH) {
	return $this->getArray($this->query($sql), $type);
    }

    public function toObject($sql) {
	$array = array();
	$result = $this->query($sql);
	while ($row = mysql_fetch_object($result)) {
	    $array[] = $row;
	}
	mysql_free_result($result);

	return $array;
    }

    public function close() {
	mysql_close($this->dbConn);
    }

}

?>