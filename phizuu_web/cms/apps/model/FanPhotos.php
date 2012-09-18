<?php
class FanPhotos {
    private $appId;
    private $userId;

    function  __construct($userId) {
        $userInfoObj = new UserInfo($userId);
        $userInfo = $userInfoObj->getUserInfo();
        $this->userId = $userId;
        $this->appId = $userInfo['app_id'];
    }

    public function getAllEventPhotos() {
        $eventPhotoArr = array();
        if(($events = $this->_getEvents())!==FALSE) {
            foreach ($events->events as $event) {
                $eventId = $event->id;
                $eventPhotos = $this->_getEventPhotos($eventId);
                if ($eventPhotos !== FALSE && isset($eventPhotos->images)) {
                    $eventPhotoArr = array_merge($eventPhotoArr, $eventPhotos->images);
                }
            }
        }
        return ($eventPhotoArr);
    }

    private function _getEvents() {
        $resource = $this->appId . "/events/";
        list($result, $code) = PhizuuConnectAPI::callAPI($resource);
        if($code == 200) {
            return $result;
        } else {
            return FALSE;
        }
    }

    private function _getEventPhotos($eventId) {
        $resource = $this->appId . "/event_images/$eventId";
        list($result, $code) = PhizuuConnectAPI::callAPI($resource);
        if($code == 200) {
            return $result;
        } else {
            return FALSE;
        }
    }
}

?>
