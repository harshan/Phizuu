<?php
define("AW_SETTINGS_ICON_IMAGE", 1);
define("AW_SETTINGS_FB_IMAGE", 2);
define("AW_SETTINGS_ITUNES_IMAGE", 3);


class AppWizard {
    private $userId;
    private $dao;

    public function AppWizard ($userId) {
        $this->userId = $userId;
        $this->dao = new Dao();
    }

    public function getPackageInfo() {
        $sql = "SELECT home_screen_images, package_id FROM user, package WHERE user.id = {$this->userId} AND user.package_id = package.id";
        $res = $this->dao->query($sql);
        $array = $this->dao->getArray($res);

        return $array[0];
    }

    public function getListOfModules($onlyNames = false, $autoCompleteModules=false) {

        if ($autoCompleteModules) {
            $append = " WHERE fill_link = ''";
        } else {
            $append = '';
        }

        $sql = "SELECT * FROM aw_modules $append ";
        $res = $this->dao->query($sql);
        $array = $this->dao->getArray($res);

        if ($onlyNames) {
            $cnt = 1;
            $modulesArr = array();
            foreach ($array as $elem) {
                $modulesArr[$cnt] = $elem['module_name'];
                $cnt++;
            }

            return $modulesArr;
        } else {
            return $array;
        }
    }
    //HomeImage.jpg 
    public function writeXML($appId, $title, $moduleArr, $mobclixId, $pushEnabled, $addsEnabled, $faceBookImagePath, $path) {
        $files = $this->_getFileArray($path);

        array_unshift($files, 'none');

        $homeImageCount = 0;
        for($i=1; $i<=5; $i++) {
            if (array_search("HomeImage$i.jpg", $files)) {
                $homeImageCount = $i;
            }
        }

        $moduleArr = $this->_arrangeModuleList($moduleArr);

        if ($pushEnabled)
            $pushEnabledText = 'true';
        else
            $pushEnabledText = 'false';

        if ($addsEnabled)
            $addsEnabledText = 'true';
        else
            $addsEnabledText = 'false';

        $newLine = "\n";
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $newLine;
        $xml .= '<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">' . $newLine;
        $xml .= '<plist version="1.0"> <dict>' . $newLine;

        $xml .= '<key>App identifier</key>' . $newLine;
        $xml .= "<string>$appId</string>" . $newLine;

        $xml .= '<key>Title</key>' . $newLine;
        $xml .= "<string>$title</string>" . $newLine;

        $xml .= '<key>Home image count</key>' . $newLine;
        $xml .= "<integer>$homeImageCount</integer>" . $newLine;
        
        $xml .= '<key>Modules</key>' . $newLine;
        $xml .= '<array>' . $newLine;
        foreach ($moduleArr as $module) {
            $xml .= "<string>$module</string>" . $newLine;
        }
        $xml .= '</array>' . $newLine;

        
        $xml .= '<key>Mobclix application identifier</key>' . $newLine;
        $xml .= "<string>$mobclixId</string>" . $newLine;

        $xml .= "<key>Version</key>" . $newLine;
        $xml .= "<dict>" . $newLine;
        $xml .= "<key>Major</key>" . $newLine;
        $xml .= "<string>3.0</string>" . $newLine;
        $xml .= "<key>Minor</key>" . $newLine;
        $xml .= "<string>0</string>" . $newLine;
        $xml .= "</dict>" . $newLine;     

        $xml .= '<key>PhizuuConnect URL</key>' . $newLine;
        $xml .= "<string>http://connect.phizuu.com/client</string>" . $newLine;
        $xml .= "<key>PushEnabled</key>" . $newLine;
        $xml .= "<$pushEnabledText/>" . $newLine;
        $xml .= "<key>AdsEnabled</key>" . $newLine;
        $xml .= "<$addsEnabledText/>" . $newLine;
        $xml .= '<key>FaceBookAPIKey</key>' . $newLine;
        $xml .= "<string>2f2609e2c7b262e41dff261736748fb9</string>" . $newLine;
        $xml .= '<key>FaceBookAPISecret</key>' . $newLine;
        $xml .= "<string>4d4a7d398663bf166488071d46e71642</string>" . $newLine;
        $xml .= '<key>FaceBookPostImageURL</key>' . $newLine;
        $xml .= "<string>$faceBookImagePath</string>" . $newLine;
        $xml .= '<key>App Email</key>' . $newLine;
        $xml .= "<string>info@phizuu.com</string>" . $newLine;
        $xml .= '<key>Twitter</key>' . $newLine;
        $xml .= "<dict>" . $newLine;
        $xml .= '<key>Key</key>' . $newLine;
        $xml .= "<string>gnVBYVkfzLgWVokJyKxgQ</string>" . $newLine;
        $xml .= '<key>Secret</key>' . $newLine;
        $xml .= "<string>5KSvcIfvlXjfGJaqBPb9nf5s4thu7r8MKWc7cZOHIIY</string>" . $newLine;
        $xml .= "</dict>" . $newLine;
        $xml .= '</dict>' . $newLine;
        $xml .= '</plist>';

        return file_put_contents($path . "/HHConfig.plist", $xml) ;
    }

    private function _arrangeModuleList($currentList) {
        $correctList = array(
            'Home',
            'Music',
            'Album',
            'Events',
            'FanWall',
            'Videos',
            'Discography',
            'BuyStuffs',
            'Twitter',
            'News',
            'Links',
            'Biography',
            'About',
            'UserAccounts'
        );

        $correctedList = array();

        foreach ($correctList as $item) {
            if(array_search($item, $currentList)!==FALSE) {
                $correctedList[]=$item;
            }
        }
        return $correctedList;
    }

    public function moveImages($iconImage, $iTunesArtwork, $fbImage, $musicCoverImage, $loadImage, $homeImagesArr, $appId, $path) {
        
        if(!copy($iTunesArtwork,$path."/iTunesArtwork")) {
            return false;
        } else {
            unlink($iTunesArtwork);
        }

        if(!copy($iconImage,$path."/Icon.png")) {
            return false;
        } else {
            unlink($iconImage);
        }
        
     

        $fbImagePath = "../../../../../images/facebook_post_images/$appId";
        //echo $path;
        if (!file_exists($fbImagePath)) {
            mkdir($fbImagePath);
        }

        if(!copy($fbImage,"$fbImagePath/$appId.png")) {
            return false;
        } else {
            unlink($fbImage);
        }

        $musicCoverPath = "../../../../../images/music_cover_images/$appId";
        //echo $path;
        if (!file_exists($musicCoverPath)) {
            mkdir($musicCoverPath);
        }

        if ($musicCoverImage!='') {
            if(!copy($musicCoverImage,"$musicCoverPath/$appId.png")) {
                return false;
            } else {
                unlink($musicCoverImage);
            }
        }
        
        if(!copy($loadImage,$path."/Default.png"))
            return false;

        if(!strpos($loadImage, 'free.jpg'))
            unlink($loadImage);

        $i = 1;
        foreach ($homeImagesArr as $homeImage) {
            $imageCache = imagecreatefrompng($homeImage);
            if(!imagejpeg($imageCache,$path."/HomeImage$i.jpg"))
                return false;
            unlink($homeImage);
            $i++;
        }

        return true;
    }

    function createZip($path, $appId, $packageId, $appName) {
        $zip = new ZipArchive();
        $filename = "$path/$appId.zip";
        
        $files = $this->_getFileArray($path);

        if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
            return false;
        } else {
            $files = $this->_getFileArray($path);
            $zip->addEmptyDir($appId);
            $zip->addEmptyDir("$appId/HHResources");
            foreach($files as $file) {
                if ($file != 'home_image_count.txt' && $file != "$appId.zip") {
                    if ($file == 'About.txt') {
                        $abtStr = file_get_contents("$path/$file");
                        
                        if ($packageId==1) {
                            $abtStr = "Thanks for downloading the official '$appName' iPhone application, presented by phizuu.\n\n" . $abtStr;
                        }

                        $abtFooter = 'Any suggestions or feedback, please click on the "Email" link at the top right of this page to email';

                        if (strpos($abtStr,$abtFooter)===FALSE) {
                            $abtStr = $abtStr . "\n\n" . $abtFooter ;
                        }

                        $zip->addFromString("$appId/HHResources/$file", $abtStr);
                    } else {
                        $zip->addFile("$path/$file","$appId/HHResources/$file");
                    }
                }
            }
            $zip->close();
        }

        return $filename;
    }

    function sendEmail($appName, $appId, $userName, $email, $packageId) {
        logError('Sending Mail');
	require_once '../../../../../admin/common/XPertMailer.php';

        if( $packageId == "1"){
                $package = "garage band";
        }else if($packageId == "2"){
                $package = "idol";
        }else if($packageId == "3"){
                $package = "rockstar";
        } else if( $packageId == "4"){
                $package = "android+bb: garage band";
        }else if($packageId == "5"){
                $package = "android+bb: idol";
        }else if($packageId == "6"){
                $package = "android+bb: rockstar";
        }

        $link = "http://phizuu.com/cms/apps/admin";

	$subject = "New Application - $appName";
	$mail = new XPertMailer;
	$mail->from('info@phizuu.com', 'phizuu');


	$text = '';

	$msg = "<html>\n";
	$msg .= "<body>\n";
	$msg .= "<span style='font-family:Arial, Helvetica, sans-serif;font-size:12px'><br/>\n";
	$msg .= "<b>$userName</b> has completed the application with <b>$package</b> package. Download the application data from <a href='$link'>cms admin</a>.\n";
	$msg .= "<br/><br/>Username: $userName";
	$msg .= "<br/>Email: $email";
	$msg .= "<br><br>phizuu App Builder";
	$msg .= "</span></body>\n";
	$msg .= "</html>\n";

	$send = $mail->send('info.phizuu@gmail.com', $subject, $text, $msg);
        return true;
    }

    function _getFileArray($path, $exclude = ".|..", $recursive = false) {
        $path = rtrim($path, "/") . "/";
        $folder_handle = opendir($path);
        $exclude_array = explode("|", $exclude);
        $result = array();
        while(false !== ($filename = readdir($folder_handle))) {
            if(!in_array(strtolower($filename), $exclude_array)) {
                if(is_dir($path . $filename . "/")) {
                    if($recursive) $result[] = file_array($path, $exclude, true);
                } else {
                    $result[] = $filename;
                }
            }
        }
        return $result;
    }

    public function createTextFiles($aboutText, $bioText, $path) {
        if(!file_put_contents($path . "/Bio.txt", utf8_encode($bioText)))
            return false;

        $aboutText = utf8_encode($aboutText);
        if(!file_put_contents($path . "/About.txt", $aboutText))
            return false;

        return true;
    }

    public function createFolderForApplication($appId) {
        $path = "../../../application_dirs/$appId";
        //echo $path;
        if (!file_exists($path)) {
            mkdir($path);
        }
        
        return $path;
    }
    

    public function saveModuleList($moduleArr) {
        $sql = "DELETE FROM ab_modules WHERE user_id = '{$this->userId}'";
        $this->dao->query($sql);
        
        $autoCompleteModuleNames = $this->getListOfModules(true,true);

        foreach ($moduleArr as $module) {
            if (array_search($module, $autoCompleteModuleNames)) {
                $completed = '1';
            } else {
                $completed = $this->getContentStatus($module);
            }

            $sql = "INSERT INTO `ab_modules` (`user_id`, `module_name`, `completed`) VALUES ('{$this->userId}', '{$module}', '$completed');";
            $this->dao->query($sql);
        }
    }

    public function saveModule($moduleName) {
        $sql = "INSERT IGNORE INTO `ab_modules` (`user_id`, `module_name`, `completed`) VALUES ('{$this->userId}', '{$moduleName}', '1');";
        $this->dao->query($sql);
    }

    public function getSelectedModules($onlyNames = false) {
        $sql = "SELECT * FROM ab_modules WHERE user_id = '{$this->userId}'";
        $res = $this->dao->query($sql);
        $array = $this->dao->getArray($res);

        if ($onlyNames) {
            $cnt = 1;
            $modulesArr = array();
            foreach ($array as $elem) {
                $modulesArr[$cnt] = $elem['module_name'];
                $cnt++;
            }

            return $modulesArr;
        } else {
            return $array;
        }
    }

    public function isFlickerSet() {
        $sql = "SELECT * FROM setting WHERE type='3' AND user_id='{$this->userId}'";
        $res = $this->dao->query($sql);
        if (mysql_num_rows($res)>0) {
            return true;
        } else {
            return false;
        }
    }

    public function isYouTubeSet() {
        $sql = "SELECT * FROM setting WHERE type='1' AND user_id='{$this->userId}'";
        $res = $this->dao->query($sql);
        if (mysql_num_rows($res)>0) {
            return true;
        } else {
            return false;
        }
    }

    public function getContentStatus($module) {
       
        switch ($module) {
            case 'Photos':
                $table = 'image';
                break;

            case 'Videos':
                $table = 'video';
                break;

            case 'Music':
                $table = 'song';
                break;

            case 'Events':
                $table = 'tour';
                break;

            case 'Links':
                $table = 'link';
                break;

            case 'News':
                $table = 'news';
                break;

            default:
                $table = '';
                break;
        }
        if ($table != '') {
            $sql = "SELECT * FROM $table WHERE user_id='{$this->userId}' AND iphone_status='1'";
            //echo $sql;
            $res = $this->dao->query($sql);
            if (mysql_num_rows($res)>0) {
                return 1;
            } else {
                $sql = "SELECT * FROM $table WHERE user_id='{$this->userId}'";
                $res = $this->dao->query($sql);
                if (mysql_num_rows($res)>0) {
                    return 2;
                }
            }

            if ($table=='news') {
                $news= new News();
                $lid='2';
                $rss_list = $news->getRssFeed($lid);
                if(sizeof($rss_list) >0) {
                    $rss_stat="1";

                } else {
                    $rss_stat="0";
                }


                foreach($rss_list as $rss_one) {
                    $rss_val= $rss_one -> value;
                    $rss_id= $rss_one->id;
                }

                if ($rss_val!="") {
                    return 1;
                }
            }
        }

        return 0;
    }

    public function setContentStatus($moduleName, $status) {
        $sql = "UPDATE ab_modules SET completed = '$status' WHERE module_name='$moduleName' AND user_id={$this->userId}";
        $res = $this->dao->query($sql);
    }

    public function setPackage($package) {
         $sql = "UPDATE user SET package_id = $package WHERE id={$this->userId}";
         $res = $this->dao->query($sql);
    }

    public function generateAppId($push) {
        $sql = "SELECT * FROM  last_ids";
        $res = $this->dao->query($sql);
        $arr = $this->dao->getArray($res);
        if ($push == 1) {
            $id = $arr[1]['last_id'] +1;
            $sql = "UPDATE last_ids SET last_id = $id WHERE `key` = 'push'";
        } else {
            $id = $arr[0]['last_id'] +1;
            $sql = "UPDATE last_ids SET last_id = $id WHERE `key` = 'none_push'";
        }

        $res = $this->dao->query($sql);

        return $id;
    }

    function getAppDetails() {
        $sql = "SELECT * FROM user WHERE id='{$this->userId}'";
        $res = $this->dao->query($sql);
        $arr = $this->dao->getArray($res);
        return $arr[0];
    }

    function saveIconImages($iconImage,$iconImage2x, $iTunesArtwork, $iTunesArtwork2x,$fbImage, $anroid36 , $anroid48 , $anroid72 , $anroid96 ,$appId) {
        $path = $this->createFolderForApplication($appId);
        
        if(!copy($iTunesArtwork,$path."/iTunesArtwork")) {
            return false;
        } else {
            unlink($iTunesArtwork);
        }
        
        if(!copy($iTunesArtwork2x,$path."/iTunesArtwork@2x")) {
            return false;
        } else {
            unlink($iTunesArtwork2x);
        }
        
        if(!copy($iconImage,$path."/Icon.png")) {
            return false;
        } else {
            unlink($iconImage);
        }
        
        if(!copy($iconImage2x,$path."/Icon@2x.png")) {
            return false;
        } else {
            unlink($iconImage2x);
        }
        //Anroid images
        if (!file_exists($path."/drawable-ldpi/")) {
            mkdir($path."/drawable-ldpi/");
        }
        if(!copy($anroid36,$path."/drawable-ldpi/icon.png")) {
            return false;
        } else {
            unlink($anroid36);
        }
        if (!file_exists($path."/drawable-hdpi/")) {
            mkdir($path."/drawable-hdpi/");
        }
        if(!copy($anroid48,$path."/drawable-hdpi/icon.png")) {
            return false;
        } else {
            unlink($anroid48);
        }
        if (!file_exists($path."/drawable-mdpi/")) {
            mkdir($path."/drawable-mdpi/");
        }
        if(!copy($anroid72,$path."/drawable-mdpi/icon.png")) {
            return false;
        } else {
            unlink($anroid72);
        }
        if (!file_exists($path."/drawable-xdpi/")) {
            mkdir($path."/drawable-xdpi/");
        }
        if(!copy($anroid96,$path."/drawable-xdpi/icon.png")) {
            return false;
        } else {
            unlink($anroid96);
        }
        
        $fbImagePath = "../../../../../images/facebook_post_images/$appId";
        //echo $path;
        if (!file_exists($fbImagePath)) {
            mkdir($fbImagePath);
        }

        if(!copy($fbImage,"$fbImagePath/$appId.png")) {
            return false;
        } else {
            unlink($fbImage);
        }
    }

    private function _saveSettings($type, $value) {
        $sql = "INSERT INTO aw_data VALUES (
            user_id={$this->userId},
            type=$type,
            value='$value'
            )";
        $res = $this->dao->query($sql);
    }

    public function saveLoadImage($useDefault = FALSE, $loadImage = "", $appId = "") {
        include "../../../view/user/home_images/ThumbNail.php";
        $path = $this->createFolderForApplication($appId);
        $thumbNail = new ThumbNail();
        if ($useDefault==TRUE) {
            $loadImage = "../../../images/free.png";
        }
        $fileName = $this->GetFileName($loadImage);
        $dirPath = $this->GetFilePath($loadImage).'/';
        
        if(!copy($loadImage,$path."/Default@2x.png"))
        {
            return false;
        }else{
        //$thumbNail->create_abs_image('Default@2x.png', 'Default@2x.png', 640, 921, $path."/");
        $thumbNail->create_abs_image('Default@2x.png', 'Default.png', 320, 460, $path."/");
        }
        
        if ($useDefault==FALSE)
            unlink($loadImage);
    }
    function GetFileName($path){
        $path_parts = pathinfo($path);

        $exe =  $path_parts['extension'];
        $file =  $path_parts['filename']; 
        return $imageName = $file.'.'.$exe;
    }
    function GetFilePath($path){
        $path_parts = pathinfo($path);
        $dirPath = $path_parts['dirname'];
        return $dirPath;
    }

    public function saveHomeImages($homeImagesArr, $appId = "") {
        $path = $this->createFolderForApplication($appId);

        $i = 1;
        foreach ($homeImagesArr as $homeImage) {
            $imageCache = imagecreatefrompng($homeImage);
            if(!imagejpeg($imageCache,$path."/HomeImage$i.jpg"))
                return false;
            imagedestroy($imageCache);
            unlink($homeImage);
            $i++;
        }

        file_put_contents($path."/home_image_count.txt",count($homeImagesArr));
    }

    public function copyThemePListFile($appId) {
        $path = $this->createFolderForApplication($appId);
        copy('../../../temporary_files/Theme.plist', $path . '/Theme.plist');
    }

    public function saveMusicCoverImage($musicCoverImage, $appId) {
        $musicCoverPath = "../../../../../images/music_cover_images/$appId";
        
        if (!file_exists($musicCoverPath)) {
            mkdir($musicCoverPath);
        }

        if ($musicCoverImage!='') {
            if(!copy($musicCoverImage,"$musicCoverPath/$appId.png")) {
                return false;
            } else {
                unlink($musicCoverImage);
            }
        }
    }

    public function createInformationFiles($aboutText, $bioText, $keywordsText, $appId) {
        $path = $this->createFolderForApplication($appId);
        
        if(!file_put_contents($path . "/Bio.txt", utf8_encode($bioText)))
            return false;

        if(!file_put_contents($path . "/About.txt", utf8_encode($aboutText)))
            return false;

        if(!file_put_contents($path . "/Keywords.txt", utf8_encode($keywordsText)))
            return false;
        
        return true;
    }

    public function writePermisionsForModules($moduleArr, $appId, $packageId) {
        $music = array_search('Music', $moduleArr)?'1':'0';
        $videos = array_search('Videos', $moduleArr)?'1':'0';
        $photos = array_search('Photos', $moduleArr)?'1':'0';
        $flyers = array_search('Flyers', $moduleArr)?'1':'0';
        $news = array_search('News', $moduleArr)?'1':'0';
        $tours = array_search('Tours', $moduleArr)?'1':'0';
        $links = array_search('Links', $moduleArr)?'1':'0';
        $send_message = ($packageId!=1)?'1':'0';

        $sql = "INSERT INTO `module` (
            `id`,
            `app_id`,
            `music`,
            `videos`,
            `photos`,
            `flyers`,
            `news`,
            `tours`,
            `links`,
            `settings`,
            `send_message`,
            `analytics`,
            `buy_stuff`,
            `fan_contents`,
            `discography`,
            `app_update`
            ) VALUES (
            NULL,
            '$appId',
            '".$music."',
            '".$videos."',
            '".$photos."',
            '".$flyers."',
            '".$news."',
            '".$tours."',
            '".$links."',
            '1',
            '".$send_message."',
            '1',
            '1',
            '1',
            '1',
            '1'
            );";
        //echo $sql;
        $this->dao->query($sql);
    }

    function saveAdditionalInfo($userId) {
        $browserInfo = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"No agent found";
        $sql = "INSERT INTO `aw_additional_info` (`user_id`,`browser_info`) VALUES ($userId, '$browserInfo')";
        $this->dao->query($sql);
    }

    function addAdditionalModules($packageId, $fanWall=TRUE, $twitter=FALSE) {
        $additionalModules = array(
            'Biography',
            'About',
            'UserAccounts'
        );

        if ($packageId!=1 && $fanWall) {
            $additionalModules[] = 'FanWall';
        }

        if ( $twitter) {
            $additionalModules[] = 'Twitter';
        }

        foreach ($additionalModules as $module) {
            $this->saveModule($module);
        }
    }

    public function addWallPost($appId, $appName, $comment, $faceBookImageURL) {
        $lineFeed = "\r\n";

        $headers = array("Content-type: multipart/form-data; boundary=---------------daAKdfkfsdkKdf8s");

        //First Section
        $data = $lineFeed . "-----------------daAKdfkfsdkKdf8s" . $lineFeed;
        $data .= "Content-Disposition: form-data; name=\"json\"" . $lineFeed . $lineFeed;

        $data .= '{"user_name":"'.$appName.'","user_type":"FB","comment":"'.stripslashes($comment).'","image":{"uri":"'.$faceBookImageURL.'"},"location":""}' . $lineFeed; //Data

        $data .= "-----------------daAKdfkfsdkKdf8s". $lineFeed;

        //Second Section
//        $data .= $lineFeed . "-----------------daAKdfkfsdkKdf8s" . $lineFeed;
//        $data .= "Content-Disposition: form-data; name=\"image\"; filename=\"fanphoto.jpg\"" . $lineFeed;
//        $data .= "Content-Type: application/octet-stream" . $lineFeed . $lineFeed;
//
//        $fileContent = file_get_contents($faceBookImageURL);
//        $data .= $fileContent . $lineFeed; //Data
//
//        $data .= "-----------------daAKdfkfsdkKdf8s". $lineFeed;

        //Sending Data
        $url = "http://connect.phizuu.com/client/$appId/wall/";

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 4s
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
    }
}
?>