<?php
class Album {
    public $userId;
    public $dao;

    public function __construct($userId) {
        $this->userId = $userId;
        $this->dao = new Dao();
    }

    public function switchToAlbums() {
        $sql = "INSERT INTO `album_info` (`user_id`, `album_mode`) VALUES ($this->userId, 1);";
        $this->dao->query($sql);

        $sql = "UPDATE image SET iphone_status='' WHERE user_id = {$this->userId}";
        $this->dao->query($sql);
    }

    public function getAlbumStatus() {
        $sql = "SELECT * FROM `album_info` WHERE user_id={$this->userId}";
        $arr = $this->dao->toArray($sql);
        if (count($arr)>0)
            return $arr[0]['album_mode'];
        else
            return 0;
    }

    public function deleteAlbum($id) {
        $images = $this->listPicturesOfAlbum($id);
        
        foreach($images as $image) {
            $sql = "UPDATE image SET iphone_status='' WHERE id = {$image->id}";
            $this->dao->query($sql);
        }
        
        $sql = "DELETE FROM albums WHERE id = $id";
        $this->dao->query($sql);

        $sql = "DELETE FROM album_image WHERE album_id = $id";
        $this->dao->query($sql);

        $path = $this->getImagePathForAlbum($id);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function listAlbums($albumId = NULL) {
        if ($albumId != NULL) {
            $whereText = "AND id = $albumId";
        } else {
            $whereText = "";
        }
        
        $sql = "SELECT * FROM albums WHERE user_id={$this->userId} $whereText ORDER BY `order`";
        $albums = $this->dao->toArray($sql);

        $cnt=0;
        foreach ($albums as $item) {
            $sql = "SELECT * FROM album_image WHERE album_id={$item['id']}";
            $images = $this->dao->query($sql);
            $albums[$cnt]['image_count'] = mysql_numrows($images);
            $cnt++;
        }

        return $albums;
    }

    public function addPictureToAlbum($imageId, $albumId) {
        $sql = "SELECT MAX(`order`) as max_order FROM image WHERE user_id={$this->userId}";
        $arr = $this->dao->toArray($sql);
        $order = $arr[0]['max_order'] + 1;
        
        $sql = "UPDATE image SET iphone_status='1', `order`=$order WHERE id='$imageId'";
        $this->dao->query($sql);
        $sql = "INSERT INTO `album_image` (`album_id`, `image_id`) VALUES ($albumId, $imageId);";
        $this->dao->query($sql);
    }

    public function removePictureFromAlbum($imageId, $albumId) {
        $sql = "UPDATE image SET iphone_status='' WHERE id='$imageId'";
        $this->dao->query($sql);
        $sql = "DELETE FROM `album_image` WHERE `album_id` = $albumId AND `image_id` = $imageId";
        $this->dao->query($sql);
    }
    public function updateIphoneStatus($imageId, $albumId) {
        $sql = "UPDATE image SET iphone_status='1' WHERE id='$imageId'";
        $this->dao->query($sql);
        
    }
    public function listPicturesOfAlbum($albumId, $limit = FALSE) {
        if ($limit != FALSE) {
            $limitText = "LIMIT $limit";
        } else {
            $limitText = "";
        }

        $sql = "SELECT * FROM album_image, image WHERE album_image.image_id = image.id AND album_id=$albumId ORDER BY `order` $limitText";
        $arr = $this->dao->toObject($sql);

        return $arr;
    }

    public function countAllPictures($iPhone = TRUE) {
        $sql = "SELECT id FROM image WHERE user_id={$this->userId} AND iphone_status=1";
        $res = $this->dao->query($sql);

        return mysql_num_rows($res);
    }

    public function refreshThumb($albumId) {
        $images = $this->listPicturesOfAlbum($albumId, 3);
        $defaultThumb = "../images/albumthumb.png";

        $storage = new StorageServer('');

        if (count($images) == 0) {
            return $storage->getBaseURL() . '/cms/apps/images/albumthumb.png';
        } else {
            $image = $this->_generateThumb($images);
            $path = $this->getImagePathForAlbum($albumId);
            imagepng($image, $path, 9);
            imagedestroy($image);

            
            $url = $storage->getURLForPath('images', 'album_thumbs', $albumId . '.png');

            return $url;
        }
    }

    public function createAlbumImage($temp=TRUE, $id=NULL) {
        $storageServer = new StorageServer('../../../static_files');
        $path = $storageServer->getPathForCatogory('images', 'album_images');
        return $path . $albumId . '.png';
    }

    public function uploadTempCoverImage($filePath, $unlink=TRUE) {
        $storageServer = new StorageServer('../../../static_files');
        $path = $storageServer->getPathForCatogory('images', 'temp_images');

        $dstFileName = 'temp.jpg';
        $dstFilePath = $path . $dstFileName;

        $this->_createAlbumCover($filePath, $dstFilePath, $unlink);

        $url = $storageServer->getURLForPath('images', 'temp_images',$dstFileName);
        return array($url,$dstFilePath);
    }

    private function _createAlbumCover($srcFileName, $dstFileName, $unlink) {
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

        imagejpeg($newImage,$dstFileName,80);
        if($unlink)
            unlink($srcFileName);
    }

    public function updateThumb($albumId, $url) {
        $sql = "UPDATE albums SET thumb_uri = '$url' WHERE id = $albumId";
        $this->dao->query($sql);
    }

    public function getImagePathForAlbum($albumId) {
        $storageServer = new StorageServer('../../../static_files');
        $path = $storageServer->getPathForCatogory('images', 'album_thumbs');
        return $path . $albumId . '.png';
    }

    public function _generateThumb($images) {
        $image = imagecreatetruecolor(95,50);
        $text_color = imagecolorallocate($image, 233, 14, 91);

        $imageTransColor = imagecolorallocatealpha($image, 0,0,0,127);
        imagefill($image, 0, 0, $imageTransColor);
        //imagestring($image, 1, 5, 5,  'A Simple Text String', $text_color);

        //if (count($images) == 1) {
            $imageAr1 = $this->_createImage($images[0]->thumb_uri);

            $image1 = $imageAr1[0];

            $imageMasked1 = $this->_applyMaskForImage($image1);
            imagedestroy($image1);

            $image_rotated = imagerotate($imageMasked1,-4, $imageTransColor);

            imagecopy($image, $image_rotated, 32, -2, 0, 0, 70, 60);
            imagedestroy($image_rotated);

            $image_rotated = imagerotate($imageMasked1,8, $imageTransColor);
            imagecopy($image, $image_rotated, 1, -3, 0, 0, 60, 60);
            imagedestroy($image_rotated);

            if(count($images)<=1) {
                imagecopy($image, $imageMasked1, 15, 2, 0, 0, 66, 50);
            } else {
                $imageAr2 = $this->_createImage($images[1]->thumb_uri);
                $imageMasked2 = $this->_applyMaskForImage($imageAr2[0]);
                imagedestroy($imageAr2[0]);
                imagecopy($image, $imageMasked2, 15, 2, 0, 0, 66, 50);
                imagedestroy($imageMasked2);
            }
            imagedestroy($imageMasked1);


//            $image1 = $imageAr1[0];
//            $image_rotated = imagerotate($image1,45, 0);
//            imagecopyresized($image, $image_rotated, 0, 0, 0, 0, 50, 50, 100, 100);
//            imagedestroy($image_rotated);
//
//            $image_rotated = imagerotate($image1,-45, 0);
//            imagecopyresized($image, $image_rotated, 50, 0, 0, 0, 50, 50, 100, 100);
//            imagedestroy($image_rotated);
//
              //imagecopyresized($image, $image1, 25, 0, 0, 0, 50, 50, 75, 75);
        //}

        imagealphablending($image, true);
        imagesavealpha($image, true);
        return $image;
    }


    private function _applyMaskForImage($image) {
        $newImage = $this->_createImage('../images/mask_for_album_image_thumb.png');
        $newImage = $newImage[0];



        //$transparentColor = imagecolorallocate($newImage, 128, 128, 128);
        //imagecolortransparent($newImage, $transparentColor);

        //$cropedImage = imagecreatetruecolor(52, 39);
        //imagecopyresized  (  resource $dst_image  ,  resource $src_image  ,  int $dst_x  ,  int $dst_y  ,  int $src_x  ,  int $src_y  ,  int $dst_w  ,  int $dst_h  ,  int $src_w  ,  int $src_h  )
        imagealphablending($newImage, true);
        imagesavealpha($newImage, true);


        imagecopyresampled($newImage, $image, 3, 3, 0, 0, 52, 39, 75, 50);

        return $newImage;
    }

    private function _createImage($url) {
        $info = getimagesize($url);
        
        switch ( $info[2] ) {
          case IMAGETYPE_GIF:
            $image = imagecreatefromgif($url);
          break;
          case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($url);
          break;
          case IMAGETYPE_PNG:
            $image = imagecreatefrompng($url);
          break;
          default:
            return false;
        }

        return array($image,$info);
    }

    public function createAlbum($albumName, $date, $location, $desc, $imagePath) {
        if ($albumName=='' || strlen($albumName)>14) {
            return FALSE;
        }

        $storage = new StorageServer();
        $baseUrl = $storage->getBaseURL();
        $defaultImage = $baseUrl . "/cms/apps/images/albumthumb.png";

        $sql = "SELECT MAX(`order`) as max_order FROM albums WHERE user_id={$this->userId}";
        $arr = $this->dao->toArray($sql);
        $order = $arr[0]['max_order'] + 1;

        $sql = "INSERT INTO albums (album_name, album_date, location, description, user_id, thumb_uri, `order`) VALUES ('$albumName', '$date', '$location', '$desc', {$this->userId},'$defaultImage', $order)";
        $this->dao->query($sql);

        if(mysql_affected_rows() == 1) {
            $albumId = mysql_insert_id();

            $imageURI = $this->_uploadAlbumCover($albumId, $imagePath);

            $sql = "UPDATE albums SET image_uri = '$imageURI' WHERE id=$albumId";
            $this->dao->query($sql);

            unlink($imagePath);
            return $albumId;
        } else {
            return FALSE;
        }
        
    }

    public function editAlbum($albumId, $albumName, $date, $location, $desc, $imagePath) {
        $sqlPart = '';
        if($imagePath!='') {
            $imageURI = $this->_uploadAlbumCover($albumId, $imagePath);
            unlink($imagePath);
            $sqlPart =  ", image_uri = '$imageURI'";
        }
        $sql = "UPDATE albums SET album_name='$albumName', album_date='$date', location='$location', description='$desc' $sqlPart  WHERE id=$albumId";
        $this->dao->query($sql);

        return TRUE;
    }

    private function _uploadAlbumCover($albumId,$imagePath) {
        $storageServer = new StorageServer('../../../static_files');
        $fullImagePath = $storageServer->getPathForCatogory('images', 'album_cover');
        $fileName = $albumId.".jpg";
        copy($imagePath, $fullImagePath . $fileName);

        // Start generate CMS thumb
        list($image, $info) = $this->_createImage($imagePath);

        $oWidth = imagesx($image);
        $oHeight = imagesy($image);

        $imageHeight = 75;
        $imageWidth = 75;

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

        imagejpeg($newImage,$imagePath,80);

        $images[0]->thumb_uri = $imagePath;
        $imageThumb = $this->_generateThumb($images);

        $path = $this->getImagePathForAlbum($albumId);
        imagepng($imageThumb, $path, 9);
        imagedestroy($imageThumb);

        $thumbURL = $storageServer->getURLForPath('images', 'album_thumbs', $albumId . '.png');
        $this->updateThumb($albumId, $thumbURL);
        //// End generate CMS thumb

        return $storageServer->getURLForPath('images', 'album_cover', $fileName);
    }

    public function uploadImage($imagePath, $id) {
        $storageServer = new StorageServer('../../../static_files');
        $fullImagePath = $storageServer->getPathForCatogory('images', 'gallery_images');
        $thumbImagePath = $storageServer->getPathForCatogory('images', 'gallery_thumb_images');

        $thumbWidth = 75;
        $thumbHeight = 75;

        $imageHeight = 800;
        $imageWidth = 600;

        $imageFileName ="$id.jpg";
        $thumbFileName = "$id.jpg";

        $image = new SimpleImage();
        $image->load($imagePath);

        $oWidth = $image->getWidth();
        $oHeight = $image->getHeight();

        if ($oWidth < $imageWidth && $oHeight <$imageHeight) {
            $image->save($fullImagePath . $imageFileName, IMAGETYPE_JPEG, 90);
        } else {
            $tH = $imageHeight;
            $tW = ($oWidth/$oHeight) * $tH;

            if ($tW > $imageWidth) {
                $tW = $imageWidth;
                $tH = ($oHeight/$oWidth) * $tW;
            }
            $image->resize($tW,$tH);
            $image->save($fullImagePath . $imageFileName, IMAGETYPE_JPEG, 90);
        }

        $oWidth = $image->getWidth();
        $oHeight = $image->getHeight();

        $tH = $thumbHeight;
        $tW = ceil(($oWidth/$oHeight) * $tH);
        $y = 0;
        $x = ceil(($tW - $thumbWidth)/2);
        if ($tW < $thumbWidth) {
            $tW = $thumbWidth;
            $tH = ($oHeight/$oWidth) * $tW;
            $y = ceil(($tH - $thumbHeight)/2);
            $x = 0;
        }
        $image->resize($tW,$tH);

        $newImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
        //echo $tW.",".$tH;
        //imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h)
                    //imagecopyresampled($newImage, $image->image,0,0,$x,$y,$thumbWidth, $thumbHeight,$thumbWidth, $thumbHeight);
        imagecopy($newImage, $image->image, 0, 0,$x , $y, $thumbWidth, $thumbHeight);

        $image->image = $newImage;
        $image->save($thumbImagePath . $thumbFileName, IMAGETYPE_JPEG, 90);

        return filesize($fullImagePath . $imageFileName);
    }

    public function deletePicture($id) {
        $sql = "DELETE FROM image WHERE id=$id";
        $this->dao->query($sql);
        
        $storageServer = new StorageServer('../../../static_files');
        $fullImagePath = $storageServer->getPathForCatogory('images', 'gallery_images')."$id.jpg";
        $thumbImagePath = $storageServer->getPathForCatogory('images', 'gallery_thumb_images')."$id.jpg";

        if(file_exists($fullImagePath))
            unlink($fullImagePath);

        if(file_exists($thumbImagePath))
            unlink($thumbImagePath);
    }

    public function isFlickrImageAlreadyAdded($url) {
        $sql = "SELECT id FROM image WHERE user_id={$this->userId} AND uri = '$url'";
        $res = $this->dao->query($sql);

        if (mysql_num_rows($res)>0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function listPhotosToSelectForAlbumCover($albumId=NULL) {
        if ($albumId != NULL) {
            $sql = "SELECT * FROM image WHERE image.user_id=$this->userId AND iphone_status='' ORDER BY `order`";
            $arr = $this->dao->toObject($sql);
            $images = $this->listPicturesOfAlbum($albumId);
            $arr = array_merge($arr, $images);
        } else {
            $sql = "SELECT * FROM image WHERE image.user_id=$this->userId AND iphone_status='' ORDER BY `order`";
            $arr = $this->dao->toObject($sql);
        }


        

        return $arr;
    }
    
}
?>
