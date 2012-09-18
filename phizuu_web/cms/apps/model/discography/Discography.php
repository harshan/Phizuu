<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Discography
 *
 * @author Dhanushka
 */
class Discography {
    private $userId;
    private $dao;

    public function  __construct($userId) {
        $this->userId = $userId;
        $this->dao = new Dao();
    }

    public function listDiscographies() {
        $sql = "SELECT * FROM `discography`  WHERE user_id = {$this->userId} ORDER BY `order`";

        return $this->dao->toArray($sql);
    }

    public function addNew($data, $file) {
        $title = mysql_escape_string($data['title']);
        $info = mysql_escape_string($data['info']);
        $details = mysql_escape_string($data['details']);

        $sql = "SELECT MAX(`order`) as max_order FROM `discography` WHERE user_id={$this->userId}";
        $array = $this->dao->toArray($sql);
        $orderMax = $array[0]['max_order'];

        $sql = "INSERT INTO `discography` (`user_id`,`title`,`info`,`details`, `order`) VALUES ({$this->userId}, '$title','$info','$details', '$orderMax')";
        $this->dao->query($sql);
        
        $id = mysql_insert_id();

        if(isset ($_POST['buyURLTitle'])) {
            $buyTitles = $_POST['buyURLTitle'];
            $buyLinks = $_POST['buyURLLink'];

            for ($i=0; $i<count($buyTitles); $i++) {
                if($buyTitles[$i]!='' && $buyLinks[$i]!='') {
                    $this->addNewBuyURL($id, $buyTitles[$i], $buyLinks[$i]);
                }
            }
        }

        $this->updateImage($id, $file['image']['tmp_name']);

        return $id;
    }

    public function updateImage($id, $fileName) {
        $storageServer = new StorageServer('../../../../../static_files');
        $fullImagePath = $storageServer->getPathForCatogory('images', 'discography_images')."$id.jpg";
        $thumbImagePath = $storageServer->getPathForCatogory('images', 'discography_thumb_images')."$id.jpg";

        $fullImageURI = $storageServer->getURLForPath('images', 'discography_images', "$id.jpg");
        $thumbImageURI = $storageServer->getURLForPath('images', 'discography_thumb_images', "$id.jpg");

        set_error_handler("imageErrorHandler");
        $this->_createImage($fileName, $fullImagePath, $thumbImagePath, true);
        restore_error_handler();

        $sql = "UPDATE `discography` SET image_uri='$fullImageURI', thumb_uri='$thumbImageURI' WHERE id='$id'";
        $this->dao->query($sql);
    }

    public function setOrder($orderedArr) {
        foreach ($orderedArr as $order=>$id) {
            $sql = "UPDATE discography SET `order`='$order' WHERE id='$id'";
            $this->dao->query($sql);
        }
    }

    private function _createImage($srcFileName, $dstFileName, $thumbFileName, $unlink) {
        $image_info = getimagesize($srcFileName);
        $imageType = $image_info[2];
        if( $imageType == IMAGETYPE_JPEG ) {
            $image = imagecreatefromjpeg($srcFileName);
        } elseif( $imageType == IMAGETYPE_GIF ) {
            $image = imagecreatefromgif($srcFileName);
        } elseif( $imageType == IMAGETYPE_PNG ) {
            $image = imagecreatefrompng($srcFileName);
        }

        $oWidth = imagesx($image);
        $oHeight = imagesy($image);

        $imageHeight = 225;
        $imageWidth = 225;
        $thumbHeight = 100;
        $thumbWidth = 100;

        $rW = $imageWidth;
        $rH = ($oHeight/$oWidth)*$rW;

        $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
        //echo $rH;
        if($rH>=$imageHeight) {
            $extraHeight = $rH - $imageHeight;

            $top = ($extraHeight/2)*($oWidth/$imageWidth);
            $src_h = $imageHeight * ($oWidth/$imageWidth);

            imagecopyresampled($newImage, $image, 0, 0, 0, $top, $imageWidth, $imageHeight, $oWidth, $src_h);
        } else {
            $rH = $imageHeight;
            $rW = ($oWidth/$oHeight) * $rH;

            $extraWidth = $rW - $imageWidth;

            $left = ($extraWidth/2)*($oHeight/$imageHeight);
            $src_w = $imageWidth * ($oHeight/$imageHeight);

            imagecopyresampled($newImage, $image, 0, 0, $left, 0, $imageWidth, $imageHeight, $src_w, $oHeight);
        }
        imagedestroy($image);

        imagejpeg($newImage,$dstFileName,90);
        $thumbImage = imagecreatetruecolor($thumbWidth, $thumbHeight);

        imagecopyresampled($thumbImage, $newImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $imageWidth, $imageHeight);
        imagejpeg($thumbImage,$thumbFileName,90);

        imagedestroy($newImage);
        imagedestroy($thumbImage);
        
        if($unlink)
            unlink($srcFileName);
    }

    public function deletePictures($id) {
        $storageServer = new StorageServer('../../../../../static_files');
        $fullImagePath = $storageServer->getPathForCatogory('images', 'discography_images')."$id.jpg";
        $thumbImagePath = $storageServer->getPathForCatogory('images', 'discography_thumb_images')."$id.jpg";

        if(file_exists($fullImagePath))
            unlink($fullImagePath);

        if(file_exists($thumbImagePath))
            unlink($thumbImagePath);
    }

    public function getBuyLinks($discoId, $buyLinkId = NULL) {
        $buyLinkRestriction = '';
        if ($buyLinkId!=NULL) {
            $buyLinkRestriction = " AND id=$buyLinkId";
        }
        $sql = "SELECT * FROM discography_buy_links WHERE discography_id=$discoId $buyLinkRestriction";
        return $this->dao->toArray($sql);
    }

    public function addNewBuyURL ($id, $title, $link) {
        $sql = "INSERT INTO discography_buy_links VALUES (NULL, $id,'$title','$link')";
        $this->dao->query($sql);

        return mysql_insert_id();
    }

}



function imageErrorHandler($errno, $errstr, $errfile, $errline)
{
    if($errno==2048) {
        $message = urlencode('Image is too big to resize at the server!');
        header("Location: DiscographyController.php?action=main_view&message=$message");
        exit;
    } else {
        $message = urlencode('Unknown error occured!');
        header("Location: DiscographyController.php?action=main_view&message=$message");
        exit;
    }
    /* Don't execute PHP internal error handler */
    return true;
}
    
?>
