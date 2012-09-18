<?php

echo $path = $_REQUEST['path'];

$showStatus = 1;
    list($width, $height, $type, $attr) = @getimagesize($_FILES[$path]['tmp_name']);
//    echo "Image width " .$width;
//    echo "<BR>";
//    echo "Image height " .$height;
//    echo "<BR>";
//    echo "Image type " .$type;
//    echo "<BR>";
//    echo "Attribute " .$attr;

    $error = FALSE;

    if ($_FILES[$path]['size'] > 2097152) {
        $message = ("Your picture size is too large (Max 2MB) ");
    } elseif ($_FILES[$path]['size'] == 0) {
        $message = ("Upload file can't be empty or file is too large!");
    } elseif
    (!$type || $type == 4 || $type == 5) {
        $message = ('The specified file is not a picture! (Only .jpg, .png, .gif )');
    } else {

        $time = date("dmyHis", time());
        $fileName = $time . $_FILES[$path]["name"];
        move_uploaded_file($_FILES[$path]["tmp_name"], "../../../../../static_files/6/images/home_images/" . $fileName );
    }
?>
