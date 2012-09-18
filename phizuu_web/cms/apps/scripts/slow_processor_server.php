<?php
/* This is a script to parse data in the log file to the database */
require_once ('../config/config.php');
require_once ('../model/data_extractors/LogFileEntry.php');
require_once ('../model/data_extractors/LogFileParser.php');


$limit = NULL;
if (isset($_GET['limit'])) {
	$limit = $_GET['limit'];
}

$logFileParser = new LogFileParser(CONF_LOG_FILE_PATH);
$from = $logFileParser->getLastCount();
echo $logFileParser->addRecordsToDatabase($from , $limit);
?>