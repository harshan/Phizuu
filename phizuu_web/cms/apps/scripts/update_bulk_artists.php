<?php
/* This is a script to parse data in the log file to the database */
require_once ('../config/config.php');
require_once ('../database/Dao.php');
require_once ('../model/line_up/LineUp.php');


if(!isset($_POST['submit'])) {
?>
<html>
<body>

<form action="update_bulk_artists.php" method="post" enctype="multipart/form-data">
<label for="file">CSV File:</label>
<input type="file" name="file" id="file" />
<input type="submit" name="submit" value="Upload" />
</form>

</body>
</html>
<?php
exit;
}

if ($_FILES["file"]["error"] > 0) {
    echo "No file uploaded or error while uploading! <br />";
} elseif ($_FILES["file"]["type"] == 'application/vnd.ms-excel' || $_FILES["file"]["type"] == 'text/csv' || $_FILES["file"]["type"] == 'text/comma-separated-values') {
    $name = $_FILES["file"]["name"];
    $file_path = $_FILES["file"]["tmp_name"];

    $lineUp = new LineUp();
    $row = 1;

    $arr = array();
    if (($handle = fopen($file_path, "r")) !== FALSE) {
	while (($data = fgetcsv($handle)) !== FALSE) {
	    if ($row > 0) {
		if (isset($data[0]) &&  $data[0] != '') {
		    $arr['artist_id'] = $data[0];
		    $arr['artist_name'] = $data[1];
		    $arr['artist_image'] = '';//2
		    $arr['artist_web_url'] = isset($data[3])?$data[3]:'';
		    $arr['artist_facebook'] = isset($data[4])?$data[4]:'';
		    $arr['artist_twitter'] = isset($data[5])?$data[5]:'';
		    $arr['artist_biography'] = isset($data[6])?$data[6]:'';
		    $arr['artist_image_logo'] = '';//7
		    $arr['artist_video'] = isset($data[8])?$data[8]:'';
		    $arr['artist_music'] = isset($data[9])?$data[9]:'';
		    $arr['artist_site_img'] = isset($data[10])?$data[10]:'';
		    $arr['artist_site_logo'] = isset($data[11])?$data[11]:'';

		    if ($lineUp->updateArtist($arr, 0))
			echo "Updated: ".$arr['artist_name'].'<br/>';
		    else
			echo "Error occured while creating: ".$arr['artistName'].'<br/>';
		}
	    }
	    $row++;
	}
	fclose($handle);
    }
} else {
    echo "Invalid file type";
}
?>