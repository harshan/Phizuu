<?php
/**
 * Description of LineUp
 *
 * @author Dhanushka
 */

class LineUp {
    function createNewArtist($arr, $userId, $appId) {
        $dao = new Dao();

        $arr['artistName'] = mysql_escape_string($arr['artistName']);

	$arr['biography'] = preg_replace('/[^(\x20-\x7F)]*/','', $arr['biography']); // Remove UTF characters
        $arr['biography'] = mysql_escape_string($arr['biography']);

	$artist_web_url = mysql_escape_string($arr['artist_web_url']);
	$artist_facebook = mysql_escape_string($arr['artist_facebook']);
	$artist_twitter = mysql_escape_string($arr['artist_twitter']);
	$artist_image_logo = mysql_escape_string($arr['artist_image_logo']);
	$artist_video = mysql_escape_string($arr['artist_video']);
	$artist_music = mysql_escape_string($arr['artist_music']);
	$artist_site_image = mysql_escape_string($arr['artist_site_image']);
	$artist_site_logo = mysql_escape_string($arr['artist_site_logo']);

        $sql = "INSERT INTO line_up_artists (artist_name, biography, image_url, artist_web_url, artist_facebook, artist_twitter,".
		"artist_image_logo, artist_video, artist_music, site_img, site_logo) ".
               "VALUES ('{$arr['artistName']}', '{$arr['biography']}', '{$arr['imageUrl']}', '{$artist_web_url}', '{$artist_facebook}'," .
	       "'{$artist_twitter}', '{$artist_image_logo}', '{$artist_video}', '{$artist_music}', '{$artist_site_image}', '{$artist_site_logo}')";
	       
        try{
            $dao->query($sql);
	    $lastId = mysql_insert_id();
	    
	    /*if ($arr['imageUrl'] == ""){
		$storage = new StorageServer('../../../../../static_files', $userId);
		$path = $storage->getPathForCatogory('images', 'line_up_images') . 'temp_file_replace_QFHEHFNELLK3432.jpg';
		$arr['imageUrl']  = "../../static_files/$appId/images/line_up_images/temp_file_replace_QFHEHFNELLK3432.jpg";
		copy ('../../../images/lineup_no_image.jpg', $path);
	    }*/

            
            $fileName = $lastId . '.jpg';

	    $url = '';
	    if ($arr['imageUrl']!='') {
		$sourceFile = '../../../' . $arr['imageUrl'];
		$destFile = str_replace('temp_file_replace_QFHEHFNELLK3432', $lastId, $sourceFile);

		copy($sourceFile, $destFile);
		unlink($sourceFile);

		$storage = new StorageServer('', $userId);
		$url = $storage->getURLForPath('images', 'line_up_images', $fileName);
	    }

	    $logo_url = '';
	    if ($arr['artist_image_logo']!='') {
		$sourceFile = '../../../' . $arr['artist_image_logo'];
		$destFile = str_replace('temp_file_replace_QFHEHFNELLK3432', $lastId, $sourceFile);

		copy($sourceFile, $destFile);
		unlink($sourceFile);

		$storage = new StorageServer('', $userId);
		$logo_url = $storage->getURLForPath('images', 'line_up_artist_logo_images', $fileName);
	    }

            $sql = "UPDATE line_up_artists SET image_url = '$url', artist_image_logo = '$logo_url' WHERE id = $lastId";
            $dao->query($sql);
            return true;
        }
        catch (Exception $e){
            return false;
        }
    }

    function searchArtists($arr) {
        $dao = new Dao();
        
        $arr['searchText'] = mysql_escape_string($arr['searchText']);
        $pageNumber = $arr['pageNumber'];
        $rowsPerPage = $arr['rowsPerPage'];

        if (!$pageNumber) {
            $pageNumber = 1;
        }

        $start = (($pageNumber - 1) * $rowsPerPage);
        $limit = "LIMIT $start, $rowsPerPage";

        $sql = "SELECT * FROM line_up_artists WHERE artist_name LIKE '" . $arr['searchText']. "%' ORDER BY artist_name $limit";

        try{
           $artistArray =  $dao->toArray($sql);

           $sql = "SELECT id FROM line_up_artists WHERE artist_name LIKE '" . $arr['searchText']. "%'";
           $result = $dao->query($sql);
           $total = mysql_num_rows($result);

           return array($artistArray, $total);

        }catch (Exception $e){
           return array(false, 0);
        }
    }

    function deleteArtist($arr){
        $dao = new Dao();

        $sql = "DELETE FROM line_up_artists WHERE id = ".$arr['artistId'];
        
        try{
            $dao->query($sql);

	    $sql = "DELETE FROM line_up_shows WHERE artist_id = ".$arr['artistId'];
	    $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function getArtistInfo($arr){
        $dao = new Dao();
        $sql = "SELECT artist_name, biography, image_url, artist_web_url ,artist_facebook, artist_twitter, artist_image_logo,
	    artist_video, artist_music, site_img, site_logo FROM line_up_artists WHERE id = ".$arr['artistId'];
	
        try{
           $result = $dao->query($sql);
           $row = mysql_fetch_array($result);
           return $row;
        }catch (Exception $e){
            return false;
        }
    }

    function updateArtist($arr, $userId){
        $dao = new Dao();

        $sql = '';

	if (!isset($arr['artist_site_logo'])) { //In case not set, remove after CMS implementation
	    $arr['artist_site_logo'] = '';
	}

	if (!isset($arr['artist_site_img'])) { //In case not set, remove after CMS implementation
	    $arr['artist_site_img'] = '';
	}

        $arr['artist_id'] = mysql_escape_string($arr['artist_id']);
        $arr['artist_name'] = mysql_escape_string($arr['artist_name']);
	//$arr['artist_biography'] = preg_replace('/[^(\x20-\x7F)]*/','', $arr['artist_biography']); // Remove UTF characters
        $arr['artist_biography'] = mysql_escape_string($arr['artist_biography']);
	$optional1 = '';
	$optional2 = '';

	$lastId = $arr['artist_id'];
        $fileName = $lastId . '.jpg';

        if ($arr['artist_image'] != '') {
            $sourceFile = '../../../' . $arr['artist_image'];
            $destFile = str_replace('temp_file_replace_QFHEHFNELLK3432', $lastId, $sourceFile);

            copy($sourceFile, $destFile);
            unlink($sourceFile);

            $storage = new StorageServer('', $userId);
            $url = $storage->getURLForPath('images', 'line_up_images', $fileName);

	    $optional1 = ", image_url='$url'";
        }

	if ($arr['artist_image_logo'] != '') {
            $sourceFile = '../../../' . $arr['artist_image_logo'];
            $destFile = str_replace('temp_file_replace_QFHEHFNELLK3432', $lastId, $sourceFile);

            copy($sourceFile, $destFile);
            unlink($sourceFile);

            $storage = new StorageServer('', $userId);
            $url = $storage->getURLForPath('images', 'line_up_artist_logo_images', $fileName);

	    $optional2 = ", artist_image_logo='$url'";
        }

	$sql = "UPDATE line_up_artists SET artist_name='{$arr['artist_name']}', biography='{$arr['artist_biography']}', artist_web_url='{$arr['artist_web_url']}', artist_facebook='{$arr['artist_facebook']}', artist_twitter='{$arr['artist_twitter']}',".
	       "artist_video='{$arr['artist_video']}' , artist_music='{$arr['artist_music']}', site_img='{$arr['artist_site_image']}', site_logo='{$arr['artist_site_logo']}' $optional1 $optional2".
               " WHERE id = {$arr['artist_id']}";

        try{
	    $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function createFestivalDay($arr){
	$dao = new Dao();

        $festival_name = mysql_escape_string($arr['festival_name']);
        $festival_date = $arr['festival_date'];

        $sql = "INSERT INTO line_up_festival_days(festival_name, festival_date) ".
               "VALUES ('{$festival_name}', '{$festival_date}')";
	       
	try{
            $dao->query($sql);
	    $id = mysql_insert_id();
	    return $id;
	}catch(Exception $e){
	    return false;
	}
    }

    function deleteFestivalDay($arr){
	$dao = new Dao();
	$sql = "DELETE FROM line_up_festival_days WHERE id = ".$arr['festival_id'];

        try{
            $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function getFestivalDays(){
	$dao = new Dao();

	$sql = "SELECT id, festival_name, festival_date FROM line_up_festival_days";
	try{
	    $festival_days = $dao->toArray($sql, MYSQL_ASSOC);
	    return $festival_days;
	}catch (Exception $e){
            return false;
	 }
    }

    function updateFestivalDay($arr){
	$dao = new Dao();

        $festival_id = $arr['festival_id'];
        $festival_name = mysql_escape_string($arr['festival_name']);
        $festival_date = $arr['festival_date'];

	 $sql = "UPDATE line_up_festival_days SET festival_name='{$festival_name}', festival_date='{$festival_date}'".
                   " WHERE id = {$festival_id}";

	try{
	    $dao->query($sql);
	    return true;
        }catch (Exception $e){
            return false;
        }
    }

    function createNewStage($arr, $userId, $appId) {
        $dao = new Dao();

        $stage_name = mysql_escape_string($arr['stage_name']);

	if ($arr['stage_file_path'] == ""){
	    $storage = new StorageServer('../../../../../static_files', $userId);
            $path = $storage->getPathForCatogory('images', 'line_up_stage_images') . 'temp_file_replace_QFHEHFNELLK3432.jpg';
	    $arr['stage_file_path']  = "../../static_files/$appId/images/line_up_stage_images/temp_file_replace_QFHEHFNELLK3432.jpg";
	    copy ('../../../images/lineup_no_stage_image.jpg', $path);
	}

        $sql = "INSERT INTO line_up_stages (stage_name, image_url) ".
               "VALUES ('{$stage_name}', '{$arr['stage_file_path']}')";

        try{
            $dao->query($sql);

            $lastId = mysql_insert_id();
            $fileName = $lastId . '.jpg';

            $sourceFile = '../../../' . $arr['stage_file_path'];
            $destFile = str_replace('temp_file_replace_QFHEHFNELLK3432', $lastId, $sourceFile);

            copy($sourceFile, $destFile);
            unlink($sourceFile);

            $storage = new StorageServer('', $userId);
            $url = $storage->getURLForPath('images', 'line_up_stage_images', $fileName);

            $sql = "UPDATE line_up_stages SET image_url = '$url' WHERE id = $lastId";
            $dao->query($sql);
	    return array($lastId, $url) ;
        }
        catch (Exception $e){
            return array(false, false);
        }
    }

     function getStages(){
	$dao = new Dao();

	$sql = "SELECT id, stage_name, image_url FROM line_up_stages";
	try{
	    $stages = $dao->toArray($sql, MYSQL_ASSOC);
	    return $stages;
	}catch (Exception $e){
            return false;
	 }
    }

    function deleteStage($arr){
	$dao = new Dao();
	$sql = "DELETE FROM line_up_stages WHERE id = ".$arr['stage_id'];

        try{
            $dao->query($sql);

	    $sql = "DELETE FROM line_up_festival_day_stages WHERE stage_id = ".$arr['stage_id'];
	    $dao->query($sql);

	    $sql = "DELETE FROM line_up_shows WHERE stage_id = ".$arr['stage_id'];
	    $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function updateStage($arr, $userId){
        $dao = new Dao();

        $sql = '';

        $stage_id = mysql_escape_string($arr['stage_id']);
        $stage_name = mysql_escape_string($arr['stage_name']);
        
        if ($arr['stage_file_path'] != '') {
            $lastId = $arr['stage_id'];
            $fileName = $lastId . '.jpg';

            $sourceFile = '../../../' . $arr['stage_file_path'];
            $destFile = str_replace('temp_file_replace_QFHEHFNELLK3432', $lastId, $sourceFile);

            copy($sourceFile, $destFile);
            unlink($sourceFile);

            $storage = new StorageServer('', $userId);
            $url = $storage->getURLForPath('images', 'line_up_stage_images', $fileName);

            $sql = "UPDATE line_up_stages SET stage_name='{$stage_name}', image_url='$url'".
                   " WHERE id = {$stage_id}";

        } else {
            $sql = "UPDATE line_up_stages SET stage_name='{$stage_name}' WHERE id = {$stage_id}";
        }

        try{
	    $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function addFestivalDayToStage($arr){
	$dao = new Dao();

        $festival_day_id = $arr['festival_id'];

        $sql = "INSERT INTO line_up_festival_day_stages (festival_day_id, stage_id) ".
               "VALUES ('{$arr['festival_id']}', '{$arr['stage_id']}')";
	try{
	    $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function loadStagesId($arr){
	$dao = new Dao();

	$sql = "SELECT stage_id, order_index FROM line_up_festival_day_stages WHERE festival_day_id =".$arr['festival_id'];
	try{
	    $stages_id = $dao->toArray($sql, MYSQL_ASSOC);
	    return $stages_id;
	}catch (Exception $e){
            return false;
	}
    }

    function updateOrderOfStagesInDay($arr) {
	$dao = new Dao();
	$i = 0;
	foreach($arr['item_id_arr'] as $id) {
	    $index = $arr['item_id_index'][$i];
	    $sql = "UPDATE line_up_festival_day_stages SET order_index = '$index' WHERE festival_day_id = ".$arr['festival_id']." AND stage_id = $id";

	    try{
		$dao->query($sql);
	    } catch (Exception $e){
		return false;
	    }
	    $i++;
	}

	return true;
    }


    function deleteFestivalDayStage($arr){
	$dao = new Dao();
	$sql = "DELETE FROM line_up_festival_day_stages WHERE festival_day_id = ".$arr['festival_id']." AND stage_id = ".$arr['stage_id'];

        try{
	   
            $dao->query($sql);

	    // new
	    $sql = "DELETE FROM line_up_shows WHERE festival_day_id = ".$arr['festival_id']." AND stage_id = ".$arr['stage_id'];
	    $dao->query($sql);

            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function creatShow($arr){
	$dao = new Dao();

        $time= mysql_escape_string($arr['show_time']);
	$timeStr = "'$time'";
	
	$endTime= mysql_escape_string($arr['show_end_time']);
	$endTimeStr = "'$endTime'";

	if ($time == "")
	{
	    $timeStr = 'NULL';
	}
	
	if ($endTime == "")
	{
	    $endTimeStr = 'NULL';
	}

	// No duplicates check
	/*$sql = "SELECT stage_id FROM line_up_shows WHERE festival_day_id='{$arr['festival_id']}' AND stage_id = '{$arr['stage_id']}' AND artist_id = '{$arr['artist_id']}' AND time = '{$time}' ";
	try {
	    $res = $dao->query($sql);
	    if (mysql_num_rows($res)>0)
		return 1;
	} catch (Exception $e) {
	    return false;
	}*/

	$sql = "INSERT INTO line_up_shows(festival_day_id, stage_id, artist_id, time, end_time) ".
               "VALUES ('{$arr['festival_id']}', '{$arr['stage_id']}', '{$arr['artist_id']}', $timeStr, $endTimeStr)";

	try{
            $dao->query($sql);
	    $lastId = mysql_insert_id();
	    $sql = "SELECT image_url, artist_name FROM line_up_artists WHERE id = ".$arr['artist_id'];
            $result = $dao->toArray($sql, MYSQL_ASSOC);
	    $result[0]['show_id'] = $lastId;
	    return $result[0];
	}catch(Exception $e){
	    return false;
	}
    }

    function deleteShow($arr){
	$dao = new Dao();

	$show_id = $arr['show_id'];

	$sql = "DELETE FROM line_up_shows WHERE show_id = $show_id";

        try{
            $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function updateShow($arr){
	$dao = new Dao();

	$show_id = $arr['show_id'];
	$time_new = mysql_escape_string($arr['show_time_new']);

	$timeStr = "'$time_new'";
	if ($time_new == "")
	{
	    $timeStr = 'NULL';
	}

	//$sql = "UPDATE line_up_shows SET time = '$time_new' WHERE show_id = $show_id";
	$sql = "UPDATE line_up_shows SET time = $timeStr WHERE show_id = $show_id";

        try{
            $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }
    
    function updateShowEndTime($arr){
	$dao = new Dao();

	$show_id = $arr['show_id'];
	$show_end_time_new = mysql_escape_string($arr['show_end_time_new']);

	$timeStr = "'$show_end_time_new'";
	if ($show_end_time_new == "")
	{
	    $timeStr = 'NULL';
	}
	
	$sql = "UPDATE line_up_shows SET end_time = $timeStr WHERE show_id = $show_id";

        try{
            $dao->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    function loadShows($arr){
	$dao = new Dao();

	$festival_day_id = $arr['festival_id'];
	$stage_id = $arr['stage_id'];

	$sql = "SELECT image_url, artist_id, artist_name, time, end_time, show_id FROM line_up_shows LEFT JOIN line_up_artists ON line_up_shows.artist_id = line_up_artists.id  WHERE festival_day_id = ".$festival_day_id.
	" AND stage_id = ".$stage_id;
	try{
	    $results = $dao->toArray($sql, MYSQL_ASSOC);
	    return $results;
	}catch (Exception $e){
            return false;
	 }
    }

    function remoteDBUpdate($appId) {
	$sql = "SELECT * FROM line_up_remote_db_info WHERE app_id=$appId";
	$dao = new Dao();
	try {
	    $arr = $dao->toArray($sql);
	    if (count($arr) == 0) {
		echo "Remote database data is missing. ";
		return false;
	    }
	} catch (Exception $e) {
	    echo "Remote database retrieval failed. ";
	    return false;
	}
	
	try {
	    $db_details  = $arr[0];
	    $daoRemote = new Dao($db_details['host'], $db_details['username'], $db_details['password'], $db_details['database']);

	    $sql = "SET FOREIGN_KEY_CHECKS = 0";
	    $daoRemote->query($sql);

	    $sql = "TRUNCATE TABLE `artists`";
	    $daoRemote->query($sql);

	    $sql = "TRUNCATE TABLE `stages`";
	    $daoRemote->query($sql);

	    $sql = "TRUNCATE TABLE `artistshows`";
	    $daoRemote->query($sql);

	    $sql = "TRUNCATE TABLE `festival_day`";
	    $daoRemote->query($sql);
	    
	    $sql = "TRUNCATE TABLE `day_stages`";
	    $daoRemote->query($sql);

	    $sql = "SELECT * FROM line_up_artists";
	    $artits = $dao->toArray($sql);

	    $remoteSql = "INSERT INTO `artists` (".
			"`artistid`,".
			"`artistname`,".
			"`artistimg`,".
			"`artistwebsite`,".
			"`artistfacebook`,".
			"`artisttwitter`,".
			"`artistbio`,".
			"`artistlogo`,".
			"`artistvideo`,".
			"`artistmusic`,".
			"`siteimg`,".
			"`sitelogo`".
			") VALUES";
	    $count = count($artits);
	    foreach ($artits as $i => $artit) {
		$remoteSql .= "(".
			"'{$artit['id']}',".
			"'".mysql_escape_string($artit['artist_name'])."',".
			"'".mysql_escape_string($artit['image_url'])."',".
			"'".mysql_escape_string($artit['artist_web_url'])."',".
			"'".mysql_escape_string($artit['artist_facebook'])."',".
			"'".mysql_escape_string($artit['artist_twitter'])."',".
			"'".mysql_escape_string($artit['biography'])."',".
			"'".mysql_escape_string($artit['artist_image_logo'])."',".
			"'".mysql_escape_string($artit['artist_video'])."',".
			"'".mysql_escape_string($artit['artist_music'])."',".
			"'".mysql_escape_string($artit['site_img'])."',".
			"'".mysql_escape_string($artit['site_logo'])."'".
			")";
		if ($i<$count-1) {
		    $remoteSql .= ',';
		}
	    }
	    $daoRemote->query($remoteSql);

	    $sql = "SELECT * FROM line_up_stages";
	    $stages = $dao->toArray($sql);
	    $remoteSql = "INSERT INTO `stages`".
			 "(`stageid`,".
			 "`stagename`,".
			 "`stageimage`) VALUES ";
	    $count = count($stages);
	    foreach ($stages as $i => $stage) {
		$remoteSql .= "(".
		"'".mysql_escape_string($stage['id'])."',".
		"'".mysql_escape_string($stage['stage_name'])."',".
		"'".mysql_escape_string($stage['image_url'])."')";
		if ($i<$count-1) {
		    $remoteSql .= ',';
		}
	    }
	    $daoRemote->query($remoteSql);
	    
	    $sql = "SELECT * FROM `line_up_shows`";
	    $shows = $dao->toArray($sql);
	    $remoteSql = "INSERT INTO `artistshows` (`artisteventid`,`artistid`,`day`,`stageid`,`time`, `show_time_string`) VALUES ";
	    $count = count($shows);
	    foreach ($shows as $i => $show) {
		$remoteSql .= "(".
		"'".$show['show_id']."',".
		"'".$show['artist_id']."',".
		"'".$show['festival_day_id']."',".
		"'".$show['stage_id']."',".
		"'".$show['time']."',".
		"'".  $this->_getShowTimeString($show['time'], $show['end_time'])."')";
		if ($i<$count-1) {
		    $remoteSql .= ',';
		}
	    }
	    $daoRemote->query($remoteSql);

	    $sql = "SELECT * FROM `line_up_festival_days`";
	    $days = $dao->toArray($sql);
	    $remoteSql = "INSERT INTO `festival_day` (`day_id`,`day_name`,`festival_date`) VALUES ";
	    $count = count($days);
	    foreach ($days as $i => $day) {
		$remoteSql .= "(".
		"'".$day['id']."',".
		"'".$day['festival_name']."',".
		"'".$day['festival_date']."')";
		if ($i<$count-1) {
		    $remoteSql .= ',';
		}
	    }
	    $daoRemote->query($remoteSql);
	    
	    $sql = "SELECT * FROM `line_up_festival_day_stages`";
	    $days = $dao->toArray($sql);
	    $remoteSql = "INSERT INTO `day_stages` (`day_id`,`stage_id`,`order_index`) VALUES ";
	    $count = count($days);
	    foreach ($days as $i => $day) {
		$remoteSql .= "(".
		"'".$day['festival_day_id']."',".
		"'".$day['stage_id']."',".
		"'".$day['order_index']."')";
		if ($i<$count-1) {
		    $remoteSql .= ',';
		}
	    }
	    $daoRemote->query($remoteSql);

	    $checkSumArtists = md5($this->_generateTableString('line_up_artists'));
	    $checkSumStages = md5($this->_generateTableString('line_up_stages').$this->_generateTableString('line_up_festival_day_stages'));
	    $checkSumEvents = md5($this->_generateTableString('line_up_shows'));
	    $remoteSql = "UPDATE `updates` SET artists='$checkSumArtists', stages='$checkSumStages', shows='$checkSumEvents'";

	    $daoRemote->query($remoteSql);
	} catch (Exception $e) {
	    echo $e->getMessage() . ". ";
	    return false;
	}

	$daoRemote->close();
	return true;
    }

    function _generateTableString ($tableName) {
	$sql = "SELECT * FROM $tableName";
	$dao = new Dao();
	$rows = $dao->toArray($sql, MYSQL_NUM);
	$str = "";
	foreach($rows as $colums) {
	    foreach($colums as $column) {
		$str .= $column;
	    }
	}

	return $str;
    }
    
    private function _getShowTimeString ($startTime, $endTime) {
	if ($startTime=='') 
	    $startTime = NULL;
	
	if ($endTime == '')
	    $endTime = NULL;
	
	$startTime = $this->_formatTime($startTime);
	$endTime = $this->_formatTime($endTime);
	
	if ($startTime == NULL) {
	    return '';
	} elseif ($endTime == NULL) {
	    return $startTime;
	} else {
	    return $startTime . ' - ' . $endTime;
	}
    }
    
    private function _formatTime ($time) {
	if ($time == NULL)
	    return NULL;
	
	$parts = explode(':', $time);
	return $parts[0] . ':' . $parts[1];
    }
}
?>
