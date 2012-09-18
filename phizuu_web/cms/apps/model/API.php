<?php

function errorHandlerIgnoreError ($errno, $errstr, $errfile, $errline) {
    
}

class API {
    
    public function writeStaticModuleJSON() {
        $modules['Images'] = 'images/';
        $modules['Music'] = 'music/';
        $modules['Tours'] = 'events/';
        $modules['Flyers'] = 'flyers/';
        $modules['Links'] = 'links/';
        $modules['Videos'] = 'videos/';
        $modules['News'] = 'news/';
        $modules['BuyStuff'] = 'buy_stuff/';
        $modules['Discography'] = 'discography/';
        $modules['Biography'] = 'biography/';
	$modules['About'] = 'about/';

        $userId = $_SESSION['user_id'];
        $appId = $this->getAppId();

        if($appId===FALSE) {
            return FALSE;
        }

        $appId = $this->getAppId();

        $dirPath = STATIC_API_PATH . $appId . '/';
        if(is_dir($dirPath)) {
            $this->_delTree(realpath(STATIC_API_PATH . $appId)); //Clean the contents
        }

        mkdir($dirPath);

        foreach ($modules as $func => $path) {
            $json = '';
            switch ($func) {
                case 'Images':
                    $json = $this->streamImage($appId);
                    break;
                case 'Music':
                    $json = $this->streamMusic($appId);
                    break;
                case 'Tours':
                    $json = $this->streamTours($appId);
                    break;
                case 'Flyers':
                    $json = $this->streamFlyers($appId);
                    break;
                case 'Videos':
                    $json = $this->streamVideo($appId, 'iphone');
                    break;
                case 'Links':
                    $json = $this->streamLinks();
                    break;
                case 'News':
                    $json = $this->streamNews($appId);
                    break;
                case 'BuyStuff':
                    $json = $this->streamBuyStuff();
                    break;
                case 'Discography':
                    $json = $this->streamDiscography($userId);
                    break;
                case 'Biography':
                    $json = $this->streamBiography($appId);
                    break;
		case 'About':
                    $json = $this->streamAbout($appId);
                    break;
            }

            file_put_contents($dirPath . 'index.html', $this->streamMain($appId));

            $fullPath = $dirPath . $path; 

            if(!is_dir($fullPath))
                mkdir($fullPath);

            if(!is_array($json)) {
                file_put_contents($fullPath . 'index.html', $json);
            } else {
                foreach($json as $content) {
                    $contentPath = $fullPath . $content->path;
                    if(!is_dir($contentPath))
                        mkdir($contentPath);

                    file_put_contents($contentPath . 'index.html', $content->json);
                }
            }
        }
    }

    private function getAppId() {
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
        $res = $dao->query($sql);
        $userArr = $dao->getArray($res);
        if(count ($userArr)>0) {
            $userArr = $userArr[0];
            return $userArr['app_id'];
        } else {
            return FALSE;
        }
    }

    public function getAppName() {
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
        $res = $dao->query($sql);
        $userArr = $dao->getArray($res);
        if(count ($userArr)>0) {
            $userArr = $userArr[0];
            return $userArr['app_name'];
        } else {
            return FALSE;
        }
    }

    private function streamLinks() {
        $linksObj = new Links();
        $links = $linksObj->listLinks($_SESSION['user_id']);
        $linkArr = array();

        

        foreach ($links as $link) {
            $linkArr[] = array('id'=>$link['id'], 'title'=>$link['title'], 'uri'=>$link['uri']);
        }

        $jsonArr = array('links'=>$linkArr);
        return json_encode($jsonArr);
    }

    private function streamBuyStuff() {
        $buyStuffObj = new BuyStuff();
        $stuff = $buyStuffObj->listStuff($_SESSION['user_id']);
        $buyStuffArr = array();



        foreach ($stuff as $link) {
            $buyStuffArr[] = array('title'=>$link['title'], 'uri'=>$link['uri']);
        }

        $jsonArr = array('buy_stuff'=>$buyStuffArr);
        return json_encode($jsonArr);
    }

    public function streamMusic($user_id) {
        $musicModel = new MusicModel();
        $all_music=$musicModel -> listAllMusicJson($user_id);


        $this->playlistJson = array();

        foreach ($all_music as $music) {
            $x = array(
                    'flags' => 12,
                    'id' => $music->id,
                    'title' => $music->title==''?"":$music->title,
                    'duration' => $music->duration==''?0:(int)$music->duration,
                    'stream_uri' => $music->stream_uri==''?"":$music->stream_uri,
                    'soundcloud_uri' => $music->soundcloud_uri==''?"":$music->soundcloud_uri,
                    'year' => $music->year==''?0:(int)$music->year,
                    'note' => $music->note==''?"":$music->note,
                    'image' =>array("uri"=>$music->image_uri==''?"":$music->image_uri),
                    'category_id' =>$music->category_id==''?"0":$music->category_id,
                    'android_uri' => $music->android_url==''?"":$music->android_url,
            );
            
            if ($music->itunes_affiliate_url !='') {
                $x['itunes_uri'] = $music->itunes_affiliate_url ;
            } else if ($music->itunes_uri !=''){
                $x['itunes_uri'] = $music->itunes_uri;
            }
            
            if ($music->album!=null)
                $x['album'] = $music->album;
            
            $this->playlistJson[] = $x;
        }

        $banner = $musicModel->getCoverImage($_SESSION['user_id']); //'http://farm3.static.flickr.com/2166/3542257079_e9231712a1.jpg';
        if($banner == NULL) {
            $banner = "";
        }

        $soundCloud = new stdClass();
        $soundCloud->consumer_key = SOUNDCOULD_CONSUMER_KEY;
        $soundCloud->consumer_secret = SOUNDCOULD_CONSUMER_SECRET;
        $soundCloud->access_token = SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN;
        $soundCloud->access_token_secret = SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN_SECRET;
        
        $musicObj = new SoundCloudMusic();

        $categoriesArr = $musicObj->listCategories($_SESSION['user_id']);
        array_push($categoriesArr, $musicObj->getDefaultCategory($_SESSION['user_id']));

        $categories = array();
        foreach ($categoriesArr as $category) {
            $categories[] = array('id'=>$category['id'],'title'=>$category['name']);
        }

        $json = array('flags' => 11,'image' => array('uri' => $banner),'tracks' => $this->playlistJson, 'soundcloud_config' => $soundCloud, 'track_categories'=>$categories);

        return $json_stream = json_encode($json);
    }

    public function streamVideo($user_id,$phone) {
        $video = new VideoModel();
        $all_video=$video -> listAllVideosJson($user_id);
        $this->playlistJson = array();

        foreach ($all_video as $track) {

            if($phone=='blackberry') {
                $stream_uri=$track->stream_uri_3gp;
            }
            else {
                $stream_uri=$track->stream_uri;
            }

            $this->playlistJson[] = array(
                    'flags' => 12,
                    'id' => $track->id,
                    'title' => $track->title==''?"":$track->title,
                    'duration' => (int)$track->duration,
                    'stream_uri' => $stream_uri,
                    'year' => (int)$track->year,
                    'note' => $track->note,
                    'image' => array('thumb_uri' => $track->thum_uri)
            );
        }
        $json = array('flags' => 11,'tracks' => $this->playlistJson);
        return $json_stream = json_encode($json);
    }

    public function streamImage($user_id) {
        $picture = new PicModel();
        $all_pic=$picture -> listAllPicsJson($user_id);
        $this->playlistJson = array();
        print_r($all_pic);
        foreach ($all_pic as $image) {
            $this->playlistJson[] = array(
                    'id' => $image->img_id,
                    'uri' => $image->uri,
                    'thumb_uri' =>$image-> thumb_uri,
                    'caption' =>$image-> name==''?"":$image-> name
            );
        }

        $json = array('images' => $this->playlistJson);

        $imageJSON = json_encode($json);

        $writeArr = array();
        $jsonObj = new stdClass();
        $jsonObj->json = $imageJSON;
        $jsonObj->path = '';
        
        $writeArr[] =$jsonObj;

        $albumObj = new Album($_SESSION['user_id']);

        if($albumObj->getAlbumStatus()==0) {
            $jsonBase = new stdClass();
            $jsonBase->photos_mode = 'gallery';

            $jsonObj = new stdClass();
            $jsonObj->json = json_encode($jsonBase);
            $jsonObj->path = 'config/';
            $writeArr[] =$jsonObj;
            return $writeArr;
        } else {
            $jsonBase = new stdClass();
            $jsonBase->photos_mode = 'album';

            $jsonObj = new stdClass();
            $jsonObj->json = json_encode($jsonBase);
            $jsonObj->path = 'config/';
            $writeArr[] =$jsonObj;
        }

        $albumList = $albumObj->listAlbums();

        $albumArr = array();
        foreach ($albumList as $album) {
            $albumAPIObj = new stdClass();

            $albumAPIObj->id = $album['id'];
            $albumAPIObj->name = ($album['album_name']=='')?"":$album['album_name'];
            $albumAPIObj->date = ($album['album_date']=='' || $album['album_date']=='0000-00-00')?"":$album['album_date'];
            $albumAPIObj->image_count = ($album['image_count']=='')?0:$album['image_count'];

            $imageThumb = new stdClass();
            $imageThumb->thumb_uri = ($album['thumb_uri']=='')?"":$album['thumb_uri'];
            $imageThumb->uri = ($album['image_uri']=='')?"":$album['image_uri'];

            $albumAPIObj->cover_image = $imageThumb;

            $albumArr[] = $albumAPIObj;
        }

        $jsonBase = new stdClass();
        $jsonBase->albums = $albumArr;

        $jsonObj = new stdClass();
        $jsonObj->json = json_encode($jsonBase);
        $jsonObj->path = 'albums/';
        $writeArr[] =$jsonObj;

        foreach ($albumList as $album) {
            $photos = $albumObj->listPicturesOfAlbum($album['id']);

            $photosArr = array();
            foreach ($photos as $photo) {
                $albumAPIObj = new stdClass();

                $albumAPIObj->id = $photo->id;
                $albumAPIObj->uri = $photo->uri;
                $albumAPIObj->thumb_uri = $photo->thumb_uri;
                $albumAPIObj->caption = $photo->name==''?"":$photo->name;

                $photosArr[] = $albumAPIObj;
            }
            $jsonBase = new stdClass();
            $jsonBase->images = $photosArr;

            $jsonObj = new stdClass();
            $jsonObj->json = json_encode($jsonBase);
            $jsonObj->path = 'albums/'.$album['id'].'/';
            $writeArr[] =$jsonObj;
        }

        return $writeArr;
    }

    public function streamTours($user_id) {
        $tours = new ToursModel();
        $all_tours=$tours -> listAllToursJson($user_id);

        $this->toursJson = array();

        $sql = "SELECT id FROM user WHERE app_id='$user_id'";
        $dao = new Dao();
        $res = $dao->query($sql);
        $arr = $dao->getArray($res);
        $userId = $arr[0]['id'];
        ////tour.ticket_url, tour.thumb_url, tour.flyer_url

        $tourModel = new ToursModel();

        
        foreach ($all_tours as $tour) {

            if($tour->flyer_url==''){
                $image = $tourModel->getDefaultImage($userId);

                if(!isset ($image[0])){
                    $image[0] = "";
                }

                if(!isset ($image[1])){
                    $image[1] = "";
                }

                $tour->flyer_url = $image[0];
                $tour->thumb_url = $image[1];
            }

            $this->toursJson[] = array(
                'id' => $tour->id,
                'name' => $tour->name != ''?$tour->name:"",
                'date' => ($tour->date == '0000-00-00' || $tour->date == '')?"":$tour->date,
                'location' => $tour->location==''?"":$tour->location,
                'description' => $tour->description==''?"":$tour->description,
                'ticket_uri' => $tour->ticket_url==''?"":$tour->ticket_url,
                'flyer_image' => array("uri"=>$tour->flyer_url, "thumb_uri"=>$tour->thumb_url),
                'registrations' => $tour->registerations
            );
        }

        $json = array('events' => $this->toursJson);
        return $json_stream = json_encode($json);
    }

    public function streamNews($user_id) {
        $news = new NewsModel();
        $all_news =$news -> listAllNewsJson($user_id);

        $dao = new Dao();
        $sql = "SELECT * FROM user WHERE app_id = {$user_id}";
        $res = $dao->query($sql);
        $userArr = $dao->getArray($res);

        $appName = htmlspecialchars($userArr[0]['app_name']);

        $rssString = '<?xml version="1.0"?><rss version="2.0"><channel>';
        $rssString .= "<title>{$appName}</title>";
        $rssString .= "<link>http://phizuu.com</link>";
        $rssString .= "<description>Generated by phizuu CMS</description>";
        $rssString .= "<language>en-us</language>";

        foreach ($all_news as $news) {
            $date = date("D, d M Y 00:00:00",strtotime($news->date)) . " GMT";
            $rssString .= "<item>";
            $rssString .= "<id>". htmlspecialchars($news->id) . "</id>";
            $rssString .= "<title>". htmlspecialchars($news->title) . "</title>";
            $rssString .= "<link></link>";
            $rssString .= "<description>". htmlspecialchars($news->description) . "</description>";
            $rssString .= "<pubDate>$date</pubDate>";
            $rssString .= "</item>";
        }

        $rssString .= '</channel></rss>';

        
        return $rssString;
    }

    public function streamDiscography($user_id) {
        $writeArr = array();
        $jsonObj = new stdClass();

        $discography = new Discography($user_id);

        $albums = $discography->listDiscographies();

        $rtnObj = new stdClass();

        $discographyArr = array();
        foreach ($albums as $album) {
            $albumObj = new stdClass();
            $albumObj->id = $album['id'];
            $albumObj->title = $album['title'];
            $albumObj->information = $album['info'];
            $albumObj->details = $album['details'];

            $image = new stdClass();
            $image->uri = $album['image_uri'];
            $image->thumb_uri = $album['thumb_uri'];
            $albumObj->cover_image = $image;
            $albumObj->flyer_image = $image;

            $buyURLs = array();
            $listURLs = $discography->getBuyLinks($album['id']);
            foreach ($listURLs as $url) {
                $urlObj = new stdClass();
                $urlObj->title = $url['title'];
                $urlObj->uri = $url['link'];
                $buyURLs[] = $urlObj;

            }

            $albumObj->buy_urls = $buyURLs;
            $albumObj->like_count = $album['like_count'];
            $discographyArr[] = $albumObj;
        }

        $rtnObj->discography_albums = $discographyArr;
        
        return json_encode($rtnObj);
    }

    public function streamFlyers($user_id) {

        $json = array('flyers' => array());
        return $json_stream = json_encode($json);
    }

    public function addDiscographyLike($uuid, $discographyId) {
        $sql = "SELECT * FROM discography_registrations WHERE discography_id=$discographyId AND uuid='$uuid'";
        $dao = new Dao();
        $res = $dao->query($sql);

        if (mysql_num_rows($res)==0) {
            $sql = "INSERT INTO discography_registrations (discography_id,uuid) VALUES ($discographyId ,'$uuid')";
            $dao = new Dao();
            $res = $dao->query($sql);

            $sql = "UPDATE discography SET like_count=(like_count+1) WHERE id=$discographyId";
            $dao = new Dao();
            $res = $dao->query($sql);
        }

        $sql = "SELECT like_count FROM discography WHERE id=$discographyId";
        $dao = new Dao();
        $res = $dao->query($sql);
        $arr = $dao->getArray($res);
        if (count($arr)>0) {
            return "{\"count\":\"{$arr[0]['like_count']}\"}";
        } else {
            return "";
        }
    }

    public function streamBiography ($appId) {
        $writeArr = array();

        $sql = "SELECT * FROM information_modules WHERE `app_id`= '$appId'";
        $dao = new Dao();
        $arr = $dao->toArray($sql);
        if (count($arr)>0) {
            $jsonObj = new stdClass();

            $dataObj = new stdClass();
            $dataObj->enabled = true;
            $dataObj->html = $arr[0]['biography_text'];

            $jsonObj->json = json_encode($dataObj);
            $jsonObj->path = '';
            $writeArr[] = $jsonObj;
        }

        return $writeArr;
    }
    
    public function streamAbout ($appId) {
        $writeArr = array();

        $sql = "SELECT * FROM information_modules WHERE `app_id`= '$appId'";
        $dao = new Dao();
        $arr = $dao->toArray($sql);
        if (count($arr)>0) {
            $jsonObj = new stdClass();

            $dataObj = new stdClass();
            $dataObj->enabled = true;
            $dataObj->html = $arr[0]['about_text'];

            $jsonObj->json = json_encode($dataObj);
            $jsonObj->path = '';
            $writeArr[] = $jsonObj;
        }

        return $writeArr;
    }

    public  function streamMain($app_id) {
        $setting_type=$_ENV['setting_twiter'];


        $settings= new Settings();
        $settingModel = new SettingsModel();

        $rssId= $_ENV['setting_rssfeed'];
        $rss_list = $settingModel->getRssFeed($rssId, $app_id);

        if(count($rss_list)>0 && $rss_list[count($rss_list)-1]->value !='') {
            $newsURL= $rss_list[count($rss_list)-1]->value;
        } else {
            $newsURL= 'http:\/\/connect.phizuu.com\/static-api\/'.$app_id.'\/news\/';
        }

        $settings_list = $settings->listSettingsApi($app_id, $setting_type);

        if(sizeof($settings_list) >0) {
            foreach($settings_list as $lst_settings) {
                if($lst_settings -> preferred == '1') {
                    $twitter_pref=$lst_settings -> value;
                }
                else {
                    $twitter=$lst_settings -> value;
                }
            }

            if(empty($twitter_pref)) {
                $twitter_pref=$twitter;
            }
        }
        else {

            $twitter_pref='';
        }

        if ($twitter_pref!=''){
            $twitter_pref="http://twitter.com/statuses/user_timeline/$twitter_pref.rss";
        }
        $file_content='{
                                            "image_set_uri" : "images\/",
                                            "audio_playlist_uri" : "music\/",
                                            "video_playlist_uri" : "videos\/",
                                            "news_uri" : "'.$newsURL.'",
                                            "twitter_uri" : "'. str_replace('/', '\/', addslashes($twitter_pref)) .'",
                                            "events_uri" : "events\/",
                                            "flyer_image_set_uri" : "flyers\/",
                                            "links_uri" : "links\/",
                                            "fan_video_playlist_uri" : "fan_videos\/"
                                    }';


        return $file_content;
    }

    function _delTree($dir) {
        $externalGenerated = array('world_map');

        $oldErrorHandler = set_error_handler("errorHandlerIgnoreError", E_ALL);

        $files = scandir($dir);
        array_shift($files);    // remove '.' from array
        array_shift($files);    // remove '..' from array
        foreach ($files as $file) {
            $isExternal = false;
            foreach($externalGenerated as $ext) {
                if ($ext==$file) {
                    $isExternal = true;
                }
            }
            $file = $dir . '/' . $file;
            if (is_dir($file)) {
                if (!$isExternal) {
                    $this->_delTree($file);
                    @rmdir($file);
                }
            } else {
                unlink($file);
            }
        }
        //@rmdir(realpath($dir));
        set_error_handler($oldErrorHandler, E_ALL);
    }

}
?>
