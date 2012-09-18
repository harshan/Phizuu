<?php

require_once("../../../config/config.php");
require_once ('../../../model/Album.php');
require_once ('../../../database/Dao.php');

$nav_modules=$_SESSION['modules'];

$album = new Album($_SESSION['user_id']);
$albumMode = $album->getAlbumStatus();

if ($albumMode == 1) {
    $albumURL = 'album.php';
} else {
    $albumURL = 'photos_new.php';
}

if (!isset($imagePath)) {
    $imagePath = '../../../';
}

$modules1 = array (
    array (
        'module_name'=> 'music',
        'active_image'=> 'musicAc.png',
        'inactive_image'=> 'musicIn.png',
        'link'=> '../../../view/user/music/music.php'
    ),
    array (
        'module_name'=> 'line_up',
        'active_image'=> 'lineupAc.png',
        'inactive_image'=> 'lineupIn.png',
        'link'=> '../../../controller/modules/line_up/LineUpController.php?action=main_view'
    ),
    array (
        'module_name'=> 'videos',
        'active_image'=> 'VideoAc.png',
        'inactive_image'=> 'VideoIn.png',
        'link'=> '../../../view/user/videos/videos.php'
    ),
    array (
        'module_name'=> 'photos',
        'active_image'=> 'photosAc.png',
        'inactive_image'=> 'photosIn.png',
        'link'=> "../../../view/user/pictures/$albumURL"
    ),
    array (
        'module_name'=> 'news',
        'active_image'=> 'newsAc.png',
        'inactive_image'=> 'newsIn.png',
        'link'=> '../../../view/user/news/news_new.php'
    ),
    array (
        'module_name'=> 'tours',
        'active_image'=> 'ToursAc.png',
        'inactive_image'=> 'ToursIn.png',
        'link'=> '../../../view/user/tours/tours_new.php'
    ),
    array (
        'module_name'=> 'links',
        'active_image'=> 'linksAc.png',
        'inactive_image'=> 'linksIn.png',
        'link'=> '../../../controller/modules/links/LinksController.php?action=main_view'
    ),
    array (
        'module_name'=> 'send_message',
        'active_image'=> 'sendMessageAc.png',
        'inactive_image'=> 'sendMessageIn.png',
        'link'=> '../../../view/user/send_message/send_message.php'
    ),
    array (
        'module_name'=> 'analytics',
        'active_image'=> 'analyticsAc.png',
        'inactive_image'=> 'analyticsIn.png',
        'link'=> '../../../view/user/analytic/analytic_main.php'
        //'link'=> '../../../controller/reports/analytics/ReportController.php?action=main_view'
    ),
    array (
        'module_name'=> 'payments',
        'active_image'=> 'paymentAc.png',
        'inactive_image'=> 'paymentIn.png',
        'link'=> '../../../controller/modules/payments/PaymentController.php?action=view'
    ),
    array (
        'module_name'=> 'buy_stuff',
        'active_image'=> 'buyStaffAc.png',
        'inactive_image'=> 'buyStaffIn.png',
        'link'=> '../../../controller/modules/buy_stuff/BuyStuffController.php?action=main_view'
    ),
    array (
        'module_name'=> 'app_update',
        'active_image'=> 'contentEditReqAc.png',
        'inactive_image'=> 'contentEditReqIn.png',
        'link'=> '../../../controller/modules/update_app/UpdateAppController.php?action=main_view'
    ),
    array (
        'module_name'=> 'fan_contents',
        'active_image'=> 'userContentsAc.png',
        'inactive_image'=> 'userContentsIn.png',
        'link'=> '../../../controller/modules/user_uploaded_content/UserUploadedContentsController.php?action=main_view'
    ),
    array (
        'module_name'=> 'discography',
        'active_image'=> 'discographyAc.png',
        'inactive_image'=> 'discographyIn.png',
        'link'=> '../../../controller/modules/discography/DiscographyController.php?action=main_view'
    ),
    array (
        'module_name'=> 'settings',
        'active_image'=> 'settingsAc.png',
        'inactive_image'=> 'settingsIn.png',
        'link'=> '../../../view/user/settings/settings_new.php'
    ),
    array (
        'module_name'=> 'home_images',
        'active_image'=> 'home_img_ac.png',
        'inactive_image'=> 'home_img_in.png',
        'link'=> '../../../view/user/home_images/home_images.php'
    ),
    array (
        'module_name'=> 'email_list',
        'active_image'=> 'emailAc.png',
        'inactive_image'=> 'emailIn.png',
        'link'=> '../../../view/user/email_list/email_list.php'
    ),
    array (
        'module_name'=> 'information',
        'active_image'=> 'informationAc.png',
        'inactive_image'=> 'informationIn.png',
        'link'=> '../../../controller/modules/information/InfoController.php?action=main_view'
    )
    
);

$tabList = array();
$moreList = array();


$numVisibleModules = 0;
foreach ($nav_modules[0] as $nav_module) {
    if($nav_module == '1')
        $numVisibleModules++;
}

if ($numVisibleModules>12) {
    $count = 1;
    foreach( $modules1 as $module) {
        if (isset($nav_modules[0][$module['module_name']] ) && $nav_modules[0][$module['module_name']] == '1') {
            if ($count<12) {
                $tabList[] = $module;
            } else {
                $moreList[] = $module;
            }
            $count++;
        }
    }
    
    $more = array (
        'module_name'=> 'more',
        'active_image'=> 'moreAc.png',
        'inactive_image'=> 'moreIn.png',
        'link'=> '#'
    );

    $tabList[] = $more;
    $modules[] = $more;
    $nav_modules[0]['more'] = '1';
} else {

    $tabList = $modules1;
}

?>   
<div id="navigator">
    <?php
    

    foreach( $tabList as $module) {
        ?>
        <?php if(isset($nav_modules[0][$module['module_name']] ) && $nav_modules[0][$module['module_name']] == '1') {?>
    <div class="iconBox">
        <a href="<?php echo $module['link'] ?>" <?php echo $module['module_name']=='more'?"onmouseover='navigationShowMore()' onmouseout=navigationHideMore()":"" ?> >
            <img src="../../../images/menu_divider.png" style="position: absolute;height: 80px;text-decoration: none;border: 0"/>
             <?php
            if ($menu_item == $module['module_name']) { ?>
                <img src="../../../images/<?php echo $module['active_image'] ?>" width="79" height="80" border="0" />
            <?php } else { ?>
                <img src="../../../images/<?php echo $module['inactive_image'] ?>" width="79" height="80" border="0" onmouseout="javascript: cmsRestoreImage(this)" onmouseover="javascript: cmsSwapImage('../../../images/<?php echo $module['active_image'] ?>',this)"/>
            <?php } ?>
        </a>
        <?php if ($module['module_name']=='more') { ?> 
 
        <?php } ?>
       
    </div>
            <?php }

    } ?>
</div>

<div id="navigationMoreDiv" onmouseover='navigationCancelTimeOut()' onmouseout='navigationHideMore()'>
    <?php foreach( $moreList as $module) { ?>
   
    <div class="iconBoxMoreList">
        <a href="<?php echo $module['link'] ?>" >
              <img src="../../../images/menu_divider.png" style="position: absolute;height: 80px;text-decoration: none;border: 0"/>
            <?php
            if ($menu_item == $module['module_name']) { ?>
                <img src="../../../images/<?php echo $module['active_image'] ?>" width="79" height="80" border="0" />
              
            <?php } else { ?>
                <img src="../../../images/<?php echo $module['inactive_image'] ?>" width="79" height="80" border="0" onmouseout="javascript: cmsRestoreImage(this)" onmouseover="javascript: cmsSwapImage('../../../images/<?php echo $module['active_image'] ?>',this)"/>
                
            <?php } ?>
        </a>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
var lastImage = '';
var navigationTimerId;
function cmsSwapImage(src, elem) {
    lastImage = elem.src;
    elem.src = src;
}

function navigationShowMore() {
    clearTimeout(navigationTimerId);
    var menu = document.getElementById('navigationMoreDiv');
    menu.style['display']='inline';

    return false;
}

function navigationHideMore() {
    var menu = document.getElementById('navigationMoreDiv');
    
    navigationTimerId = setTimeout ( "navigationHideMoreDeligate()", 300 );


    return false;
}

function navigationCancelTimeOut() {
    clearTimeout(navigationTimerId);
}

function navigationHideMoreDeligate() {
    //window.console.log('hide');
    var menu = document.getElementById('navigationMoreDiv');
    menu.style['display']='none';
}

function cmsRestoreImage (elem) {
    elem.src = lastImage;
}

function navigationLoadWait(elem) {
    elem.innerHTML = "<img src='../../../images/bigrotation2.gif' height=32 width=32/>";
    return true;
}
<?php
$i=1;
foreach( $modules as $module) {
    echo "var preloadImage444$i = new Image;";
    echo "preloadImage444$i.src = '../../../images/{$module['active_image'] }';";
    $i++;
}
?>

</script>
