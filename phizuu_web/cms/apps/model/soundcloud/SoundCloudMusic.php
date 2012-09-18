<?php
class SoundCloudMusic {
    function getAuthRequestURL($callbackUrl) {
        $soundcloud = new Soundcloud(SOUNDCOULD_CONSUMER_KEY, SOUNDCOULD_CONSUMER_SECRET);
        $token = $soundcloud->get_request_token($callbackUrl);
        $_SESSION['oauth_request_token'] = $token['oauth_token'];
        $_SESSION['oauth_request_token_secret'] = $token['oauth_token_secret'];
        return $soundcloud->get_authorize_url($token['oauth_token']);
    }

    function getAccessToken($oauthToken) {
        //echo $oauthToken . ",\n" . $_SESSION['oauth_request_token'] . ",\n" . $_SESSION['oauth_request_token_secret'];
        $soundcloud = new Soundcloud(SOUNDCOULD_CONSUMER_KEY, SOUNDCOULD_CONSUMER_SECRET, $_SESSION['oauth_request_token'], $_SESSION['oauth_request_token_secret']);
        
        $token = $soundcloud->get_access_token($oauthToken);
        return $token;
    }

    function getInfoFromTheDatabase($userId) {
        $sql = "SELECT * FROM soundcloud WHERE user_id = $userId";

        $dao = new Dao();
        $array = $dao->toArray($sql);

        if (count($array) == 0) {
            return FALSE;
        } else {
            return $array[0];
        }
    }

    function removeInfoFromTheDatabase($userId) {
        $sql = "DELETE FROM soundcloud WHERE user_id = $userId";

        $dao = new Dao();
        $array = $dao->query($sql);
    }

    function getUserData($accessTokenArray) {
        $soundcloud = new Soundcloud(SOUNDCOULD_CONSUMER_KEY, SOUNDCOULD_CONSUMER_SECRET, $accessTokenArray['oauth_access_token'], $accessTokenArray['oauth_access_token_secret']);
    
        $me = $soundcloud->request('me');
        $me = new SimpleXMLElement($me);
        $soundCloudInfo = get_object_vars($me);

        if (isset($soundCloudInfo[0]) && $soundCloudInfo[0] == '401 - Unauthorized' && !isset($soundCloudInfo['permalink'])) {
            return FALSE;
        } else {
            return $soundCloudInfo;
        }
    }

    function getTracks($userId, $filter=TRUE) {
        $accessTokenArray = $this->getInfoFromTheDatabase($userId);

        $soundcloud = new Soundcloud(SOUNDCOULD_CONSUMER_KEY, SOUNDCOULD_CONSUMER_SECRET, $accessTokenArray['oauth_access_token'], $accessTokenArray['oauth_access_token_secret']);

        $rtnObj = $soundcloud->request('me/tracks');
        $rtnObj = new SimpleXMLElement($rtnObj);
        $tracks = get_object_vars($rtnObj);

        if (!isset($tracks['track'])) {
            return array();
        }

        if (is_object($tracks['track'])) { //In the case track is not an object array (When there is only one track)
            $tracks['track'] = array($tracks['track']);
        }


        //file_put_contents("prem_ac_test.txt", print_r($tracks['track'],true));
        
        if ($filter)
            return $this->_filterTracks($tracks['track'], $userId);

        return $tracks['track'];
    }

    private function _filterTracks($tracks, $userId) {
        $filteredTracks = array();
        
        foreach ($tracks as $track) {

            $track = $this->objectToArray($track);
            //
            if ( $track['sharing']=='public' && !$this->_findTrackByStreamURL($track['stream-url'],$userId)) {
                $filteredTracks[] = $track;
            }
        }

        return $filteredTracks;
    }

    private function _findTrackByStreamURL($streamURI, $userId) {
        $sql = "SELECT * FROM song WHERE user_id = '$userId' AND stream_uri = '$streamURI'";
        $dao = new Dao();
        $res = $dao->query($sql);
        if (mysql_num_rows($res)>0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getTrack($trackId, $userId) {
        $accessTokenArray = $this->getInfoFromTheDatabase($userId);

        $soundcloud = new Soundcloud(SOUNDCOULD_CONSUMER_KEY, SOUNDCOULD_CONSUMER_SECRET, $accessTokenArray['oauth_access_token'], $accessTokenArray['oauth_access_token_secret']);

        $rtnObj = $soundcloud->request("tracks/$trackId");
        $rtnObj = new SimpleXMLElement($rtnObj);
        $tracks = get_object_vars($rtnObj);
        return $tracks;
    }

    function saveInfoToDatabase($userId, $oauthToken, $oauthTokenSecret) {
        $sql = "INSERT INTO soundcloud (user_id, oauth_access_token,oauth_access_token_secret) VALUES ($userId, '$oauthToken', '$oauthTokenSecret') ON DUPLICATE KEY UPDATE oauth_access_token='$oauthToken', oauth_access_token_secret='$oauthTokenSecret'";

        $dao = new Dao();
        $array = $dao->query($sql);
    }

    function addMusic ($track, $userId, $appWizard = false) {
        $title = isset($track['title'])?"'".mysql_real_escape_string($track['title'])."'":'NULL';
        $album = isset($track['album'])?"'".mysql_real_escape_string($track['album'])."'":'NULL';
        $duration = isset($track['duration'])?"'".$track['duration']."'":'0';
        $streamUrl = isset($track['stream-url'])?"'".$track['stream-url']."'":'NULL';
        $soundCloudUrl = isset($track['soundcloud-url'])?"'".$track['soundcloud-url']."'":'NULL';
        $permalinkUrl = isset($track['permalink-url'])? "'".$track['permalink-url']."'" : 'NULL';
        $itunesUri = isset($track['itunes_uri'])? "'".$track['itunes_uri']."'" : 'NULL';
        $year = isset($track['release-year'])? "'".$track['release-year']."'" : 'NULL';
        $description = isset($track['description'])? "'".mysql_real_escape_string($track['description'])."'" : 'NULL';
        $id = isset($track['id'])? "'".$track['id']."'" : 'NULL';
        $size = isset($track['file_size'])? "'".$track['file_size']."'" : '0';
        $genre = isset($track['genre'])? "'".mysql_real_escape_string($track['genre'])."'" : 'NULL';
        $iphone = $appWizard?"'1'":"''";

        $sql = "INSERT INTO `song` (".
        "`title`,".
        "`album`,".
        "`duration`,".
        "`stream_uri`,".
        "`soundcloud_uri`,".
        "`permalink`,".
        "`itunes_uri`,".
        "`year`,".
        "`note`,".
        "`image_id`,".
        "`playlist`,".
        "`user_id`,".
        "`music_id`,".
        "`iphone_status`,".
        "`file_capacity`,".
        "`new_one`,".
        "`image_uri`,".
        "`order`,".
        "`itunes_affiliate_url`,".
        "`genre`".
        ") VALUES (".
        "$title,".
        "$album,".
        "$duration,".
        "$streamUrl,".
        "$soundCloudUrl,".
        "$permalinkUrl,".
        "$itunesUri,".
        "$year,".
        "$description,".
        "NULL,".
        "NULL,".
        "$userId,".
        "$id,".
        "$iphone,".
        "$size,".
        "'1',".
        "NULL,".
        "'0',".
        "NULL,".
        "$genre".
        ");";
    
        $dao = new Dao();
        $dao->query($sql);
        return mysql_insert_id();
    }

    function uploadTrack ($fileName, $tempFile, $userId, $title, $appWizard = false) {
        $userInfo = $this->getUserInfo($userId);

        if (!$appWizard && $userInfo['package_id']  == 1) {
            $access = 'public';
            $accessTokenArray = $this->getInfoFromTheDatabase($userId);

            if ($accessTokenArray == FALSE)
                return FALSE;
            $soundcloud = new Soundcloud(SOUNDCOULD_CONSUMER_KEY, SOUNDCOULD_CONSUMER_SECRET, $accessTokenArray['oauth_access_token'], $accessTokenArray['oauth_access_token_secret']);
        } else {
            $access = 'private';
            $soundcloud = new Soundcloud(
                                SOUNDCOULD_CONSUMER_KEY,
                                SOUNDCOULD_CONSUMER_SECRET,
                                SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN,
                                SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN_SECRET);
        }

        if($title=='') {
            $title = $fileName;
        }

        $post_data = array(
            'track[title]' => stripslashes($title),
            'track[asset_data]' => $tempFile,
            'track[sharing]' => $access,
            'track[streamable]'=>'true'
        );

        $mime = 'audio/mpeg'; //For MP3 files

        $response = $soundcloud->upload_track($post_data, $mime);

        if ($response) {
            $response = new SimpleXMLElement($response);
            $response = get_object_vars($response);
        } else {
            $response = FALSE;
        }

        //unlink(realpath($tempFile));
        return $response;
    }

    function objectToArray($result) {
        $array = array();
        foreach ($result as $key=>$value) {
            if (is_object($value)) {
                $array[$key]=$this->objectToArray($value);
            }
            if (is_array($value)){
                $array[$key]=$this->objectToArray($value);
            } else {
                $array[$key]=$value;
            }
        }
        return $array;
    }

    function arrayToObject($array = array()) {
        if (!empty($array)) {
            $data = false;

            foreach ($array as $akey => $aval) {
                $data -> {$akey} = $aval;
            }

            return $data;
        }
        return false;
    }

    function getUserInfo($usrId) {
        $sql = "SELECT * FROM user WHERE id = $usrId";
        $dao = new Dao();
        $arr = $dao->toArray($sql);

        return $arr[0];
    }

    function addCategory($name, $userId) {
        $dao = new Dao();

        $sql = "SELECT id FROM music_categories WHERE user_id='$userId'";
        $res = $dao->query($sql);
        $maxCats = 3;
        if (mysql_num_rows($res)>=$maxCats) {
            return array(FALSE, "Maximum limit of categories ($maxCats) exeeded! Remove or edit categories by using 'Manage'.");
        }

        $sql = "SELECT id FROM music_categories WHERE user_id='$userId' AND name='$name'";
        $res = $dao->query($sql);
        if (mysql_num_rows($res)>=1) {
            return array(FALSE, "There is a category with the same name as '$name'");
        }

        $name = mysql_escape_string($name);
        $sql = "INSERT INTO music_categories VALUES(NULL, '$userId', '$name')";

        try {
            $dao->query($sql);
        } catch (Exception $exc) {
            echo $exc->getMessage();
            return array(FALSE, 'Error occured while adding category!');
        }

        return array(TRUE, mysql_insert_id());
    }

    function listCategories($userId) {
        $dao = new Dao();
        $sql = "SELECT * FROM music_categories WHERE user_id='$userId'";
        $res = $dao->toArray($sql);
        return $res;
    }

    function setDefaultCategory($name, $userId) {
        $dao = new Dao();

        $name = mysql_escape_string($name);
        $sql = "INSERT INTO music_default_category VALUES ('$userId','$name') " .
               "ON DUPLICATE KEY UPDATE name='$name'";

        $dao->query($sql);
    }

    function getDefaultCategory ($userId) {
        $dao = new Dao();
        $sql = "SELECT * FROM music_default_category WHERE user_id='$userId'";
        $arr = $dao->toArray($sql);
        
        if (count($arr)==0) {
            $arr[0] = array ('name' => 'Others', 'user_id'=>$userId);
        }

        $arr[0]['id'] = '0';

        return $arr[0];
    }

    function deleteCategory($id) {
        $dao = new Dao();

        $sql = "DELETE FROM music_categories WHERE id='$id'";
        $dao->query($sql);
        if(mysql_affected_rows()==1) {
            $sql = "UPDATE song SET category_id='0' WHERE category_id='$id'";
            $dao->query($sql);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function editCategory($id, $value, $userId) {
        if ($id==0) {
            $this->setDefaultCategory($value, $userId);
        } else {
            $dao = new Dao();
            $sql = "UPDATE music_categories SET name='$value' WHERE user_id='$userId' AND id='$id'";
            $dao->query($sql);
        }
    }

    /*function isAppHasSoundCloudProblem($appId) {
        $sql = "SELECT apps_with_sc_problem.`status` FROM user LEFT JOIN apps_with_sc_problem ON user.app_id=apps_with_sc_problem.app_id WHERE apps_with_sc_problem.app_id IS NOT NULL AND user.app_id=13";

        $dao = new Dao();
        $arr = $dao->toArray($sql);
        if (count($arr) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }*/
}
?>
