<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FanContent
 *
 * @author Dhanushka
 */

class FanContent {
    private $appId;
    private $userId;
    const BASE_URL = 'http://connect.phizuu.com/client/';

    public function  __construct($appId, $userId) {
        $this->appId = $appId;
        $this->userId = $userId;
    }

    public function getListOfTours() {
        $url = self::BASE_URL . $this->appId . "/events";

        $json = @file_get_contents($url);

        if($json == FALSE) {
            return FALSE;
        } else {
            return json_decode($json);
        }
    }

    public function getFanPhotos($tourId) {
        $url = self::BASE_URL . $this->appId . "/event_images/$tourId";
        $json = @file_get_contents($url);

        if($json == FALSE) {
            return FALSE;
        } else {
            return json_decode($json);
        }
    }

    public function getWallPosts($nextId = NULL) {
        $url = self::BASE_URL . $this->appId . "/wall";

        if ($nextId != NULL)
            $url .= "/$nextId";

        $json = @file_get_contents($url);

        if($json == FALSE) {
            return FALSE;
        } else {
            return json_decode($json);
        }
    }

    public function getReplies($commentId) {
        $url = self::BASE_URL . "comment/replies/$commentId";

        $json = @file_get_contents($url);

        if($json == FALSE) {
            return FALSE;
        } else {
            return json_decode($json);
        }
    }

    public function deleteFanPhoto($tourId, $photoId) {
        $url = self::BASE_URL . $this->appId . "/event_images/$tourId/$photoId";

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch); // run the whole process
        echo $result;
        curl_close($ch);
    }

    public function deleteComment($commentId) {
        $url = self::BASE_URL . "comment/$commentId";

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch); // run the whole process
        echo $result;
        curl_close($ch);
    }
}
?>
