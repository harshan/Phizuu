<?php
$settingModel = new SettingsModel();
$settings = $settingModel->listSettings($_ENV['myspace_url']);
if (count($settings) > 0)
    $url = $settings[count($settings) - 1]->value;
else
    $url = '';

$tour = new ToursModel();
$defaultImageArr = $tour->getDefaultImage($_SESSION['user_id']);
$defaultImage = $defaultImageArr[1];
$defaultImage2 = $defaultImageArr[0]
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

        <link rel="stylesheet" type="text/css" href="../../../css/calendar/jscal2.css" />
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/steel/steel.css" />
        <style type="text/css">
            <!--
            #queue > div {


                float: left;
                width: 945px;
                display: none;
                margin-bottom: 1px;
                background-color: #f3f3f3;
            }
            -->
        </style>


        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
        <script type="text/javascript" src="../../../js/jquery.tooltip.min.js"></script>
        <script src="../../../js/calendar/jscal2.js"></script>
        <script src="../../../js/calendar/en.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".ttip div").mouseover(function(){
               
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                    $("#delete_"+id).mouseover(function(){
                   
                        $("#div_tooltip_common_"+id).css({
                            "margin":"45px 0 0 918px",
                            "padding":"10px 0 0 12px"
                        });
                        $("#div_tooltip_common_"+id).show();
                        $("#delete_"+id).mouseout(function(){
                            $("#div_tooltip_common_"+id).hide();
                        });
                    
                    });
                
                
                            
                });
            
            });
            function mySpaceTours() {
                if ($("#mySpaceURL").val() == '') {
                    alert ("Please enter the URL!");
                    return false;
                } else {
                    if (confirm("All the existing events in the module will be removed!\n\nDo you want to continue?")) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }

            function editPic(id){
                var target = $('#6_' + id);

                $('#imageEdit').show(500);
                $('#imageEdit').css('top', target.offset().top + 55);
                $('#imageEdit').css('left', target.offset().left - 208);
                document.getElementById('pidEditForm').reset();
                $('#pidEditForm').attr('action', '../../../controller/tours_all_controller.php?wizard=yes&action=change_images&id=' + id);
            }

            function hidePic() {
                $('#imageEdit').hide();
            }

            function deleteItem(id) {
                //$("#newsSortable").
                var itemId = id;
                var item = $("#id_"+id);

                $.post("../../../controller/tours_all_controller.php?action=delete_tour", { 'id': id },
                function(data){
                    if (data!='ok') {
                        alert("Error! while deleting\n\n"+data);
                        $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                    } else{
                        toursCount--;
                        if (toursCount>0) {
                            $('#buttonNext').attr('src','../../../images/btn_next.png');
                            $('#buttonNext').addClass('wizardButton');
                        } else {
                            $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
                            $('#buttonNext').removeClass('wizardButton');
                        }

                        item.hide(500,function(){

                            document.getElementById('tourSortable').removeChild(document.getElementById('id_'+itemId));
                        });
                    }
                });

                $('#id_'+itemId).children().css('background-color', 'pink');

            }


            function selectImage(value, thumb) {
                $("#coverImage").attr('src', value);
                $.post("../../../controller/tours_all_controller.php?action=update_default_image", { 'url': value, 'thumb':thumb },
                function(data){
                    if(data=="ok")
                        $("#showPics").hide(500);
                    else
                        alert("Error occured while changing the image!");
                });
            }

            jQuery.fn.center = function () {
                this.css("position","absolute");
                this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
                this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
                return this;
            }

            var imagesLoaded=false;
            function showMusicChooseWindow () {
                $("#showPics").center();
                $("#showPics").show(500);
                if (!imagesLoaded){
                    $("#showPics #bodyx").html("<img src='../../../images/bigrotation2.gif'></img>");
                    $("#showPics #bodyx").load("../music/select_image.php", function(response, status, xhr) {

                        if (status == "error") {
                            var msg = "Sorry but there was an error: ";
                            $("#error").html(msg + xhr.status + " " + xhr.statusText);
                        } else {
                            imagesLoaded = true;
                        }
                    });


                }

            }




            $(document).ready(function() {
                // wait for form submission
                $("#noContent").dialog({
                    modal: true,
                    autoOpen: false,
                    width: 400,
                    resizable: false,
                    buttons: {
                        Ok: function() {
                            $(this).dialog('close');
                        }
                    }
                });

                $("#skipWarning").dialog({
                    modal: true,
                    autoOpen: false,
                    width: 400,
                    resizable: false,
                    buttons: {
                        Cancel: function() {
                            $(this).dialog('close');
                        },
                        Accept: function() {
                            window.location = "AppWizardControllerNew.php?action=tours_module_skip";
                            $(this).dialog('close');
                        }
                    }
                });
                $("#tourSortable").sortable({handle : '.dragHandle'});

                //$("#tourSortable").disableSelection();
                /*$('#tourSortable').sortable({
                update: function(event, ui) { alert('er'); }
            });*/
                $('#tourSortable').bind('sortupdate', function(event, ui) {
                    $("#tourSortable").sortable( 'disable' );
                    $("#tourSortable").css('cursor', 'wait');
                    $("#tourSortable .dragHandle").css('cursor', 'wait');
                    var order = $('#tourSortable').sortable('serialize');
                    $.post('../../../controller/tours_all_controller.php?action=order&'+order, function(data) {
                        $("#tourSortable").sortable( 'enable' );
                        $("#tourSortable .dragHandle").css('cursor', 'move');
                        $("#tourSortable").css('cursor', '');
                    });


                });

                $('.edit').editable('../../../controller/tours_all_controller.php?action=edit',{
                    indicator : 'Saving...',
                    tooltip   : 'Click to edit...'
                });


                $("#form").submit(function() {
                    // get the input element and text
                    var name = $("#name");
                    var date = $("#date");
                    var location1 = $("#location1");
                    var notes = $("#notes");
                    var count = $("#count");

                    var txt_name = name.val().trim();
                    var txt_date = date.val().trim();
                    var txt_location1 = location1.val().trim();
                    var txt_notes = notes.val();
                    var txt_count = count.val();
                    var txt_count1 =parseInt(txt_count)+1;

                    // check if text was entered
                    if(txt_name.length > 0) {
                        //	 //post data to process.php and get json
                        //		 $.post('../../../controller/tours_newline_controller.php', {name: txt_name,date: txt_date,location1: txt_location1,notes: txt_notes,count: txt_count1}, function(data) {
                        //		 //$.post('../../../controller/tours_newline_controller.php', { name: txt_name,date: txt_date,notes: txt_notes }, function(data) {
                        //			// if process.php returns success
                        //			//if(data.status == 'success') {
                        //			   // create a new div element, add it to queue and animate
                        //			   var element = $('<li>' + data.text + '</li>');
                        //			   //element.prependTo("#queue").slideDown();
                        //			   element.appendTo("#tourSortable").slideDown();
                        //			   // clear input field
                        //			   name.val('');
                        //			   date.val('');
                        //			   location1.val('');
                        //			   notes.val('');
                        //			   count.val(txt_count1);
                        //			   document.getElementById('buttonContainer').style.display="none";
                        //                               $('.edit').editable('../../../controller/tours_all_controller.php?action=edit',{
                        //         indicator : 'Saving...',
                        //         tooltip   : 'Click to edit...'
                        //     });
                        //			//}
                        //		 }, 'json');
                        //location.reload();
                        return true;

                    }
                    //
                    // prevent default form action
                    return false;

                });


                /*	   $(".click").editable("../../../controller/tours_inline_controller.php", {
                  indicator : "<img src='img/indicator.gif'>",
              tooltip   : "Click to edit...",
              style  : "inherit"
          });*/

            });


            function calendar(btn,id,div_id){
                var cal = Calendar.setup({
                    onSelect: function(cal) {cal.hide()
                        var selectionObject = cal.selection;
                        var selectedDate = selectionObject.get();
                        if(selectedDate != ""){


                            var date=selectionObject.print("%Y-%m-%d");
                            $.post('../../../controller/tours_all_controller.php?action=edit', {id: div_id,value: date}, function(data) {
                                // document.getElementById(div_id).innerHTML=selectionObject.print("%Y-%m-%d");
                                //alert(div_id);
                                document.getElementById(id).innerHTML=selectionObject.print("%Y-%m-%d");

                            });
                        }

                    }
                });


                cal. manageFields(btn, id, "%Y-%m-%d");

            }

            function calendar_add(){

                var cal = Calendar.setup({
                    onSelect: function(cal) {cal.hide()


                    }
                });


                cal. manageFields("f_btn1", "date", "%Y-%m-%d");

            }
            function test(){


                $(".click").editable("../../../controller/tours_inline_controller.php", {
                    indicator : "<img src='img/indicator.gif'>",
                    tooltip   : "Click to edit...",
                    style  : "inherit"
                });


            }

            function show_div(){
                //document.getElementById('buttonContainer').style.display="inline";
                $("#buttonContainer").show(500);
            }


            function takeAction(action) {
                if (action=='skip') {
                    $("#skipWarning").dialog( "open" );
                } else if (action=='save') {
                    if (toursCount==0) {
                        $("#noContent").dialog( "open" );
                    } else {
                        window.location = "AppWizardControllerNew.php?action=tours_module_save";
                    }
                }
            }


        </script>
    </head>


    <body>

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

                <div id="body">
                    <br/>
                    <div class="wizardTitle" >

                        <div class="middle" style="width: 910px"><div style="margin-top: 5px;float: left">Please add your events here </div></div>

                    </div>

                    <div class="wizardSecondTitle" style="width: 100%">
                        You can add any number of links. Alternatively, you can fetch all the events in the My Space 'All Shows' Page, from "Add Events From MySpace" section.
                    </div>
                    <?php if (isset($_GET['error'])) { ?>
                        <div class="tahoma_12_blue_error_bold" style ="padding-top: 4px; float: left">Error! Couldn't retrieve any Events from the given URL. No existing events were deleted.</div>
                    <?php } ?>
                    <div id="bodyNews">

                        <div id="titleBoxNewsBox">
                            <div class="tahoma_14_white" id="titleTours">Name</div>
                            <div class="tahoma_14_white" id="dateTours">Date</div>
                            <div class="tahoma_14_white" id="locationTours">Location</div>
                            <div class="tahoma_14_white" style="float: left;height: 21px;padding-top: 7px;width: 230px;">Description</div>
                            <div class="tahoma_14_white" id="titleTickeURL">Ticket URL</div>
                            <div class="tahoma_14_white" id="titleThumbImg">Thumb</div>

                        </div>
                        <ul id="tourSortable" class="tahoma_12_blue">
                            <?php include('supporting/list_tours1.php'); ?>
                        </ul>




                    </div>
                    <div id="buttonContainer1">
                        <div id="addMusicBttn2_hide">
                            <div id="addTourButton" style="cursor: pointer"><img src="../../../images/addNewTours.png" width="147" height="33" onclick="show_div();" /></div>
                        </div>
                    </div>
                    <div id="buttonContainer" style="display:none">

                        <form id="form" name="form" method="post" enctype="multipart/form-data" action="../../../controller/tours_newline_controller.php?wizard=yes">
                            <div id="addMusicBttn2">

                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName" >Name</div>
                                    <div id="formSinFeild">
                                        <input name="name" id="name" type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Date</div>
                                    <div id="formSinFeild">
                                        <input type="Text" id="date" name="date" maxlength="25" size="25" class="textFeildBoarder" style="width:227px; height:21px;" readonly="readonly"/><img src="../../../images/cal.gif" id="f_btn1"  onclick="calendar_add();" onMouseOver="calendar_add();" />
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Location</div>
                                    <div id="formSinFeild">
                                        <input  name="location1" id="location1" type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>
                                <div id="formRowMulti">
                                    <div class="tahoma_12_blue" id="formName">Description</div>
                                    <div id="formMultiFeild">
                                        <textarea name="notes" id="notes" class="textFeildBoarder" style="width:227px; height:100px;"></textarea>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Ticket URL</div>
                                    <div id="formSinFeild">
                                        <input  name="ticketURL" id="ticketURL" type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Flyer</div>
                                    <div id="formSinFeild">
                                        <input  name="flyerImage" id="flyerImage" type="file" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>

                                <div id="formRowButtons">
                                    <div class="tahoma_12_blue" id="formName"></div>
                                    <div id="formSinFeild">
                                        <input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
                                        <input type="image" src="../../../images/save.png" name="Login" id="Login"width="69" height="33"/>
                                        <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel"width="99" height="33" onclick="form.reset();"/>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                    <div id="lightBlueHeader2">

                        <div id="lightBlueHeaderMiddle2" class="tahoma_14_white" style="height: 24px">Add Events From MySpace</div>

                    </div>

                    <form action="../../../controller/tours_all_controller.php?action=fetch_myspace_tours&wizard=yes" method="post" onsubmit="javascript: return mySpaceTours();">
                        <div style="width: 800px">
                            <div class="tahoma_12_blue" id="formName" style="width:200px">Link to My Space 'All Shows' Page</div>
                            <div id="formSinFeild" style="width:320px;padding-top: 3px">
                                <input value="<?php echo $url ?>" name="mySpaceURL" id="mySpaceURL" type="text" class="textFeildBoarder" style="width:300px; height:21px;"/> 
                            </div>
                            <div style="float: left">
                                <input name="btnRss" type="image" src ="../../../images/btn_update.png" align="middle"/>
                            </div>
                        </div>

                    </form>


                    <div id="bodyLeft">&nbsp;</div>
                    <div class="nextButton" style="width: 927px; border: 0">
                        <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="33" />
                        <img id="buttonNext" class="<?php echo count($tours_list) == 0 ? '' : 'wizardButton' ?>" onclick="javascript: takeAction('save');" src="../../../images/<?php echo count($tours_list) == 0 ? 'btn_next_disabled.png' : 'btn_next.png' ?>" width="89" height="33" />

                    </div>
                </div>


            </div>  <br class="clear"/>  
        </div> 

        <br class="clear"/> 
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>




        <div id="cover"></div>
        <div id="cover2"></div>
        <div id="noContent" title="Error!" style="text-align:center">
            <p>To use the Events module you need to add at least one event. If you don't need this module please click skip this module button.</p>
        </div>
        <div id="skipWarning" title="Warning!" style="text-align:center">
            <p>If you skip this module you cannot enter Events into your app at a later time. Your application will be submitted to Apple without a Events Module. Please acknowledge by pressing Accept or press Cancel to add Events.</p>
        </div>
        <div id="picCover1" class="photoAddOver" style="display: none"></div>
        <div id="picCover2" class="photoAddOver" style="display: none"></div>
        <script type="text/javascript">

            var toursCount = <?php echo isset($tours_list) ? count($tours_list) : '0'; ?>;
        </script>
        <div id="imageEdit" style="padding: 8px; border: #043F53 2px solid ; position: absolute; z-index: 100; display: none; background-color: white; width: 300px; height: 60px">
            <form id="pidEditForm" method="post" enctype="multipart/form-data">
                <div id="formRow">
                    <div class="tahoma_12_blue" id="formName">Flyer</div>
                    <div id="formSinFeild">
                        <input  name="flyerImage" id="flyerImage" type="file" class="textFeildBoarder" style="width:227px; height:21px;"/>
                    </div>
                </div>
                <div id="formRowButtons">
                    <div class="tahoma_12_blue" id="formName"></div>
                    <div id="formSinFeild">
                        <input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
                        <input type="image" src="../../../images/save.png" name="Login" id="Login"width="89" height="33"/>
                        <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel"width="99" height="33" onclick="hidePic(); return false;"/>
                    </div>
                </div>
            </form>
        </div>

        <div id="showPics" style="display: none">
            <div id="header" class="tahoma_14_white">Choose images from your flicker account
                <div style="float: right; cursor: pointer;" onclick="javascript: $('#showPics').hide(500);" ><img src="../../../images/item_delete.png"/></div>
            </div>

            <div id="bodyx" >Body</div>
        </div>
    </body>
</html>