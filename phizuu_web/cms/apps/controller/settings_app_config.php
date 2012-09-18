<?php
include("../config/config.php");
include("../controller/session_controller.php");
require_once '../database/Dao.php';
include('../model/settings_model.php');

$settingsObj = new SettingsModel();
$settingsObj->saveSettingsFromAPI($_POST);

header("Location: ../view/user/settings/settings_new.php");
?>
