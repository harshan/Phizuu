<html>
    <head>
        <style type="text/css">
            body {
                font-family: Tahoma;
                font-size: 12px;
                color: #1e1f1f;
}
        </style>
    </head>
    <body onload="parent.loadedIFrame();" style="margin: 0px">
        <form action="../../../../controller/photo_all_controller.php?action=upload_temp_image" id="uploadForm" enctype="multipart/form-data" method="POST">
            <input type="file" name="image" id="fileImage" style="width: 250px"/>
            <br/>
            The uploaded image will be cropped to fit 256x256 pixel area. After uploading image you will be able to see the preview.
        </form>
    </body>
</html>
