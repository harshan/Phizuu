<?php
require_once ('../../config/config.php');
require_once ('../../database/Dao.php');


$sql = "SELECT user.app_id, user.app_name, user.id, user.username FROM user LEFT JOIN module ON user.app_id = module.app_id WHERE send_message=1 AND user.app_id!=0 ORDER BY app_name";

$dao = new Dao();
$array = $dao->toArray($sql, MYSQL_ASSOC);

echo json_encode($array);
?>
