<?php
require_once "../../../database/Dao.php";
require_once "../../../config/config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Phizuu Application</title>


        <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />

    </head>
    <?php
    $showButton = '';
    $error = '';
    $upload_dir_temp = "../../../temporary_files/video/";
    $fileName = $_SESSION['app_id'] . '.mp4';
    $full_filepath = $upload_dir_temp . $fileName;
    if (isset($_POST['upload'])) {

        if ($_FILES["fileUpload"]["type"] != "video/mp4") {
            $error = 'The specified file is not a MP4 video!';
        } else if ($_FILES["fileUpload"]["size"] > 5242880) {
            $error = "Your video size is too large (Max 5MB)";
        } else {
            //$time = date("dmyHis", time());


            move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $upload_dir_temp . $fileName);
            $error = 'File uploaded successfully';
        }
    }
    ?>  
    
    <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../../js/dialog_model/jquery.ui.all.css">
        <script src="../../../js/dialog_model/jquery-1.7.2.js"></script>
        <script src="../../../js/dialog_model/jquery.bgiframe-2.1.2.js"></script>
        <script src="../../../js/dialog_model/jquery.ui.core.js"></script>
        <script src="../../../js/dialog_model/jquery.ui.widget.js"></script>
        <script src="../../../js/dialog_model/jquery.ui.mouse.js"></script>
        <script src="../../../js/dialog_model/jquery.ui.button.js"></script>
        <script src="../../../js/dialog_model/jquery.ui.draggable.js"></script>
        <script src="../../../js/dialog_model/jquery.ui.position.js"></script>
        <script src="../../../js/dialog_model/jquery.ui.dialog.js"></script>
        <link rel="stylesheet" href="../../../js/dialog_model/demos.css">

            <script type="text/JavaScript">


                function takeAction(action) {
    
                    if (action=='skip') {
        
                        //         $("#skipWarning").dialog( "open" );
                        //         $( "#dialog:ui-dialog" ).dialog( "destroy" );
                        this.showDialog();
                    } else if (action=='save') {
            
                        window.location = "AppWizardControllerNew.php?action=home_video_save";
        
                    }
                }

                function showDialog() {
                    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		
	
                    $( "#skipWarning" ).dialog({
                        resizable: false,
                        height:170,
                        width:400,
                        modal: true,
                        buttons: {
                            Cancel: function() {
                                $(this).dialog('close');
                            },
                            Accept: function() {
                                window.location = "AppWizardControllerNew.php?action=home_video_skip";
                                $(this).dialog('close');
                            }
                        }
                    });
                }
            </script>



            <body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png'); validateRSS();">
                <div id="mainWideDiv">
                     <div id="header">
        <div style="width: 800px;height: 90px;margin: auto">
                        <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logoInner.png" width="350" height="35" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="99" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
        </div>
                </div>
                    <div id="middleDiv2">
                       
                        <div class="wizardTitle" style="padding-top: 35px">
                           
                            <div class="middle" style="width: 870px; height: 25px">Please upload your loading video</div>
                           
                        </div>
                        <div id="bodyPhotos" style="width: 903px;margin-bottom: 0px">

                            <div id="lightBlueHeader">
                              
                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 626px">Home Video </div>
                               
                            </div>
                            <div style="clear: both">
                                
                            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                                <div id="UploadYourOwnImageInput"><input type="file" name="fileUpload" id="fileUpload"/>
                                    <input type="submit"  name="upload" value="Upload" onclick="submitform()" class="button"/></div>
                                     <div style="clear: both">
                                    <div>Note: Upload videos must be mp4 format</div>
                                    <div style="padding-left: 27px">  Dimension of video must be  640px X 960px</div>
                                    <div style="padding-left: 27px">  Upload file size must be less then 5MB</div> 
                            </div>
                               

                                <div style="width: 300px;color: darkred;font-size: 14px">
<?php echo $error; ?>
                                </div>
                            </form>
                            </div>



                            <div id="photoHolderBox" class="uploadSectionDiv" style="clear: left">
<?php if (file_exists($full_filepath)) {
    $showButton = 'wizardButton';
    ?>           
                                    <object width="100%" height="100%"
                                            type="video/mp4" url="<?php echo $upload_dir_temp . $fileName; ?>" data="<?php echo $upload_dir_temp . $fileName; ?>"
                                            classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6">
                                        <param name="url" value="<?php echo $upload_dir_temp . $fileName; ?>">
                                            <param name="filename" value="<?php echo $upload_dir_temp . $fileName; ?>">
                                                <param name="autostart" value="1">
                                                    <param name="uiMode" value="full" />
                                                    <param name="autosize" value="1">
                                                        <param name="playcount" value="1">
                                                            <embed   id="videos" type="application/x-mplayer2" src="<?php echo $upload_dir_temp . $fileName; ?>" width="640px" height="980px" autostart="false" showcontrols="true" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/"> </embed>
                                                            </object>
<?php } ?>                       





                             </div>

                                  </div>
<div id="bodyLeftWizard">
                                <div class="nextButton" style="width: 898px;margin-left: 0px">
<img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="171" height="33" />

                                                            <?php 
                                                            if($showButton=='wizardButton'){
                                                                ?>
                                                            <img src="../../../images/btn_next.png"  onclick="javascript: takeAction('save');" style="cursor: pointer"/>
                                                            <?php
                                                            }else{
                                                                ?>
                                                            <img src="../../../images/btn_next_disabled.png"/>
                                                            <?php
                                                            }
                                                            ?>
                                </div>
                            </div> 

                             </div>
<!--                                                        <div class="nextButton" style="width: 1026px; border: 0">
                                                            <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="171" height="33" />

                                                            <?php 
                                                            if($showButton=='wizardButton'){
                                                                ?>
                                                            <img src="../../../images/btn_next.png"  onclick="javascript: takeAction('save');" style="cursor: pointer"/>
                                                            <?php
                                                            }else{
                                                                ?>
                                                            <img src="../../../images/btn_next_disabled.png"/>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div> -->
                                                        </div> <br class="clear"/>

                                                        <div id="result" style="display: none"></div>
                                                         <br class="clear"/> <br class="clear"/>
                                                            <div id="footerInner" >
                                                        <div class="lineBottomInner"></div>
                                                            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
                                                        </div>

                                                        </body>

                                                        </html>
                                                        <div id="skipWarning" title="Warning!" style="text-align:center; display: none" >
                                                            <p>If you skip this module you cannot add loading video into your app at a later time. Your application will be submitted to Apple without a Loading video. Please acknowledge by pressing Accept or press Cancel to add loading video.</p>
                                                        </div>
                                                        <script type="text/javascript">
                                                            function MM_swapImgRestore() { //v3.0
                                                                var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
                                                            }

                                                            function MM_preloadImages() { //v3.0
                                                                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                                                                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                                                                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                                                                }

                                                                function MM_findObj(n, d) { //v4.01
                                                                    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
                                                                        d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
                                                                    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
                                                                    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
                                                                    if(!x && d.getElementById) x=d.getElementById(n); return x;
                                                                }

                                                                function MM_swapImage() { //v3.0
                                                                    var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
                                                                    if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
                                                                }
                                                                //oder set using this code
                                                                $(document).ready(function() {
                                                                    $("#sortable").sortable({handle : '.dragHandle'});

                                                                    $('#sortable').bind('sortupdate', function(event, ui) {
                                                                        $("#sortable").sortable( 'disable' );
                                                                        $("#sortable").css('cursor', 'wait');
                                                                        $("#sortable .dragHandle").css('cursor', 'wait');
                                                                        var order = $('#sortable').sortable('serialize');
                                                                        $.post('../../../controller/modules/home_image/home_image_controller.php?action=order&'+order, function(data) {
                                                                            $("#sortable").sortable( 'enable' );
                                                                            $("#sortable").css('cursor', '');
                                                                            $("#sortable .dragHandle").css('cursor', 'move');
                                                                        });
                                                                    });

                                                                    refreshEdits();
                                                                });
                                                                showUploader();
                                                                function showUploader(){
                                                                    $.post("../../../controller/modules/home_image/home_image_controller.php?action=noOfRecoeds", function(data){
                                                                        if(data=='2'){
           
                                                                        }
                                                                        //alert(data);
                                                                    });
                                                                } 
                                                                function setDefault(id){
    
                                                                    var itemId = id;
                                                                    var item = $("#id_"+id);
                                                                    var imagePath = document.getElementById('imagePath'+id).value;
                                                                    //var imagePathThumb = document.getElementById('imagePathThumb'+id ).value;
                                                                    $.post("../../../controller/modules/home_image/home_image_controller.php?action=set_default&imagePath="+imagePath, { 'id': id },
                                                                    function(data){
                                                                        if (data!='ok') {
                                                                            alert("Error! while set as default\n\n"+data);
                                                                        } else{
                                                                            alert("Selected image set as default successfully");
                                                                            window.location.reload(true);
                                                                        }
                                                                    });
        
                                                                }
                                                                function deleteItem(id) {
                                                                    //$("#newsSortable").
                                                                    var itemId = id;
                                                                    var item = $("#id_"+id);
                                                                    var imagePath = document.getElementById('imagePath'+id).value;
                                                                    var imagePathThumb = document.getElementById('imagePathThumb'+id ).value;
                                                                    $.post("../../../controller/modules/home_image/home_image_controller.php?action=delete_image&imagePath="+imagePath+"&imagePathThumb="+imagePathThumb, { 'id': id },
        
                                                                    function(data){
          
                                                                        if (data!='ok') {
                                                                            alert("Error! while deleting\n\n"+data);
                                                                            $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                                                                        } else{
                                                                            item.hide(500,function(){
                                                                                document.getElementById('sortable').removeChild(document.getElementById('id_'+itemId));
                                                                                window.location.reload(true);

                                                                            });
                                                                        }
                                                                    });
        
                                                                    $('#id_'+itemId).children().css('background-color', 'pink');

                                                                }
                                                                $('#result').load('../../../controller/modules/home_image/home_image_controller.php?action=get_default_image', function(data) {
                                                                    $('#default'+data).css('background-color', '#437C17');
                                                                    $('#default'+data).css('color', '#ffffff');
                                                                });
                                                        </script>
                                                        <script type="text/javascript">
                                                                //function openJS(){alert('loaded')}
                                                                function closeJS(){
    
                                                                    window.location.reload();
                                                                }
                                                        </script>


