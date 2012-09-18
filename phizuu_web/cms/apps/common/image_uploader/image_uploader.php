<?php
require_once "../../config/app_key_values.php";
require_once "../../model/app_builder/AppWizard.php";
@session_start();
include_once "../../view/user/home_images/ThumbNail.php";
require_once "../../database/Dao.php";
require_once "../../config/config.php";


$appWizard = new AppWizard($_SESSION['user_id']);
$popArray = array();
$popArray['packageInfo'] = $appWizard->getPackageInfo();
$homeImageCount = $popArray['packageInfo']['home_screen_images'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head profile="http://gmpg.org/xfn/11">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>AJAX File Upload - Web Developer Plus Demos</title>
        <!--image uploader starts-->
        <script type="text/javascript" src="jquery-1.3.2.js" ></script>
        <script type="text/javascript" src="ajaxupload.3.5.js" ></script>
        <!--image uploader end-->
        <!--image crop starts-->
        <link type="text/css" href="../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />


        <script type="text/javascript" src="../../js/crop/jquery-pack.js"></script>
        <script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-1.4.min.js"></script>
        <script type="text/javascript" src="../../js/crop/jquery.imgareaselect-0.3.min.js"></script>

        <script type="text/javascript" src="../../js/crop/imgareaselect-default.css"></script>
        <!--image crop ends-->
        <link rel="stylesheet" type="text/css" href="styles.css" />
        <link rel="stylesheet" type="text/css" href="../../css/styles.css " />
        
         
        <?php
//get no of recoeds
//$homeImageController = new home_image_controller();
//$homeImageArr = $homeImageController->getAllHomeImagesByAppId($_SESSION['app_id']);
//if(isset($homeImageArr)){
//    $noofRecodes = count($homeImageArr);         
//}

        $folderName = $_SESSION['user_id'];
        $upload_dir = "../../../../static_files/$folderName/images/home_images/";
        $upload_dir_temp = "../../../../static_files/$folderName/images/temp_images/";
        $upload_dir_thumb = "../../../../static_files/$folderName/images/home_thumb_images/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        if (!is_dir($upload_dir_temp)) {
            mkdir($upload_dir_temp, 0777, true);
        }
        if (!is_dir($upload_dir_thumb)) {
            mkdir($upload_dir_thumb, 0777, true);
        }

//if ($_SERVER['SERVER_NAME'] == 'localhost') {
//    $callbackURL = "http://localhost/phizuu_web/static_files/$folderName/images/home_images/";
//    $callbackThumbURL = "http://localhost/phizuu_web/static_files/$folderName/images/home_thumb_images/";
//} else {
//    $callbackURL = "http://phizuu.com/static_files/$folderName/images/home_images/";
//    $callbackThumbURL = "http://phizuu.com/static_files/$folderName/images/home_thumb_images/";
//}
        $domain = $_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
            $callbackURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL . "static_files/$folderName/images/home_images/";
            $callbackThumbURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL . "static_files/$folderName/images/home_thumb_images/";
        } elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
            $callbackURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL . "static_files/$folderName/images/home_images/";
            $callbackThumbURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL . "static_files/$folderName/images/home_thumb_images/";
        } else {
            $callbackURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL . "static_files/$folderName/images/home_images/";
            $callbackThumbURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL . "static_files/$folderName/images/home_thumb_images/";
        }

//get no of recoed 
        $dao = new Dao();
        $user_id = $_SESSION['user_id'];
        $sql = "select id as count from `home_image`  WHERE `user_id`= $user_id";
        $res = $dao->query($sql);
        $noofRecodes = mysql_num_rows($res);


        if (isset($_POST['upload'])) {



            $showStatus = 1;
            list($width, $height, $type, $attr) = @getimagesize($_FILES['fileUpload']['tmp_name']);

            $error = FALSE;

            //echo $noOfRec = $homeImageController->getNoOfRerodes();


            if ($_FILES['fileUpload']['size'] > 1048576) {
                $message = ("Your picture size is too large (Max 1MB) ");
            } elseif ($_FILES['fileUpload']['size'] == 0) {
                $message = ("Upload file can't be empty or file size is too large!");
            } elseif
            (!$type || $type == 4 || $type == 5) {
                $message = ('The specified file is not a picture! (Only .jpg, .png, .gif )');
            } elseif ($width > 1200 || $height > 1200) {
                $message = ('exceeded image dimension limits (Max 1200 X 1200)');
            } elseif ($width < 640 || $height < 734) {
                $message = ('Too small image dimension size (Min 640 X 734)');
            } elseif ($noofRecodes >= $homeImageCount) {
                $message = ("You can upload only $homeImageCount images ");
            } else {

                $time = date("dmyHis", time());
                $fileName = $time . '@2x' . GetExtension($_FILES["fileUpload"]["name"]);
                move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $upload_dir_temp . $fileName);
                $uploadStatus = true;
            }
        }

        function GetExtension($extFile) {
            $extention = substr($extFile, strrpos($extFile, '.'));
            return $extention;
        }

        function GetFileName($path) {
            $path_parts = pathinfo($path);

            $exe = $path_parts['extension'];
            $file = $path_parts['filename'];
            return $imageName = $file . '.' . $exe;
        }

        if (isset($_POST['upload_thumbnail'])) {
            $fileName = $_REQUEST['fileName'];
        }
        if (isset($uploadStatus) || isset($_POST['upload_thumbnail'])) {
//**********************Crop***************************************************
//$fileName = $_FILES["fileUpload"]["name"];
//$upload_dir = "upload_pic"; 				// The directory for the images to be saved in
            $upload_path = $upload_dir_temp . "";    // The path to where the image will be saved
            $large_image_name = $fileName;   // New name of the large image
            $thumb_image_name = $fileName;  // New name of the thumbnail image
            $max_file = "1048576";       // Approx 1MB
            $max_width = "100";      // Max width allowed for the large image
            $thumb_width = "640";
            $thumb_height = "734";     // Height of thumbnail image
//Small thumb details
            $small_thumb_name = $fileName;
            $small_thumb_width = "50";
            $small_thumb_height = "100";
//$small_thumb_image_name = $upload_dir_thumb.$fileName;

            $exe = "";

//Image functions
//You do not need to alter these functions
            function resizeImage($image, $width, $height, $scale) {
                $newImageWidth = ceil($width * $scale);
                $newImageHeight = ceil($height * $scale);
                $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
                $source = imagecreatefromjpeg($image);
                imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
                imagejpeg($newImage, $image, 90);
                chmod($image, 0777);
                return $image;
            }

            $exe = GetExtension($fileName);

            function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
                $newImageWidth = ceil($width * $scale);
                $newImageHeight = ceil($height * $scale);
                $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
                $exe = GetExtension($image);
                if ($exe == ".png") {
                    $source = imagecreatefrompng($image);
                } else if ($exe == ".gif") {
                    $source = imagecreatefromgif($image);
                } else if ($exe == ".jpeg" || $exe == ".jpg") {

                    $source = imagecreatefromjpeg($image);
                }

                imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
                imagejpeg($newImage, $thumb_image_name, 90);
                chmod($thumb_image_name, 0777);
                return $thumb_image_name;
            }

//You do not need to alter these functions
            function getHeight($image) {
                $sizes = getimagesize($image);
                $height = $sizes[1];
                return $height;
            }

//You do not need to alter these functions
            function getWidth($image) {
                $sizes = getimagesize($image);
                $width = $sizes[0];
                return $width;
            }

//Image Locations
            $large_image_location = $upload_path . $large_image_name;
            $thumb_image_location = $upload_path . $thumb_image_name;

//Create the upload directory with the right permissions if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777);
                chmod($upload_dir, 0777);
            }

//Check to see if any images with the same names already exist
            $large_photo_exists = "<img src=\"" . $upload_path . $large_image_name . "\" alt=\"Large Image\"/>";
        }
//Save thumb image

        if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists) > 0) {
            //Get the new coordinates to crop the image.
            $x1 = $_POST["x1"];
            $y1 = $_POST["y1"];
            $x2 = $_POST["x2"];
            $y2 = $_POST["y2"];
            $w = $_POST["w"];
            $h = $_POST["h"];
            //Scale the image to the thumb_width set above a
            $scale = $thumb_width / $w;
            $time = date("dmyHis", time());
            $imageName = "thumb" . $time . $exe;
            if (isset($_SESSION['$fileName'])) {
                $thumb_image_location = $upload_dir . $imageName;
            }

            $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location, $w, $h, $x1, $y1, $scale);




            if (!@copy($upload_path . $fileName, $upload_dir . $fileName)) {
                return FALSE;
            } else {
                copy($upload_dir . $fileName, $upload_dir_thumb . $fileName);
                $thumbNail = new ThumbNail();
                $thumbNail->create_abs_image($fileName, $fileName, 64, 73.4, $upload_dir_thumb);
                unlink($upload_path . $fileName);
            }



            //assing  data to array
            $home_image_arr = array(
                "user_id" => $_SESSION['user_id'],
                "image_url" => $callbackURL . $fileName,
                "image_url_thumb" => $callbackThumbURL . $fileName,
                "hot_spot_type" => 0,
                "module_name" => "",
                "link_url" => "",
                "top" => 015,
                "left" => 460,
                "width" => 100,
                "height" => 50,
                "order_no" => $noofRecodes
            );


            //add to database
            $sql = "insert into `home_image` (`user_id`,`image_url`,`image_url_thumb`,`hot_spot_type`,`module_name`,`link_url`,`top`,`left`,`width`,`height`,`order_no`) VALUES ('" . addslashes($home_image_arr['user_id']) . "','" . addslashes($home_image_arr['image_url']) . "','" . addslashes($home_image_arr['image_url_thumb']) . "','" . $home_image_arr['hot_spot_type'] . "','" . $home_image_arr['module_name'] . "','" . $home_image_arr['link_url'] . "','" . $home_image_arr['top'] . "','" . $home_image_arr['left'] . "','" . $home_image_arr['width'] . "','" . $home_image_arr['height'] . "','" . $home_image_arr['order_no'] . "')";
            $dao->query($sql);
            //set default image
            if ($noofRecodes == 0) {
                $defaultImagePath = "../../application_dirs/$user_id/";
                if ($exe == '.jpg') {
                    $imageObject = imagecreatefromjpeg($upload_dir . $fileName);
                    imagejpeg($imageObject, $defaultImagePath . 'HomeImage1@2x.jpg');
                } elseif ($exe == '.png') {
                    $imageObject = imagecreatefromjpeg($upload_dir . $fileName);
                    imagepng($imageObject, $defaultImagePath . 'HomeImage1@2x.jpg');
                } elseif ($exe == '.gif') {
                    $imageObject = imagecreatefromjpeg($upload_dir . $fileName);
                    imagegif($imageObject, $defaultImagePath . 'HomeImage1@2x.jpg');
                }
                $dao = new Dao();
                $user_id = $_SESSION['user_id'];
                $sql = "update `home_image` set default_image=1 WHERE `user_id`= $user_id";
                $dao->query($sql);
            }

            echo '<script type="text/javascript" >$(function(){alert("Image uploaded successfully")});</script>';
        }
        ?>    

<!--<script type="text/javascript" >
        $(function(){
                var btnUpload=$('#upload');
                var status=$('#status');
                new AjaxUpload(btnUpload, {
                        action: 'upload-file.php',
                        name: 'uploadfile',
                        onSubmit: function(file, ext){
                                 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                    // extension is not allowed 
                                        status.text('Only JPG, PNG or GIF files are allowed');
                                        return false;
                                }
                                status.text('Uploading...');
                        },
                        onComplete: function(file, response){
                                //On completion clear the status
                                status.text('');
                                //Add uploaded file to list
                                document.getElementById('cropjs').style.display = "block";
                                if(response==="success"){
                                        $('<li></li>').appendTo('#files').html('<img src="<?php echo $upload_dir_temp; ?>'+file+'"  id="thumbnail" alt="Create Thumbnail"  /><div style=" position:relative; overflow:hidden; float: left; width:640px; height:734px;display: block" > <img src="<?php echo $upload_dir_temp; ?>'+file+'" style="position: relative; width:<?php echo $thumb_width; ?>px; height:<?php echo $thumb_height; ?>px;"  alt="Thumbnail Preview"/></div>').addClass('success');
                                } else{
                                        $('<li></li>').appendTo('#files').text(file).addClass('error');
                                }
                        }
                });
                
        });
</script>-->


    </head>
    <body style="background-color: #F4F3F1">
        <div id="mainbody" >
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <div id="UploadYourOwnImageInput" style="padding: 10px"><input type="file" name="fileUpload" id="fileUpload"/>
                    <input type="submit"  name="upload" value="Upload" onclick="submitform()" class="button"/></div>
               


            </form>
            <div id="divStatus" class="tahoma_12_blue" style="padding-bottom: 5px;padding-left: 10px;color:#1e1f1f">Select files less than 1MB</div>
            <div id="divStatus" class="tahoma_12_blue" style="padding-bottom: 5px;padding-left: 10px;color: red">
                <?php // }}  ?>


                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>
            </div>


            <?php
//Only display the javacript if an image has been uploaded
            if (isset($uploadStatus)) {
                if (strlen($large_photo_exists) > 0) {
                    $current_large_image_width = getWidth($large_image_location);
                    $current_large_image_height = getHeight($large_image_location);
                    ?>
                    <script type="text/javascript">
                        
                        function clossWindow(){
                            alert("hi");
                        }
                        function preview(img, selection) { 
                                
                            var scaleX = <?php echo $thumb_width; ?> / selection.width; 
                            var scaleY = <?php echo $thumb_height; ?> / selection.height; 
                        	
                            $('#thumbnail + div > img').css({ 
                                width: Math.round(scaleX * <?php echo $current_large_image_width; ?>) + 'px', 
                                height: Math.round(scaleY * <?php echo $current_large_image_height; ?>) + 'px',
                                marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
                                marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
                            });
                            $('#x1').val(selection.x1);
                            $('#y1').val(selection.y1);
                            $('#x2').val(selection.x2);
                            $('#y2').val(selection.y2);
                            $('#w').val(selection.width);
                            $('#h').val(selection.height);
                        } 
                                
                        $(document).ready(function () { 
                            $('#thumbnail').click(function() {
                                var x1 = $('#x1').val();
                                var y1 = $('#y1').val();
                                var x2 = $('#x2').val();
                                var y2 = $('#y2').val();
                                var w = $('#w').val();
                                var h = $('#h').val();
                                if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
                                }else{
                                    document.getElementById("val").value="true";
                                }
                            });
                        }); 
                        
                        $(window).load(function () { 
                            $('#thumbnail').imgAreaSelect({ x1: 0,y1: 0,x2: 640,y2: 734,minWidth: 640, minHeight: 734, aspectRatio: '640:734', onSelectChange: preview,handles: true }); 
                        });

                    </script>
                    <?php
                }
                ?>

                <div style="clear: both; padding-top: 10px">
                    <form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" >
                        <input type="hidden" name="x1" value="0" id="x1" />
                        <input type="hidden" name="y1" value="0" id="y1" />
                        <input type="hidden" name="x2" value="640" id="x2" />
                        <input type="hidden" name="y2" value="734" id="y2" />
                        <input type="hidden" name="w" value="640" id="w" />
                        <input type="hidden" name="h" value="734" id="h" />
                        <input type="hidden" name="val" id="val" />
                        <input type="hidden" name="fileName" id="fileName" value="<?php echo $fileName ?>" />
                        <input type="submit" name="upload_thumbnail" value="Crop Image" id="save_thumb" class="button" />
                    </form>
                    <img src="<?php echo $upload_dir_temp . $fileName; ?>" id="thumbnail" alt="Create Thumbnail" style="float: left;"/>
                    <div style=" position:relative; overflow:hidden; float: left; width:640px; height:734px;display: none" >
                        <img src="<?php echo $upload_dir_temp . $fileName; ?>" style="position: relative; width:<?php echo $thumb_width; ?>px; height:<?php echo $thumb_height; ?>px;"  alt="Thumbnail Preview"/>
                    </div>


                </div>     
            <?php } ?>
        </div>
    </body>