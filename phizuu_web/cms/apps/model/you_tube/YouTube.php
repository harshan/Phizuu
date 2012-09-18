<?php
class YouTube {
    public $username;
    public $videoFilter;

    public function __construct($username) {
        $this->username = $username;
        $this->videoFilter = urlencode("entry(title,published,media:group(yt:duration,media:description,media:thumbnail,media:player,yt:videoid,media:player))");
    }

    public function getPlayLists() {
        $filter = urlencode("entry(title,yt:countHint,yt:playlistId)");
        $url = "http://gdata.youtube.com/feeds/api/users/{$this->username}/playlists?v=2&fields=$filter";
        $data = $this->_getData($url);

        if($data===FALSE) {
            return FALSE;
        } else {
            $playLists = array();

            $entries = isset($data['feed']['entry'])?$data['feed']['entry']:array();
            foreach ($entries as $entry) {
                $playList = array();
                $playList['title'] = isset($entry['title']['$t'])?$entry['title']['$t']:'';
                $playList['count'] = isset($entry['yt$countHint']['$t'])?$entry['yt$countHint']['$t']:'';
                $playList['id'] = isset($entry['yt$playlistId']['$t'])?$entry['yt$playlistId']['$t']:'';

                $playLists[] = $playList;
            }

        }
        
        return $playLists;
    }

    public function getUploadedVideos() {
        $url = "http://gdata.youtube.com/feeds/api/users/{$this->username}/uploads?v=2&fields={$this->videoFilter}";
        $data = $this->_getData($url);

        $videos = array();
        if($data===FALSE) {
            return FALSE;
        } else {
            $videos = $this->_parseVideos($data);
        }

        return $videos;
    }

    public function getFavoritesVideos() {
        $url = "http://gdata.youtube.com/feeds/api/users/{$this->username}/favorites?v=2&fields={$this->videoFilter}";

        $data = $this->_getData($url);

        $videos = array();
        if($data===FALSE) {
            return FALSE;
        } else {
            $videos = $this->_parseVideos($data);
        }

        return $videos;
    }

    public function getVideosOfPlayList($playListId) {
        $url = "http://gdata.youtube.com/feeds/api/playlists/$playListId?v=2&fields={$this->videoFilter}";
        
        $data = $this->_getData($url);

        $videos = array();
        if($data===FALSE) {
            return FALSE;
        } else {
            $videos = $this->_parseVideos($data);
        }

        return $videos;
    }

    private function _parseVideos($data) {
        $entries = isset($data['feed']['entry'])?$data['feed']['entry']:array();
        
        $videos = array();
        foreach ($entries as $entry) {
            $video = array();

            $video['title'] = isset($entry['title']['$t'])?$entry['title']['$t']:'';
            $video['duration'] = isset($entry['media$group']['yt$duration']['seconds'])?$entry['media$group']['yt$duration']['seconds']:'';
            $video['note'] = isset($entry['media$group']['media$description']['$t'])?$entry['media$group']['media$description']['$t']:'';
            $video['thumb'] = isset($entry['media$group']['media$thumbnail'][0]['url'])?$entry['media$group']['media$thumbnail'][0]['url']:'';
            $video['year'] = isset($entry['published']['$t'])?substr($entry['published']['$t'],0,4):'';
            $video['uri'] = isset($entry['media$group']['media$player']['url'])?$entry['media$group']['media$player']['url']:'';
            $video['vid'] = isset($entry['media$group']['yt$videoid']['$t'])?$entry['media$group']['yt$videoid']['$t']:'';
            $video['vid_gp3'] = isset($entry['media$group']['media$player']['url'])?$entry['media$group']['media$player']['url']:'';
            $videos[] = $video;
        }

        return $videos;
    }

    private function _getData($url) {
        $url = $url . "&alt=json";

        $ch= curl_init($url);

        $headers = array(
            'Content-type: application/x-www-form-urlencoded;charset=UTF-8'
        );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $rtn = curl_exec($ch);

        $details = curl_getinfo($ch);
        curl_close($ch);

        if($details['http_code']=='200')  {
            $json = json_decode($rtn, TRUE);
            return $json;
        } else {
            return FALSE;
        }
    }
}

/*$youTube = new YouTube("apdhanushka");
//$playLists = $youTube->getUploadedVideos();
$playLists = $youTube->getPlayLists();
if ($playLists===FALSE) {
    echo "Error";
} else {
    print_r($playLists);
}*/
?>
