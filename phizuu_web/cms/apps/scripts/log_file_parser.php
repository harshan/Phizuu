<?php
/* This is a script to parse data in the log file to the database */
$rootPath = dirname(__FILE__)."/..";
require_once ("$rootPath/config/config.php");
require_once ("$rootPath/model/data_extractors/LogFileEntry.php");
require_once ("$rootPath/model/data_extractors/LogFileParser.php");
require_once ("$rootPath/scripts/phpwebyip2country/ip2country.php5.php");
require_once ("$rootPath/database/Dao.php");

if(isset($argc) && $argc>1) {
    $logFileName = $argv[1];
} else {
    $logFileName = $rootPath.'/'.CONF_LOG_FILE_PATH;
} 

$logFileParser = new LogFileParser($logFileName, $rootPath);
$from = $logFileParser->getLastCount();
$recordCount = $logFileParser->addRecordsToDatabase($from);
?>
