<?php
class PhizuuConnectAPI {
    static $ApiBaseURL = 'http://connect.phizuu.com/client/';

    static function callAPI($resource, $method='GET', $data=NULL) {
        $url = self::$ApiBaseURL . $resource;

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
        if($method=='POST') {
            curl_setopt($ch, CURLOPT_POST, 1); // set POST method
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $result = curl_exec($ch); // run the whole process
        $resultObj = json_decode($result);
        $details = curl_getinfo($ch);
        curl_close($ch);

        return array($resultObj, $details['http_code']);
    }
}

?>
