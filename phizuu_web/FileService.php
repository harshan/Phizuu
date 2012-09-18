<?php
class FileService {
    private static $FILE_SERVICE_URL = "http://phizuu.com/storage";
    public static $SUCCESS = 1;
    public static $ERROR_FILE_EXIST = 2;
    public static $ERROR_UNKNOWN = 3;
    public static $ERROR_FILE_NOT_FOUND = 4;

    private $lastError;

/**
 * Adds files into the file server using file service
 *
 * @param String $localFilePath Local path of the file to be uploaded
 * @param String $fileData      if local path of the file not specified the data of the file to be uploaded
 * @param String $contentType   Type of the file such as image, video, music, etc;
 * @param String $type          Type of the file such as fan_wall, tour_image, etc;
 * @param String $imageName     Name of the file to be uploaded
 * @return String URL of the added file of FALSE on failure. Find the error using getLastError()
 */
    public function addFile($contentType, $type, $imageName, $localFilePath = NULL, $fileData = NULL) {
        $lineFeed = "\r\n";
        $fileServiceURL = self::$FILE_SERVICE_URL;

        $header = array("Content-type: multipart/form-data; boundary=---------------daAKdfkfsdkKdf8s");

        //File Section
        $data = $lineFeed . "-----------------daAKdfkfsdkKdf8s" . $lineFeed;
        $data .= "Content-Disposition: form-data; name=\"image\"; filename=\"$imageName.jpg\"" . $lineFeed;
        $data .= "Content-Type: application/octet-stream" . $lineFeed . $lineFeed;

        if($localFilePath == NULL) {
            $fileContent = $fileData;
        } else {
            $fileContent = file_get_contents($localFilePath);
        }
        
        $data .= $fileContent . $lineFeed; //Data

        $data .= "-----------------daAKdfkfsdkKdf8s". $lineFeed;

        $url = "$fileServiceURL/$contentType/$type/$imageName";

        /*$ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 0); // Errors should be reported
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_HEADER, 0); //No header in output
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch); // run the whole process

        $details = curl_getinfo($ch);
        $httpCode = $details['http_code'];
        curl_close($ch);
        
        if ($httpCode == 201) { //File created
            $this->lastError = self::$SUCCESS;
            $resultObj = json_decode($result);
            return $resultObj->url;
        } elseif($httpCode==409) { //File exists
            $this->lastError = self::$ERROR_FILE_EXIST;
            return FALSE;
        } else {
            $this->lastError = self::$ERROR_UNKNOWN;
            return FALSE;
        }*/

        $params = array('http' => array(
           'method' => 'POST',
           'header' => $header,
           'content' => $data
        ));

        $ctx = stream_context_create($params);
        $fp = fopen($url, 'rb', false, $ctx);

        if (!$fp) {
            $this->lastError = self::$ERROR_FILE_EXIST;
            return FALSE;
        }

        $response = stream_get_contents($fp);
        $this->lastError = self::$SUCCESS;
        $resultObj = json_decode($response);
        return $resultObj->url;
    }

/**
 * Deletes files from the file server using file service
 *
 * @param String $contentType   Type of the file such as image, video, music, etc;
 * @param String $type          Type of the file such as fan_wall, tour_image, etc;
 * @param String $imageName     Name of the file to be uploaded
 * @return Return TRUE on sucsses and FALSE on failure. Find the error using getLastError()
 */

    public function deleteFile($contentType, $type, $imageName){
        $fileServiceURL = self::$FILE_SERVICE_URL;
        $url = "$fileServiceURL/$contentType/$type/$imageName";

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch); // run the whole process
        
        $details = curl_getinfo($ch);
        $httpCode = $details['http_code'];
        curl_close($ch);

        if ($httpCode == 200) { //File deleted
            $this->lastError = self::$SUCCESS;
            return TRUE;
        } elseif ($httpCode == 404) { //Image not found
            $this->lastError = self::$ERROR_FILE_NOT_FOUND;
            return FALSE;
        } else {
            $this->lastError = self::$ERROR_UNKNOWN;
            return FALSE;
        }
    }
    
/**
 * Checks whether a file is on the server or not
 *
 * @param String $contentType   Type of the file such as image, video, music, etc;
 * @param String $type          Type of the file such as fan_wall, tour_image, etc;
 * @param String $imageName     Name of the file to be uploaded
 * @return Return TRUE if found or else FALSE
 */

    public function isFileExist($contentType, $type, $imageName){
        $fileServiceURL = self::$FILE_SERVICE_URL;
        $url = "$fileServiceURL/$contentType/$type/$imageName";

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch); // run the whole process

        $details = curl_getinfo($ch);
        $httpCode = $details['http_code'];
        curl_close($ch);

        if ($httpCode == 200) { //File found
            $this->lastError = self::$SUCCESS;
            return TRUE;
        } elseif ($httpCode == 404) { //Image not found
            $this->lastError = self::$ERROR_FILE_NOT_FOUND;
            return FALSE;
        } else {
            $this->lastError = self::$ERROR_UNKNOWN;
            return FALSE;
        }
    }

    public function getLastError() {
        return $this->lastError;
    }
}


/*
 * USAGE OF THE CLASS
 *
 */
$fileService = new FileService();

$url = $fileService->addFile('image', 'fan_wall', 'phizuu10.png', 'images/free.png');

if ($url == FALSE) {
    if ($fileService->getLastError() == FileService::$ERROR_FILE_EXIST) {
        echo "File exsists";
    }
} else {
    echo $url;
}
/*
if($fileService->deleteFile('image', 'fan_wall', 'phizuu.png')) {
    echo "File deleted";
} else {
    if($fileService->getLastError()==FileService::$ERROR_FILE_NOT_FOUND) {
        echo "File not found";
    }
}

 */
?>
