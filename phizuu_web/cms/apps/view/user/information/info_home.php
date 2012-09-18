<?php
require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once '../../../common/browser.php';
$menu_item = 'information';

$browser = new Browser();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>phizuu - Links</title>
        <style type="text/css">

        </style>

        <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
        <link rel="stylesheet" href="../../../js/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    </head>

    <body>
         <div id="header" >
            <div id="headerContent">
            <?php include("../../../view/user/common/header.php");?>
            </div>
            <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">
                
                <?php include("../../../view/user/common/navigator.php"); ?>
                <form action="InfoController.php?action=save" method="post">
                    
                    <div id="body">
                        <div style="height: 12px;">&nbsp;</div>
                     <div style="width: 450;float: left;">
                     <div id="lightBlueHeader" style="width: 450px">
                        
                        <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 422px;margin-left: 10px">Biography Module Text</div>
                        
                    </div>
                   
                    <div style=" width: 450px; margin-bottom:6px; margin-left: 10px">
                        
                        <?php 
                       
if( $browser->getBrowser() == Browser::BROWSER_FIREFOX ) {
    ?>
                       
                        <textarea name="biographyText" id="biographyText" style="float: left; width: 425px; height: 480px"><?php echo $popArr['texts']['biography_text'] ?></textarea>
                        <?php
	
} else if( $browser->getBrowser() == Browser::BROWSER_IE ) {
    ?>
                         <textarea name="biographyText" id="biographyText" style="float: left; width: 438px; height: 480px"><?php echo $popArr['texts']['biography_text'] ?></textarea>
                        <?php 
}else{
?>
                        <textarea name="biographyText" id="biographyText" style="float: left; width: 422px; height: 480px"><?php echo $popArr['texts']['biography_text'] ?></textarea>
                        <?php } ?>
                    </div>

                    
                    <div style=" width: 80px;float: right">
                        <input type="image" src="../../../images/save.png"/>
                    </div>
                    </div>
                   
                    <div style="width: 450;float: left">
                    <div id="lightBlueHeader" style="width: 450px">
                       
                        <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 422px;margin-left: 25px">About Module Text</div>
                       
                    </div>
   
                    <div style="width: 450px; margin-bottom:6px; margin-left: 25px">
                                                                <?php 
                  
if( $browser->getBrowser() == Browser::BROWSER_FIREFOX ) {
    ?>
                        <textarea name="aboutText" id="aboutText" style="float: left; width: 425px; height: 480px"><?php echo $popArr['texts']['about_text'] ?></textarea>
                                              <?php
	
} else if( $browser->getBrowser() == Browser::BROWSER_IE ) {
    ?>
                        <textarea name="aboutText" id="aboutText" style="float: left; width: 438px; height: 480px"><?php echo $popArr['texts']['about_text'] ?></textarea>
                                                <?php 
}else{
?>
                        <textarea name="aboutText" id="aboutText" style="float: left; width: 422px; height: 480px"><?php echo $popArr['texts']['about_text'] ?></textarea>
                        <?php } ?>
                    </div>

                    
                    <div style=" width: 80px;float: right">
                        <input type="image" src="../../../images/save.png"/>
                    </div>
                    </div>
                    
                    </div>
                </form>
            </div>  <br class="clear"/>  <br class="clear"/>
        </div>
              <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>

        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jwysiwyg/jquery.wysiwyg.js"></script>

            <script type="text/javascript">
(function($)
{
  $('#biographyText, #aboutText').wysiwyg({
    controls: {
      strikeThrough : { visible : true },
      underline     : { visible : true },

      separator00 : { visible : true },

      justifyLeft   : { visible : true },
      justifyCenter : { visible : true },
      justifyRight  : { visible : true },
      justifyFull   : { visible : true },

      separator01 : { visible : true },

      indent  : { visible : true },
      outdent : { visible : true },

      separator02 : { visible : true },

      subscript   : { visible : true },
      superscript : { visible : true },

      separator03 : { visible : true },

      undo : { visible : true },
      redo : { visible : true },

      separator04 : { visible : true },

      insertOrderedList    : { visible : true },
      insertUnorderedList  : { visible : true }
    },
    css: "../../../js/jwysiwyg/my_styles.css"
  });
})(jQuery);

<?php
if (isset($_GET['saved'])) {
?>
$.jGrowl("Successfully saved the information module texts");
<?php
}
?>
    </script>
    </body>

</html>
