<?php



class Flickr
{
  var $user_id;
  
  
  public function function_call($func,$vals) {
    $params = "";
    foreach($vals as $key => $val) {
      $params .= "&$key=".urlencode($val);
    }
    $url = "http://api.flickr.com/services/rest/?format=json&nojsoncallback=1&method=".$func."&api_key=".$_ENV['flickr_key'].$params;
    //echo $url;
    $json = file_get_contents($url);
    return json_decode($json);
  }

  public static function getPhotoUrl($farm,$server,$id,$secret,$type='') {
    return "http://farm{$farm}.static.flickr.com/{$server}/{$id}_{$secret}{$type}.jpg";
  }

  public function Flickr($username="") {

	if ($username!="") {
   return $this->user_id = $this->getUserId($username);
    }
  }

  private function getUserId($username) {
    $result = $this->function_call('flickr.people.findByUsername',array('username' => $username));

    return isset($result->user->id)?$result->user->id:NULL;
  }

//  public function getPhotoSets() {
//    $results = $this->function_call('flickr.photosets.getList',array('user_id' => $this->user_id));
//    $sets = array();
//	if(isset ($results->photosets->photoset) && sizeof($results->photosets->photoset) >0){
//    foreach($results->photosets->photoset as $photoset) {
//
//      $sets[] = array('id' => $photoset->id, 'photos' => $photoset->photos, 'title' => $photoset->title->_content, 'description' => $photoset->description->_content,
//                    'image' => self::getPhotoUrl($photoset->farm,$photoset->server,$photoset->primary,$photoset->secret,'_s'));
//			
//    }
//    $results = $this->function_call('flickr.people.getInfo',array('user_id' => $this->user_id));
//    $all = $results->person->photos->count->_content;
//	}
//	else{
//	$sets[]="";
//        $results = $this->function_call('flickr.people.getInfo',array('user_id' => $this->user_id));
//        $all = isset($results->person->photos->count->_content)?$results->person->photos->count->_content:"";
//        }
//    return array('sets' => $sets,'all' => $all);
//  }
  
  public function getPhotoSets() {
        require "../../../../../facebook-php-sdk-6c82b3f/src/facebook.php";
        $facebook = new Facebook(array(
        'appId'  => '116141471859980',
        'secret' => '2dd464a8579f953663b01d2c8cb46fc4',
        ));
        
        $accessTocken = $facebook->getAccessToken();
        $userId = $facebook->getUser();
        
        $url = "https://graph.facebook.com/$userId/albums?access_token=$accessTocken";
        $responce=file_get_contents($url) ; 
        return $accessTocken;
        //return $arrCount = json_decode($responce);
  }
  
  public function getPhotos($photoset_id) {
    $results = $this->function_call('flickr.photosets.getPhotos',array( 'photoset_id' => $photoset_id,'media' => 'photos'));
    

    $photos = array();
    if (!empty($results->photoset->photo)) {
        foreach($results->photoset->photo as $photo) {

          $photos[] = array('title' => $photo->title,'image' => self::getPhotoUrl($photo->farm,$photo->server,$photo->id,$photo->secret),'thumb' => self::getPhotoUrl($photo->farm,$photo->server,$photo->id,$photo->secret,'_s'),'pid'=>$photo->id);
        }
    }
    
    return $photos;
  }

  public function getPhotos2() {

    $results = $this->function_call('flickr.people.getPublicPhotos',array('user_id' => $this->user_id));
    $photos = array();
    if (!empty($results->photos->photo)) {
        foreach($results->photos->photo as $photo) {
            $photos[] = array('title' => $photo->title,'image' => self::getPhotoUrl($photo->farm,$photo->server,$photo->id,$photo->secret),'thumb' => self::getPhotoUrl($photo->farm,$photo->server,$photo->id,$photo->secret,'_s'),'pid'=>$photo->id);
        }
    }
    return $photos;
  }
  
  public function getFaceBookPhotos($setId){
     require_once "../../../facebook-php-sdk-6c82b3f/src/facebook.php";
     require_once "../config/app_key_values.php";
     

    if($_SERVER["SERVER_NAME"]==app_key_values::$LIVE_SERVER_DOMAIN){
        $appId = app_key_values::$APP_ID_LIVE ;
        $secretKey = app_key_values::$SECRET_KEY_LIVE;
    }elseif($_SERVER["SERVER_NAME"]==app_key_values::$TEST_SERVER_DOMAIN){
        $appId = app_key_values::$APP_ID_TEST ;
        $secretKey = app_key_values::$SECRET_KEY_TEST;
    }else{
        $appId = app_key_values::$APP_ID_LOCALHOST ;
        $secretKey = app_key_values::$SECRET_KEY_LOCALHOST;
    }
         $facebook = new Facebook(array(
            'appId'  => $appId,
            'secret' => $secretKey,
        )); 
        $accessTocken =  $facebook->getAccessToken();

        $url = "https://graph.facebook.com/$setId/photos?access_token=$accessTocken";
        return $responce=file_get_contents($url) ;     
  }


  
  
  
  //create flickr login
    public function create_Login(){
	//Auth request 
	//http://flickr.com/services/auth/?api_key=[api_key]&perms=[perms]&api_sig=[api_sig]
	
	//create api_sig
	//param should be in alphebetical order
	//self::$shared_secret
	$api_sig=md5($_ENV['shared_secret']."api_key".$_ENV['flickr_key']."permswrite");
	return $url='http://flickr.com/services/auth/?api_key='.$_ENV['flickr_key'].'&perms=write&api_sig='.$api_sig.'';
	$json = file_get_contents($url);
    $frob = json_decode($json);
	
  
  }
  
  //Create an auth handler
  public function create_AuthHandler(){
  
  }
  
  //Convert frob to a token
  public function getFrob_Token($frob){

$api_sig=md5($_ENV['shared_secret']."api_key".$_ENV['flickr_key']."formatjson"."frob".$frob."methodflickr.auth.getTokennojsoncallback1");

   $results = $this->auth_function_call('flickr.auth.getToken',array('frob' => $frob,'api_sig' => $api_sig));

foreach($results->auth->token as $token) {
    $auth_token= $token;
    }

return  $auth_token;

  
  }
  
  //Make an authenticated call
  public function auth_call(){
  
  }
  
  //upload url
  public function upload_url($frob,$auth_token){
 
 //correct
  $api_sig=md5($_ENV['shared_secret']."api_key".$_ENV['flickr_key']."auth_token".$auth_token."submitFlickr");

   $url[0] = array('api_key' => $_ENV['flickr_key'],'auth_token' => $auth_token,'api_sig' => $api_sig);
   

   return $api_sig;
 
// return $url;
  }
  
  
  //authenticated call function
  public function auth_function_call($func,$vals) {
    $params = "";
    foreach($vals as $key => $val) {
      $params .= "&$key=".urlencode($val);
    }

    $url = "http://api.flickr.com/services/rest/?method=".$func."&api_key=".$_ENV['flickr_key'].$params."&format=json&nojsoncallback=1";

    $json = file_get_contents($url);

    return json_decode($json);
  }
  
}
?>