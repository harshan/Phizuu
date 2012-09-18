<?php
define('OLD_URL_TYPE', 1);
define('NEW_URL_TYPE', 2);

/* Parsing the log file */

class LogFileParser {

    private $logFile;
	private $rootPath;
    private $urlPattern = '/^([0-9]+.[0-9]+.[0-9]+.[0-9]+) - - \[([0-9]+)\/([a-zA-Z]+)\/([0-9]+)\:([0-9:]+) [\-\+0-9]+\] "GET (\/[a-z\-A-Z]+\/([0-9]+)[0-9a-zA-Z$-_\.\+\!\*\'\(\),]*) HTTP\/[0-9\.]+" [0-9]+ [0-9]+ "-" "Mozilla\/[0-9\.]+ \([a-zA-Z ]+; [a-zA-Z ]+([0-9\.]+); ([0-9a-zA-Z]+)\) (phizuu:connect|PhizuuConnect)"$/';

    public function LogFileParser($logFile, $rootPath) {
        $this->logFile = $logFile;
	$this->rootPath = $rootPath;
    }

    /** Parsing a file from the last added entry to the database */
    public function addRecordsToDatabase($fromCount, $limit = NULL) {

        if (file_exists($this->logFile)) {
            $ipList = array();
            $file = fopen($this->logFile, 'r');
            $this->_seekFileToEntry($fromCount, $file);
            $count = 0;
            $lineCount = $fromCount;
            $fileEnded = true;
            while (!($fileEnded = feof($file))) {
                if (($limit != NULL) && $count == $limit) {
                    break;
                }
                if ($lineCount%1000==0) {
                    //echo "$lineCount ";
                }
                $line = fgets($file);
                if (($record = $this->_parseLine($line))!==FALSE) {
                    $record = $this->_parseLine($line);
                    $id = $record->addToDatabase();
                    $count++;
                    //echo ". ";
                } else {
                    //echo $line."\r\n";
                }
                $lineCount++;
                
                $this->putLastCount($lineCount);
            }

            fclose($file);
            //$this->_addIPLocations($ipList);

            if ($fileEnded)
                return -1;
            else
                return $lineCount;
        } else {
            throw new Exception("Couldn't find the file: {$this->logFile}");
        }
    }

    /** Returns a LogFileEntry with the given string line */
    private function _parseLine($line) {
        if (preg_match($this->urlPattern, $line, $matches)) {
            $ip = $matches[1];
            $date = $matches[4] . '-' . $this->_convertMonthToNumber($matches[3]) . '-' . $matches [2];
            $time = $matches[5];
            $url = $matches[6];
            $appId = $matches[7];
            $os = $matches[8];
            $uuid = $matches[9];
            return new LogFileEntry($ip, $date, $time, $url, $os, $uuid, $appId);
        } else {
            return false;
        }
    }

    /** Seek the file to the last entry */
    private function _seekFileToEntry($entry, $fileHandle) {
        $count = 0;
        while (!feof($fileHandle) && ($count != $entry)) {
            $line = fgets($fileHandle);
            $count++;
        }
    }

    /** Get last count */
    public function getLastCount() {
        $handle = fopen("{$this->rootPath}/configure/records.txt", 'r');
        $line = fgets($handle);
        $parts = explode('=', $line);
        fclose($handle);

        return trim($parts[1]);
    }

    /** Put last count */
    public function putLastCount($count) {
        $handle = fopen("{$this->rootPath}/configure/records.txt", 'w');
        $line = "last_record = $count";
        fwrite($handle, $line);
        fclose($handle);
    }

    /** Month number from string */
    private function _convertMonthToNumber($monthString) {
        switch ($monthString) {
            case 'Jan':
                return 1;
            case 'Feb':
                return 2;
            case 'Mar':
                return 3;
            case 'Apr':
                return 4;
            case 'May':
                return 5;
            case 'Jun':
                return 6;
            case 'Jul':
                return 7;
            case 'Aug':
                return 8;
            case 'Sep':
                return 9;
            case 'Oct':
                return 10;
            case 'Nov':
                return 11;
            case 'Dec':
                return 12;
            default;
                throw new Exception("Invalid month string");
        }
    }

    private function _isPhizuuConnectEntry($line) {
        if (strlen($line) < 1)
            return false;

        $lineEnd = "PhizuuConnect"; // Note: PhizuuConnect identifier is deprecated and no longer maintained
        $len = strlen($lineEnd);

        if (substr($line, strlen($line) - $len - 2, $len) === "PhizuuConnect") {
            return OLD_URL_TYPE;
        }

        $lineEnd = "phizuu:connect"; // Note: PhizuuConnect identifier is deprecated and no longer maintained
        $len = strlen($lineEnd);
        if (substr($line, strlen($line) - $len - 2, $len) === "phizuu:connect") {
            return NEW_URL_TYPE;
        }

        return false; // If not matches with anything
    }



    /*private function _addIPLocations($ipList) {
        $count = 0;
        $logFileEntry = new LogFileEntry();
        foreach ($ipList as $ip) {
            $logFileEntry->setLocationArray($this->_getCountryCode($ip[1]));
            $logFileEntry->setConnectionId($ip[0]);
            $logFileEntry->addIPLocation();
        }
    }*/


}
