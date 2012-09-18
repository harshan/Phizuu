<?php
session_start();

//$_SESSION['user_id'] = 7;

require_once '../../../model/StorageServer.php';
require_once '../../../model/UserInfo.php';
require_once '../../../model/themes/ThemeCache.php';
require_once '../../../database/Dao.php';
require_once '../../../config/config.php';
require_once '../../../model/themes/ThemeBuilder.php';

$userInfo = UserInfo::getUserInfoDirect();
$defaultThemePackage = "phizuu_classic";

$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

switch ($action) {
    case 'view_select_package':
        $themeBuilder = new ThemeBuilder();
        include ('../../../view/user/app_wizard/theme_package.php');
        break;
    case 'choose_package':
        $themePackage = $_GET['theme'];
        $themeBuilder = new ThemeBuilder();
        $themeBuilder->deleteSavedTheme();
        header ("Location: ThemeController.php?action=main_view&theme_package=$themePackage");
        break;
    case 'main_view':
        $themeBuilder = new ThemeBuilder();
        $theme = $themeBuilder->getThemeDetails();

        $mainThemes = $theme->mainThemes;
        $imageSets = $theme->imageSets;
        $iconSets = $theme->iconSets;
        $colorSets = $theme->colorSets;
        $themePackage = isset($_GET['theme_package'])?$_GET['theme_package']:$defaultThemePackage;

        $loadedTheme = $themeBuilder->loadSavedTheme();

        include ('../../../view/user/app_wizard/theme_builder.php');
        break;
    case 'save_theme':
        $themeBuilder = new ThemeBuilder();

        $data = $_POST['theme_data'];
        $themeBuilder->saveTheme($data);
        break;

    case 'finish_theme':
        $themeBuilder = new ThemeBuilder();

        $data = $_POST['theme'];
        $data = json_decode(stripslashes($data));
        $themeBuilder->finishTheme($data);
        break;

    case 'download_theme':
        $themeBuilder = new ThemeBuilder();

        $themeBuilder->createAndDownloadTheme($_GET['user_id']);
        break;
    
    default:
        echo "Error! No valid action";
}
?>