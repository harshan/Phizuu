<?php

class YouTube
{
  public static function videos($username,$url=null,$year=null) {
    $t = '$t';
    $group='media$group';
    $content='media$content';
    $thumb = 'media$thumbnail';

    if ($url==null) {
      $url = "http://stage.gdata.youtube.com/feeds/api/users/{$username}/uploads?alt=json";
    }


    $json = @file_get_contents($url);


    if($json===FALSE){
        return false;
    }
    $result = json_decode($json);
    $videos = array();

    if (isset($result->feed->entry) && count($result->feed->entry)>0) {
        foreach($result->feed->entry as $video) {

          $tt = $video->$group->$thumb;
          $duration = 0;
          if (isset($video->$group->$content)) {
            $tx = $video->$group->$content;
            $duration = $tx[0]->duration;
          }
             $vid_arr=explode("?",$tx[0]->url);
             $vid_arr2=explode("/",$vid_arr[0]);

          $videos[] = array('year' => ($year==null?substr($video->published->$t,0,4):$year),'title' => addslashes($video->title->$t),'note' => addslashes(urlencode($video->content->$t)),'uri' => $video->link[0]->href,'thumb' => $tt[0]->url,'duration' => $duration,'vid' => addslashes($vid_arr2[(sizeof($vid_arr2)-1)]),'vid_gp3' => addslashes($tx[1]->url));
        }
    }
    
    return $videos;
  }
  
  public static function playlists($username) 
  {
    $t = '$t';
    $id = 'yt$playlistId';
    $count = 'gd$feedLink';
    $total = 'openSearch$totalResults';

    $url = "http://stage.gdata.youtube.com/feeds/api/users/{$username}/playlists?alt=json";
    $json = @file_get_contents($url);
    $result = json_decode($json);

    if ($result->feed->$total->$t==0)
      return array();
    $playlists = array();
    foreach($result->feed->entry as $playlist) {
      $c = $playlist->$count;

      $playlists[] = array('title' => $playlist->title->$t,'description' => $playlist->content->$t,'id' => $playlist->$id->$t, 'count' => $c[0]->countHint, 'year' => substr($playlist->published->$t,0,4));
    }

    return $playlists;
  }

  public static function playlistsWizard($username)
  {
    $t = '$t';
    $id = 'yt$playlistId';
    $count = 'gd$feedLink';
    $total = 'openSearch$totalResults';

    $url = "http://stage.gdata.youtube.com/feeds/api/users/{$username}/playlists?alt=json";
    $json = @file_get_contents($url);
    $result = json_decode($json);

    if (!isset($result->feed)) {
        $playList->error = true;
      return $playList;
    }
    
    $playlists = array();
    if (isset($result->feed->entry)){
        foreach($result->feed->entry as $playlist) {
            $c = isset($playlist->$count[0]->countHint)?$playlist->$count[0]->countHint:'';
            $playlists[] = array('title' => $playlist->title->$t,'description' => $playlist->content->$t,'id' => $playlist->$id->$t, 'count' => $c , 'year' => substr($playlist->published->$t,0,4));
        }
    }

    return $playlists;
  }


  public static function playlistVideos($id,$year) 
  {
    $url = "http://stage.gdata.youtube.com/feeds/api/playlists/{$id}?alt=json";
    //$url = "http://gdata.youtube.com/feeds/api/users/sykeandsugarstarr/favorites?alt=json";
    if ($id == 'Favorites') {
        $url = "http://gdata.youtube.com/feeds/api/users/{$_POST['username']}/favorites?alt=json";
    }
    
    return YouTube::videos(null,$url,$year);
  }
  
}

?>