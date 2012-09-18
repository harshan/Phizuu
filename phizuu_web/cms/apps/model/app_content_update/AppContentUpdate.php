<?php
class AppContectUpdate {
    private $userId;
    private $appId;

    public function  __construct($userId, $appId) {
        $this->userId = $userId;
        $this->appId = $appId;
    }

    public function getUploadedContents () {
        $appWizard = new AppWizard($this->userId);
        $path = $appWizard->createFolderForApplication($this->appId);

        $updatedContents = array();
        if (file_exists("$path/Icon.png")) {
            $updatedContents['icon'] = "$path/Icon.png";
        }
        
        if (file_exists("$path/home_image_count.txt")) {
            $count = file_get_contents("$path/home_image_count.txt");
            $homeScreenImages = array();
            for ($i=1; $i<=$count; $i++) {
                if (file_exists("$path/HomeImage$i.jpg")) {
                    $homeScreenImages[] = "$path/HomeImage$i.jpg";
                }
            }
            $updatedContents['homeImages'] = $homeScreenImages;
        }
        
        if (file_exists("$path/Default.png")) {
            $updatedContents['loadingImage'] = "$path/Default.png";
        }

        if (file_exists("$path/About.txt")) {
            $info = array();
            $info['about'] = file_get_contents("$path/About.txt");
            $info['bio'] = file_get_contents("$path/Bio.txt");
            $info['keywords'] = file_get_contents("$path/Keywords.txt");

            $updatedContents['info'] = $info;
        }

        return $updatedContents;
    }

    public function createZipFile($path, $appId) {
        $zip = new ZipArchive();
        $filename = "$path/$appId.zip";

        $files = $this->_getFileArray($path);

        if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
            return false;
        } else {
            $files = $this->_getFileArray($path);
            foreach($files as $file) {
                $zip->addFile("$path/$file","/$file");
            }
            $zip->close();
        }

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

    public function sendEmails($userArr, $amount) {
        $username = $userArr['username'];
        $appName = $userArr['app_name'];
        $appId = $userArr['app_id'];

        $link = "http://phizuu.com/cms/apps/application_dirs/$appId/$appId.zip";

        //Email to user
        $m = new MAIL;
        $m->From('info@phizuu.com', 'phizuu');
        $m->AddTo($userArr['email']);
        $m->Subject('Application Content Update Request - phizuu CMS');
        $m->Text('Please use html browser to view this email');

        $html = '<html>';
        $html .= '<body>';
        $html .= "Hey $username, <br/><br/>";
        $html .= "Thank you for your request for changing contents of the application '$appName' and the payment of \${$amount}. This is to inform you that we have recieved the request.<br/><br/>";
        $html .= "-Team phizuu";
        $html .= '</body>';
        $html .= '</html>';

        $m->Html($html);

        $m->Send();

        //Email to phizuu
        $m = new MAIL;
        $m->From('info@phizuu.com', 'phizuu');
        $m->AddTo('info.phizuu@gmail.com');
        $m->Subject('Application Content Update Request - phizuu CMS');
        $m->Text('Please use html browser to view this email');

        $html = '<html>';
        $html .= '<body>';
        $html .= "Hi phizuu,<br/><br/>";
        $html .= "This is to inform that '$username' has submitted an application content change request. Please download the contents of the request from <a href='$link'>here</a>.<br/><br/>";
        $html .= "Username: $username<br/>";
        $html .= "Amount Paid: $amount<br/>";
        $html .= "App Name: $appName<br/>";
        $html .= "Email: {$userArr['email']}<br/>";
        $html .= "<br/>phizuu CMS";
        $html .= '</body>';
        $html .= '</html>';

        $m->Html($html);

        $m->Send();
    }
}
?>
