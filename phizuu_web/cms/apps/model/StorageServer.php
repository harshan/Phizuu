<?php
class StorageServer {
    private $path;
    private $appId;
    
    public function __construct($serverPath='', $userId=NULL) {
        $this->path = $serverPath;

        $userInfo = new UserInfo($userId);
        $userArr = $userInfo->getUserInfo();
        $this->appId = $userArr['app_id'];
    }

    public function getPathForCatogory($type, $catagory) {
        $typePath = $this->_getPathForApp() . "/$type";
        if (!file_exists($typePath)) {
            mkdir($typePath);
        }

        $fullPath = "$typePath/$catagory/";
        if (!file_exists($fullPath)) {
            mkdir($fullPath);
        }

        return $fullPath;
    }


    public function getURLForPath($type, $catagory,$fileName) {
        return $this->getBaseURL() . "/static_files/{$this->appId}/$type/$catagory/$fileName";
    }

    public function getBaseURL() {
//        if ($_SERVER['SERVER_NAME'] == 'localhost') {
//            $baseURL = 'http://localhost/phizuu_web';
//        } else {
//            $baseURL = 'http://phizuu.com';
//        }
        
        require_once "../../../config/app_key_values.php";
        if($_SERVER['SERVER_NAME'] == app_key_values::$LIVE_SERVER_DOMAIN){
            $baseURL = 'http://'.app_key_values::$LIVE_SERVER_DOMAIN.'/'.app_key_values::$LIVE_SERVER_URL;
        }elseif($_SERVER['SERVER_NAME'] == app_key_values::$TEST_SERVER_DOMAIN){
            $baseURL = 'http://'.app_key_values::$TEST_SERVER_DOMAIN.'/'.app_key_values::$TEST_SERVER_URL;
        }else{
            $baseURL = 'http://'.$_SERVER['SERVER_NAME'].'/'.app_key_values::$LOCALHOST_SERVER_URL;
        }
        return $baseURL;
    }

    private function _getPathForApp() {
        $appPath = "{$this->path}/{$this->appId}";
        if (!file_exists($appPath)) {
            mkdir($appPath);
        }

        return $appPath;
    }
}

?>
