<?php
define ("ROOT_PATH" , dirname(__FILE__)."/..");
require_once (ROOT_PATH."/config/config.php");
require_once (ROOT_PATH."/scripts/classes/WorldMapAPI.php");
require_once (ROOT_PATH."/database/Dao.php");

$worldMapAPI = new WorldMapAPI();

$worldMapAPI->generateApi();
?>
