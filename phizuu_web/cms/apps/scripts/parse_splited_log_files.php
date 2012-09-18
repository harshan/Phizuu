<?php
$rootPath = dirname(__FILE__)."/..";

require_once ("$rootPath/config/config.php");
require_once ("$rootPath/model/data_extractors/LogFileEntry.php");
require_once ("$rootPath/model/data_extractors/LogFileParser.php");
require_once ("$rootPath/scripts/phpwebyip2country/ip2country.php5.php");
require_once ("$rootPath/database/Dao.php");

if(!isset($argc) || $argc<2) {
    echo "Script need the path to the dir as a argument\n";
    exit;
}

$lastFileNameStorage = "$rootPath/configure/last_file_processed.txt";
$lastFileName = file_get_contents($lastFileNameStorage);
if ($lastFileName=="") {
    logError("The last file name is empty", $rootPath);
    exit;
}


$logFilePath = $argv[1];
$logFileName = $logFilePath . $lastFileName;

if (!file_exists($logFileName)) {
    echo ("File doesn't exists '$logFileName'\n");
    exit;    
}

$logFileParser = new LogFileParser($logFileName, $rootPath);
$from = $logFileParser->getLastCount();
$recordCount = $logFileParser->addRecordsToDatabase($from);

if ($recordCount==-1) {
    logError("Successfully finished parsing '$lastFileName'", $rootPath);
    $nextFileName = getNextFileName($lastFileName);
    file_put_contents($lastFileNameStorage, $nextFileName);
    logError("Next file '$nextFileName'", $rootPath);
    $logFileParser->putLastCount(0);
    logError("Next file count reset", $rootPath);
}

function logError($error, $rootPath) {
    if ($handle = fopen("$rootPath/logs/log_file_parser.log", 'a')) {
        $errorText =  date("D M j G:i:s T Y") . " --- " . $error . "\n";
        fwrite($handle, $errorText);
        fclose($handle);
    }
}

function getNextFileName($currentFileName) {
    if (preg_match('/^part.([0-9]+)$/', $currentFileName, $matches)) {
        return 'part.'.str_pad(($matches[1]+1),4,"0", STR_PAD_LEFT);
    } else {
        return "none";
    }
}
?>
