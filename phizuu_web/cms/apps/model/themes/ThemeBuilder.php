<?php
/**
 * Description of ThemeBuilder
 *
 * @author Dhanushka
 */
class ThemeBuilder {
    public static $THEME_BASE_PATH = "iphone_themes";
    public static $THEME_PATH = "../../../iphone_themes";
    public static $THEME_CACHE_PATH = "../../../iphone_themes/theme_cache/theme.cache";

    public function refreshThemeCache() {
        $themeCache = new ThemeCache();
        $themeCache->mainThemes = $this->_getMainThemes();
        $themeCache->colorSets = $this->_getColorSets();
        $themeCache->iconSets = $this->_getIconSets();
        $themeCache->imageSets = $this->_getImageSets();

        $cacheString = serialize($themeCache);
        file_put_contents(self::$THEME_CACHE_PATH, $cacheString);

        return $themeCache;
    }

    public function getThemeDetails() {
        if(file_exists(self::$THEME_CACHE_PATH)) {
            return unserialize(file_get_contents(self::$THEME_CACHE_PATH));
        } else {
            return $this->refreshThemeCache();
        }
    }

    private function _getMainThemes() {
        $files = $this->_getFileList(self::$THEME_PATH . '/themes');

        $mainThemes = array();

        foreach($files as $file) {
            if (preg_match('/(.xml)$/', $file)) {
                $xml = simplexml_load_file($file);
                $mainTheme = get_object_vars($xml);

                $previewPath = self::$THEME_PATH . '/themes/' . $mainTheme['preview'];

                if (file_exists($previewPath)) {
                    $storage = new StorageServer();
                    $mainTheme['preview'] = $storage->getBaseURL() . "/cms/apps/" . self::$THEME_BASE_PATH . "/themes/" . $mainTheme['preview'];
                } else {
                    $mainTheme['preview'] = '';
                }

                $mainTheme['icon_sets'] = get_object_vars($mainTheme['icon_sets']);
                $mainThemes[$mainTheme['name']] = $mainTheme;
            }
        }

        return $mainThemes;
    }

    private function _getColorSets() {
        $files = $this->_getFileList(self::$THEME_PATH . '/colors');

        $colorThemes = array();

        foreach($files as $file) {
            if (preg_match('/(.xml)$/', $file)) {
                $xml = simplexml_load_file($file);
                $colorTheme = get_object_vars($xml);
                $colorThemes[$colorTheme['name']] = $colorTheme;
            }
        }

        return $colorThemes;
    }

    private function _getIconSets() {
        $path = self::$THEME_PATH . '/icon_sets';
        $folders = $this->_getFileList($path, FALSE, TRUE);

        $iconThemes = array();

        foreach($folders as $folder) {
            $subFolders = $this->_getFileList("$path/$folder", FALSE, TRUE);

            $subSets = array();
            foreach ($subFolders as $subFolder) {
                $infoFile = "$path/$folder/$subFolder/info.xml";
                
                if (file_exists($infoFile)) {
                    $xml = simplexml_load_file($infoFile);
                    
                    $images = array();
                    $files = $this->_getFileList("$path/$folder/$subFolder", FALSE);
                    $storage = new StorageServer();
                    
                    foreach ($files as $file) {
                        if (is_file("$path/$folder/$subFolder/$file") && preg_match('/(.jpg|.jpge|.png|.gif)$/', $file)) {
                            $imageBasePath = self::$THEME_BASE_PATH . "/icon_sets/$folder/$subFolder/$file";
                            $imageBaseURL = $storage->getBaseURL() . "/cms/apps/" . $imageBasePath;
                            $fileName = str_replace('.', '_', $file);
                            $fileName = str_replace('@', '_', $fileName);
                            $images[$fileName] = array('name'=>$file, 'path'=>$imageBasePath,'url'=>$imageBaseURL);
                        }
                    }

                    $iconSetInfo = get_object_vars($xml);
                    $iconSetInfo['images'] = $images;

                    $previewName = $this->_createPreviewForIconSet($images, self::$THEME_PATH . "/icon_sets/$folder/$subFolder/", $folder, $iconSetInfo['name']);
                    $iconSetInfo['preview'] = $storage->getBaseURL() . "/cms/apps/" . self::$THEME_BASE_PATH . "/icon_sets/$folder/$subFolder/$previewName";;

                    $subSets[$iconSetInfo['name']] = $iconSetInfo;
                }
            }

            $iconThemes[$folder] = $subSets;
        }

        return $iconThemes;
    }

    private function _createPreviewForIconSet($iconSet, $path, $type, $name) {
        $newImage = imagecreatetruecolor(67,100);

        $outImageName = "preview_cache_gfhn342fwsfpokl234mdsae";
        $imageOutPath = "$path/$outImageName";

        switch ($type) {
            case 'tab_bar':
                $color = imagecolorallocate($newImage, 50, 50, 50);
                $white = imagecolorallocate($newImage, 255, 255, 255);
                
                imagefill($newImage, 0, 0, $color);
                imagettftext($newImage, 8, 0, 3, 95, $white, '../../../common/fonts/TAHOMA.TTF', $name);
                $iconName = 'photos-tab_png';
                if (isset($iconSet[$iconName])) {                  
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 2, 4, 0, 0, 30, 30);
                    imagedestroy($image);
                }
                $iconName = 'music-tab_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 32, 4, 0, 0, 30, 30);
                    imagedestroy($image);
                }
                $iconName = 'events-tab_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 2, 42, 0, 0, 30, 30);
                    imagedestroy($image);
                }
                $iconName = 'home-tab_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 32, 42, 0, 0, 30, 30);
                    imagedestroy($image);
                }
                break;
                
            case 'general':
                $color = imagecolorallocate($newImage, 255, 255, 255);
                imagefill($newImage, 0, 0, $color);
                
                $iconName = 'itunes-download-icon_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 2, 2, 0, 0, 46, 26);
                    imagedestroy($image);
                }
                $iconName = 'btn_photo_up_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 2, 30, 0, 0, 63, 27);
                    imagedestroy($image);
                }
                $iconName = 'downButton_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 2, 60, 0, 0, 29, 38);
                    imagedestroy($image);
                }
                $iconName = 'upButton_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 35, 60, 0, 0, 29, 38);
                    imagedestroy($image);
                }
                break;
                
            case 'music':
                $color = imagecolorallocate($newImage, 255, 255, 255);
                imagefill($newImage, 0, 0, $color);
                $black = imagecolorallocate($newImage, 0, 0, 0);
                imagettftext($newImage, 8, 0, 2, 98, $black, '../../../common/fonts/TAHOMA.TTF', $name);
                
                $iconName = 'play_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 2, 2, 0, 0, 40, 40);
                    imagedestroy($image);
                }
                $iconName = 'miniplayer_bg_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 2, 46, 297, 0, 63, 40);
                    imagedestroy($image);
                }
                $iconName = 'close_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopy($newImage, $image, 42, 2, 0, 0, 27, 27);
                    imagedestroy($image);
                }
                break;
                
            case 'middle_tabs':
                $color = imagecolorallocate($newImage, 255, 255, 255);
                imagefill($newImage, 0, 0, $color);

                $iconName = 'sub_tab_inactive_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopyresampled($newImage, $image, 2, 2, 0, 0, 63, 45, 80, 57);
                    imagedestroy($image);
                }
                
                $iconName = 'sub_tab_active_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopyresampled($newImage, $image, 2, 49, 0, 0, 63, 45, 80, 57);
                    imagedestroy($image);
                }

                $iconName = 'book_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopyresampled($newImage, $image, 20, 61, 0, 0, 24, 24, 30, 30);
                    imagecopyresampled($newImage, $image, 20, 12, 0, 0, 24, 24, 30, 30);
                    imagedestroy($image);
                }
                
                break;
            case 'grid_images':
                $color = imagecolorallocate($newImage, 255, 255, 255);
                imagefill($newImage, 0, 0, $color);

                $iconName = 'nav_bg_png';
                if (isset($iconSet[$iconName])) {
                    $image = $this->_createImage('../../../'.$iconSet[$iconName]['path']);
                    imagecopyresampled($newImage, $image, 2, 2, 0, 0, 65, 65, 80, 80);
                    imagedestroy($image);
                }

                break;
            default:
                break;
        }

        imagejpeg($newImage, $imageOutPath, 90);
        imagedestroy($newImage);
        return $outImageName;
    }

    private function _getImageSets() {
        $path = self::$THEME_PATH . '/images';
        $folders = $this->_getFileList($path, FALSE, TRUE);

        $imageThemes = array();

        foreach($folders as $folder) {

            $infoFile = "$path/$folder/info.xml";
            
            if (file_exists($infoFile)) {
                $xml = simplexml_load_file($infoFile);

                $images = array();
                $files = $this->_getFileList("$path/$folder/", FALSE);
                $storage = new StorageServer();
                foreach ($files as $file) {
                    if (is_file("$path/$folder/$file") && preg_match('/(.jpg|.jpge|.png|.gif)$/', $file)) {
                        $imageBasePath = self::$THEME_BASE_PATH . "/images/$folder/$file";
                        $imageBaseURL = $storage->getBaseURL() . "/cms/apps/" . $imageBasePath;
                        $fileName = str_replace('.', '_', $file);
                        $fileName = str_replace('@', '_', $fileName);
                        $images[$fileName] = array('name'=>$file, 'path'=>$imageBasePath,'url'=>$imageBaseURL);
                    }
                }

                $privewName = $this->_createPreviewForImageSet($images, "$path/$folder/");

                $imageThemeInfo = get_object_vars($xml);
                $imageThemeInfo['images'] = $images;
                $imageThemeInfo['preview'] = $storage->getBaseURL() . "/cms/apps/" . self::$THEME_BASE_PATH . "/images/$folder/$privewName";
                $imageThemes[$imageThemeInfo['name']] = $imageThemeInfo;
            }
        }

        return $imageThemes;
    }

    private function _createPreviewForImageSet($imageSet, $path) {
        $newImage = imagecreatetruecolor(67,100);
        $outImageName = "preview_cache_awern342fwsfpokl234mdsae";
        $imageOutPath = "$path/$outImageName";
        
        if (isset($imageSet['navigationbar_png'])) {
            $image = $this->_createImage('../../../'.$imageSet['navigationbar_png']['path']);
            imagecopy($newImage, $image, 0, 0, 0, 0, 67, 44);
            imagedestroy($image);
        }
        if (isset($imageSet['tab-bar-background_png'])) {
            $image = $this->_createImage('../../../'.$imageSet['tab-bar-background_png']['path']);
            imagecopy($newImage, $image, 0, 51, 0, 0, 67, 49);
            //imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h)
            imagedestroy($image);
        }

        imagejpeg($newImage, $imageOutPath, 90);
        imagedestroy($newImage);
        return $outImageName;
    }

    private function _createImage($path) {
        $info = getimagesize($path);
        
        switch ( $info[2] ) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($path);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($path);
                break;
            default:
                return false;
        }

        return $image;
    }

    private function _getFileList($path, $appendPath=TRUE, $folders=FALSE) {
        $dirList = array();
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if ($folders) {
                        if(is_dir($path . '/' . $file))
                            $dirList[] = $appendPath ? $path . '/' . $file : $file;
                    } else {
                        $dirList[] = $appendPath ? $path . '/' . $file : $file;
                    }
                }
            }
            
            closedir($handle);
        }

        return $dirList;
    }

    public function saveTheme($data) {
        $storageServer = new StorageServer('../../../../../static_files');

        $data = stripslashes($data);
        $path = $storageServer->getPathForCatogory('data', 'saved_themes') . 'theme.txt';
        file_put_contents($path, $data);
    }

    public function deleteSavedTheme() {
        $storageServer = new StorageServer('../../../../../static_files');
        $path = $storageServer->getPathForCatogory('data', 'saved_themes') . 'theme.txt';
        unlink($path);
    }

    public function loadSavedTheme() {
        $storageServer = new StorageServer('../../../../../static_files');

        $path = $storageServer->getPathForCatogory('data', 'saved_themes') . 'theme.txt';

        if (file_exists($path)) {
            return file_get_contents($path);
        } else {
            return FALSE;
        }
    }

    public function finishTheme($theme) {
        $storedTheme = $this->getThemeDetails();

        $storage = new StorageServer('../../../../../static_files');
        $path = $storage->getPathForCatogory('data', 'themes');
        $prePath = $path . 'pre_build/';

        if (!file_exists($prePath)) {
            mkdir($prePath);
        }

        //Clear the folder
        $files = $this->_getFileList($prePath);
        foreach($files as $file) {
            @unlink($file);
        }

        $preTheme = new stdClass();
        $preTheme->images = array();
        

        $preTheme->images['imageSet'] = array();
        $images = $theme->imageSet->images;
        foreach ($images as $key=>$image) {
            if (file_exists('../../../'.$image->path)) {
                $preTheme->images['imageSet'][$key] = $image->name;
                $image2x = $this->_create1xImages('../../../'.$image->path, $prePath, $image->name);
                if ($image2x!==FALSE) {
                    $key1x = str_replace('.', '_', $image2x);
                    $preTheme->images['imageSet'][$key1x] = $image2x;
                    $image->name = $image2x;
                }
                copy('../../../'.$image->path, $prePath . $image->name);
            }
        }

        foreach ($theme->iconSet as $type=>$name) {
            $preTheme->images[$type] = array();
            
            $images = $storedTheme->iconSets[$type][$name]['images'];
            foreach ($images as $key=>$image) {
                if (file_exists('../../../'.$image['path'])) {
                    copy('../../../'.$image['path'], $prePath . $image['name']);
                    $preTheme->images[$type][$key] = $image['name'];
                }
            }
        }

        $preTheme->images['special'] = array();

        $specialIconName = 'chevron.png';
        $this->_createSpecialIcons($prePath,$specialIconName,$theme->colorSet->colors);
        $preTheme->images['special'][str_replace('.', '_', $specialIconName)] = $specialIconName;

        $specialIconName = 'reply_back.png';
        $this->_createSpecialIcons($prePath,$specialIconName,$theme->colorSet->colors);
        $preTheme->images['special'][str_replace('.', '_', $specialIconName)] = $specialIconName;

        $colors = $theme->colorSet->colors;

        $preTheme->colors = array();
        foreach ($colors as $key=>$color) {
            $preTheme->colors[$key]=$color;
        }

        $data = serialize($preTheme);
        file_put_contents($prePath . 'info.srl', $data);
    }

    private function _createSpecialIcons($prePath, $name, $colors) {
        switch ($name) {
            case 'chevron.png':
                $image = imagecreatetruecolor(10, 11);

                $black = imagecolorallocate ($image, 0, 0, 0);
                imagecolortransparent($image, $black);

                list($r, $g, $b) = $this->html2rgb($colors->table_text_color,FALSE);
                $chevColor = imagecolorallocate ($image, $r, $g, $b);

                imagefill($image, 0, 0, $chevColor);
                
                $maskImage = imagecreatefrompng('../../../images/themes/chevron.png');
                imagealphablending($maskImage, false);
                imagesavealpha($maskImage, true);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                $this->_imageAlphaMask($image, $maskImage);

                imagepng($image, $prePath.$name, 9);
                imagedestroy($image);
                imagedestroy($maskImage);
                break;

            case 'reply_back.png':
                $image = imagecreatetruecolor(320, 112);

                $black = imagecolorallocate ($image, 0, 0, 0);
                imagecolortransparent($image, $black);

                list($r, $g, $b) = $this->html2rgb($colors->banner_background_color,FALSE);
                $chevColor = imagecolorallocate ($image, $r, $g, $b);
                imagefill($image, 0, 0, $chevColor);

                list($r, $g, $b) = $this->html2rgb($colors->banner_text_color,FALSE);
                $lineColor = imagecolorallocate ($image, $r, $g, $b);
                imageline($image, 0, 2, 320, 2, $lineColor);
                imageline($image, 0, 97, 280, 97, $lineColor);
                imageline($image, 301, 97, 320, 97, $lineColor);

                $maskImage = imagecreatefrompng('../../../images/themes/reply_back.png');
                imagealphablending($maskImage, false);
                imagesavealpha($maskImage, true);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                $this->_imageAlphaMask($image, $maskImage);

                imagepng($image, $prePath.$name, 9);
                imagedestroy($image);
                imagedestroy($maskImage);
                break;

            default:
                break;
        }
    }

    private function _imageAlphaMask( &$picture, $mask ) {
        // Get sizes and set up new picture
        $xSize = imagesx( $picture );
        $ySize = imagesy( $picture );
        $newPicture = imagecreatetruecolor( $xSize, $ySize );
        imagesavealpha( $newPicture, true );
        imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

        // Resize mask if necessary
        if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
            $tempPic = imagecreatetruecolor( $xSize, $ySize );
            imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
            imagedestroy( $mask );
            $mask = $tempPic;
        }

        // Perform pixel-based alpha map application
        for( $x = 0; $x < $xSize; $x++ ) {
            for( $y = 0; $y < $ySize; $y++ ) {
                $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
                $alpha = floor( $alpha[ 'alpha' ]);
                $color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
                imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
            }
        }

        // Copy back to original picture
        imagedestroy( $picture );
        $picture = $newPicture;
    }

    public function createAndDownloadTheme($userId) {
        $storedTheme = $this->getThemeDetails();

        $storage = new StorageServer('../../../../../static_files', $userId);
        $path = $storage->getPathForCatogory('data', 'themes');

        $prePath = $path . 'pre_build/';
        $themeFile = $prePath . 'info.srl';

        $buildPath = $path . '/theme_build/';
        if (!file_exists($buildPath)) {
            mkdir($buildPath);
        }

        $buildPath = $buildPath . 'Theme.x/';
        if (!file_exists($buildPath)) {
            mkdir($buildPath);
        }

        //Clean the folder
        $files = $this->_getFileList($buildPath);
        foreach($files as $file) {
            @unlink($file);
        }

        $buildPathImages = $buildPath . 'images/';
        if (!file_exists($buildPathImages)) {
            mkdir($buildPathImages);
        }

        //Clean the folder
        $files = $this->_getFileList($buildPathImages);
        foreach($files as $file) {
            @unlink($file);
        }

        if (!file_exists($prePath)) {
            return FALSE;
        }
        
        $theme = unserialize(file_get_contents($themeFile));

        foreach ($theme->images as $images) {
            foreach ($images as $image) {
                @copy($prePath.$image, $buildPathImages.$image);
            }
        }

        unset($theme->colors['name']);
        $plist = $this->_createPListFile($theme->colors);
        
        file_put_contents($buildPath.'Theme.plist', $plist);


        $this->createZip($buildPath);
        
        //Clean the folder
        $files = $this->_getFileList($buildPathImages);
        foreach($files as $file) {
            @unlink($file);
        }

        //Clean the folder
        $files = $this->_getFileList($buildPath);
        foreach($files as $file) {
            @unlink($file);
        }

        @rmdir("$buildPath/images");
    }

    function createZip($path) {
        $zip = new ZipArchive();
        $filename = "$path/zip.zip";

        $files = $this->_getFileList($path, TRUE, TRUE);

        if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
            return false;
        } else {
            $zip->addEmptyDir('Theme.bundle');
            $zip->addFile("$path/Theme.plist","Theme.bundle/Theme.plist");

            $zip->addEmptyDir('Theme.bundle/images/');
            
            $files = $this->_getFileList("$path/images/", FALSE, FALSE);
            foreach($files as $file) {
                $zip->addFile("$path/images/$file","Theme.bundle/images/$file");
            }
            
            $zip->close();
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="Theme.zip"');
        echo file_get_contents($filename);

        return true;
    }

    private function _createPListFile($colors) {
        $keyMap = array (
            'popup_background_color'=>'Popup back color',
            'navbar_tint_color'=>'NavBar tint color',
            'foreground_color'=>'Foreground color',
            'mini_player_foreground_color'=>'Miniplayer foreground color',
            'background_color'=>'Background color',
            'table_cell_odd_color'=>'Table Cell back color 0',
            'table_cell_even_color'=>'Table Cell back color 1',
            'table_seperator_color'=>'Table Seperate color',
            'table_background_color'=>'Table background color',
            'table_text_color'=>'Table text color',
            'banner_background_color'=>'Banner background color',
            'banner_text_color'=>'Banner text color',
            'tab_text_color'=>'Tab text color'
        );

        $str =  '<?xml version="1.0" encoding="UTF-8"?>'."\n".
                '<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">'."\n".
                '<plist version="1.0">'."\n".
                "<dict>\n";

        foreach ($colors as $key=>$color) {
            $colorStr = "\t<key>".$keyMap[$key]."</key>\n";
            $colorStr .= $this->_convertColorToDicString($color);
            $str .= $colorStr;
        }

        $str .= "</dict>\n</plist>";

        return $str;
    }

    private function _convertColorToDicString($color) {
        list($r,$g,$b) = $this->html2rgb($color);

        $str = "\t<dict>\n".
		"\t\t<key>red</key>\n".
		"\t\t<real>$r</real>\n".
		"\t\t<key>green</key>\n".
		"\t\t<real>$g</real>\n".
		"\t\t<key>blue</key>\n".
		"\t\t<real>$b</real>\n".
		"\t\t<key>alpha</key>\n".
		"\t\t<real>1</real>\n".
                "\t</dict>\n";
        
        return $str;
    }

    private function html2rgb($color, $flat=TRUE) {
        if ($color[0] == '#')
            $color = substr($color, 1);

        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0].$color[1],
                                     $color[2].$color[3],
                                     $color[4].$color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else
            return array(0, 0, 0);

        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

        if ($flat) {
            $r=$r/255; $g=$g/255; $b=$b/255;
        }

        return array($r, $g, $b);
    }

    private function _create1xImages($imagePath, $path, $imageName) {
        
        $image = $this->_createImage($imagePath);

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width>=638 && $width<=642) {
            $newImage = imagecreatetruecolor($width/2, $height/2);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width/2, $height/2, $width, $height);
            imagepng($newImage, $path.$imageName, 9);
            imagedestroy($image);
            imagedestroy($newImage);
            $imageName = str_replace('.', '@2x.', $imageName);
            return $imageName;
        } else {
            imagedestroy($image);
            return FALSE;
        }
    }
}
?>
