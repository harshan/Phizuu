<?php

class MySQLResult {

var $mysql;

var $query;

function MySQLResult(&$mysql, $query)
{
$this->mysql = &$mysql;
$this->query = $query;
}


function fetch()
{
	if ($row = mysql_fetch_array($this->query, MYSQL_ASSOC)) {
	return $row;
	} else if ( $this->size() > 0 ) {
	mysql_data_seek($this->query, 0);
	return false;
	} else {
	return false;
	}
}

function size()
{
return $size=mysql_num_rows($this->query);
}

	function isError()
	{
	return $this->mysql->isError();
	}
}

// Connect to MySQL
$db = new MySQL($host, $usna, $pwd, $db);

?>
