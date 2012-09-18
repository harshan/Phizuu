<?php 
class jsonClass {

    public function streamMusic($user_id) {
        $music = new MusicModel();
        $all_music=$music -> listAllMusicJson($user_id);


        $this->playlistJson = array();

        foreach ($all_music as $music) {
            $x = array(
                    'flags' => 12,
                    'title' => $music->title,
                    'duration' => (int)$music->duration,
                    'stream_uri' => $music->stream_uri,
                    'year' => (int)$music->year,
                    'note' => $music->note,
                    'image' =>array("uri"=>$music->image_uri)
            );
            if ($music->itunes_uri!=null)
                $x['itunes_uri'] = $music->itunes_uri;
            if ($music->android_url!=null)
                $x['android_uri'] = $music->android_url;
            if ($music->album!=null)
                $x['album'] = $music->album;
            $this->playlistJson[] = $x;
        }

        $banner = 'http://farm3.static.flickr.com/2166/3542257079_e9231712a1.jpg';
        $json = array('flags' => 11,'image' => array('uri' => $banner),'tracks' => $this->playlistJson);
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
                    'title' => $track->title,
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

        foreach ($all_pic as $image) {
            $this->playlistJson[] = array(
                    'uri' => $image->uri,
                    'thumb_uri' =>$image-> thumb_uri,
                    'caption' =>$image-> name
            );
        }

        $json = array('images' => $this->playlistJson);
        return $json_stream = json_encode($json);
    }


    public function streamTours($user_id) {
        $tours = new ToursModel();
        $all_tours=$tours -> listAllToursJson($user_id);

        $this->toursJson = array();

        ////tour.ticket_url, tour.thumb_url, tour.flyer_url

        $tourModel = new ToursModel();
        foreach ($all_tours as $tour) {

            if($tour->flyer_url==''){
                $image = $tourModel->getDefaultImage($_SESSION['user_id']);
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
                'name' => $tour->name,
                'date' => $tour->date,
                'location' => $tour->location,
                'description' => $tour->description,
                'ticket_uri' => $tour->ticket_url,
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

        $this->newsJson = array();

        foreach ($all_news as $news) {
            $this->newsJson[] = array(
                    'title' => $news->title,
                    'date' => $news->date,
                    'description' => $news->description
            );
        }
        

        $json = array('news' => $this->newsJson);
        return $json_stream = json_encode($json);
    }
    
//    public function addtomailingList($data)
//    {
//        
//    }

    public function addRegisteration($arrReg) {
        $uuid = $arrReg->uuid;
        $tourId = $arrReg->tour_id;
        
        $sql = "SELECT * FROM tour_registrations WHERE tour_id=$tourId AND uuid='$uuid'";
        $dao = new Dao();
        $res = $dao->query($sql);

        if (mysql_num_rows($res)==0) {
            $sql = "INSERT INTO tour_registrations (tour_id,uuid) VALUES ($tourId ,'$uuid')";
            $dao = new Dao();
            $res = $dao->query($sql);

            $sql = "UPDATE tour SET registerations=(registerations+1) WHERE id=$tourId";
            $dao = new Dao();
            $res = $dao->query($sql);
        } 

        $sql = "SELECT registerations FROM tour WHERE id=$tourId";
        $dao = new Dao();
        $res = $dao->query($sql);
        $arr = $dao->getArray($res);
        if (count($arr)>0) {
            return "{\"count\":\"{$arr[0]['registerations']}\"}";
        } else {
            return "";
        }
    }
    
     public function addtoMailingList($data)
    {
        $name = $data->name;
        $app_id = $data->app_id;
        $email = $data->email;
        $birth_day = $data->birth_day;
        $sex = $data->sex;
        $country = $data->country;
        $postal_code = $data->postal_code;
        $radio_station = $data->radio_station;
        $feedback = $data->feedback;
        $mobile_number = $data->mobile_number;

        $sql = "SELECT * FROM mailing_list WHERE app_id=$app_id AND email='$email'";

        $dao = new Dao();
        $res = $dao->query($sql);
        $result = mysql_fetch_array($res, MYSQL_ASSOC);
        if($result == NULL)
        {
            $sql = "INSERT INTO mailing_list (`name`,`app_id`,`email`,`birth_day`,`sex`,`country`,`postal_code`,`radio_station`,`feedback`,`mobile_no`) VALUES ('$name','$app_id','$email','$birth_day','$sex','$country','$postal_code','$radio_station','$feedback','$mobile_number')";
                    $filename = "mail.txt";
        $fh = fopen($filename, 'w');
        fwrite($fh, $sql);
        fclose($fh);
            $res = $dao->query($sql);
            $retu = array('status'=>"ok");
            return json_encode($retu);
        }
        else
        {
            $retu = array('status'=>"already");
            return json_encode($retu);
        }
    }

}
?>