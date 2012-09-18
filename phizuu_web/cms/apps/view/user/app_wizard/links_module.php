<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />


        <script type="text/JavaScript">


            function takeAction(action) {
                if (action=='skip') {
                    $("#skipWarning").dialog( "open" );
                } else if (action=='save') {
                    if (linkCount==0) {
                        $("#noContent").dialog( "open" );
                    } else {
                        window.location = "AppWizardControllerNew.php?action=links_module_save";
                    }
                }
            }

        </script>
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
        <script type="text/javascript" src="../../../js/jquery.tooltip.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
    
                $(".ttip div").mouseover(function(){
               
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                    $("#delete_"+id).mouseover(function(){
                   
                        $("#div_tooltip_common_"+id).css({
                            "margin":"45px 0 0 915px",
                            "padding":"10px 0 0 12px"
                        });
                        $("#div_tooltip_common_"+id).show();
                        $("#delete_"+id).mouseout(function(){
                            $("#div_tooltip_common_"+id).hide();
                        });
                    
                    });
                
                
                            
                });
                $("#linkSortable").sortable({handle : '.dragHandle'});

                $('#linkSortable').bind('sortupdate', function(event, ui) {
                    $("#linkSortable").sortable( 'disable' );
                    $("#linkSortable").css('cursor', 'wait');
                    $("#linkSortable .dragHandle").css('cursor', 'wait');
                    var order = $('#linkSortable').sortable('serialize');
                    $.post('../links/LinksController.php?action=order&'+order, function(data) {
                        $("#linkSortable").sortable( 'enable' );
                        $("#linkSortable").css('cursor', '');
                        $("#linkSortable .dragHandle").css('cursor', 'move');
                    });
                });

                refreshEdits();


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
                            window.location = "AppWizardControllerNew.php?action=links_module_skip";
                            $(this).dialog('close');
                        }
                    }
                });
            });

            function showAddView() {
                $('#errorRow').hide();
                document.getElementById("form").reset();
                $('#form').show(500);
            }

            function hideAddView() {
                $('#form').hide();
                return false;
            }

            function validateLink(url) {
                var regex = /(\w+)\:\/\/(\w+)\.(\w+)/;

                return url.match(regex);
            }

            function refreshEdits() {
                var oldVal='';
                $('.edit').editable(function(value, settings) {
                    var item = $(this);

                    if (validateLink(value)) {
                        item.html('Saving..');
                        $.post('../links/LinksController.php?action=edit',{'id':item.attr('id'),'value':value}, function(data){
                            item.html(data);
                        });
                    } else {
                        alert('Error! Invalid link format. Please prefix http:// or relavant scheme to the link.');
                        return oldVal;
                    }
                }, {
                    data: function(value, settings) {
                        oldVal = value;
                        return value;
                    },

                    indicator : 'Saving...',
                    tooltip   : 'Click to edit...'
                });
                $('.edit1').editable(function(value, settings) {
                    var item = $(this);

           
                    item.html('Saving..');
                    $.post('../links/LinksController.php?action=edit',{'id':item.attr('id'),'value':value}, function(data){
                        item.html(data);
                    });
           
                }, {
                    data: function(value, settings) {
                        oldVal = value;
                        return value;
                    },

                    indicator : 'Saving...',
                    tooltip   : 'Click to edit...'
                });
            }

            function formSubmit() {
                $('#errorRow').hide();
                if($("#title").val()=='' || $("#link").val()=='') {
                    $('#errorRow').html('Error! Title or Link field cannot be empty!');
                    $('#errorRow').show(500);
                    return false;
                }

                if (!validateLink($('#link').val())) {
                    $('#errorRow').html('Error! Invalid link format. Please prefix http:// or relavant scheme to the link');
                    $('#errorRow').show(500);
                    return false;
                }

                $.post("../links/LinksController.php?action=add_new", { "title": $("#title").val(), "link": $("#link").val() },
                function(data){

                    if(data.status == 'ok') {
                        linkCount++;
                        if (linkCount>0) {
                            $('#buttonNext').attr('src','../../../images/btn_next.png');
                            $('#buttonNext').addClass('wizardButton');
                        } else {
                            $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
                            $('#buttonNext').removeClass('wizardButton');
                        }
                        $("#linkSortable").slideDown('slow').append(data.line);
                        $('#form').hide();

                        refreshEdits();
                    }
                }, "json");
                return false;
            }

            function deleteItem(id) {
                //$("#newsSortable").
                var itemId = id;
                var item = $("#id_"+id);

                $.post("../links/LinksController.php?action=delete_link", { 'id': id },
                function(data){
                    if (data!='ok') {
                        alert("Error! while deleting\n\n"+data);
                        $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                    } else{
                        linkCount--;
                        if (linkCount>0) {
                            $('#buttonNext').attr('src','../../../images/btn_next.png');
                        } else {
                            $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
                        }
                        item.hide(500,function(){

                            document.getElementById('linkSortable').removeChild(document.getElementById('id_'+itemId));
                        });
                    }
                });

                $('#id_'+itemId).children().css('background-color', 'pink');

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

                        <div class="middle" style="width: 910px"><div style="margin-top: 5px;float: left">Please add your links here </div></div>

                    </div>

                    <div class="wizardSecondTitle" style="width: 100%">
                        You can add any number of links.
                    </div>

                    <p style="font-size: 6px;">&nbsp;</p>
                    <div style="width: 14px; height: 20px" class="titleDivs tahoma_14_white"></div>
                    <div style="width: 300px; height: 20px" class="titleDivs tahoma_14_white">Title</div>
                    <div style="width: 599px; height: 20px" class="titleDivs tahoma_14_white">Link</div>

                    <ul id="linkSortable">
                        <?php
                        $linksArr = $popArr['links'];

                        // This inclution is done because, when adding new item it should be appended to the list
                        // adding items uses AJAX
                        include 'supporting/new_line_sub_view.php';
                        ?>
                    </ul>
                    <p style="font-size: 6px;">&nbsp;</p>
                    <div id="addTourButton" style="padding-top: 5px">
                        <img style="cursor: pointer " src="../../../images/addNewLinks.png" width="141" height="33" onclick="showAddView();" />
                    </div>
                    <p style="font-size: 6px;">&nbsp;</p>
                    <div id="addDiv" style=" float: left; width: 948px">
                        <form id="form" name="form" method="post" style="display: none;" onsubmit="javascritp: return formSubmit();">
                            <div id="lightBlueHeader2">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Add New Link</div>

                            </div>
                            <div id="addMusicBttn2">
                                <div id="errorRow" class="formRow tahoma_12_blue" style="display: none">
                                    <div id="errorMsg" ></div>
                                </div>
                                <div id="formRow" style="height:40px">
                                    <div class="tahoma_12_blue" id="formName">Title</div>
                                    <input type="text" class="textFeildBoarder" name="title" id="title" style="width: 300px;height: 15px; font-size: 18px; padding: 5px; margin-bottom: 10px;height: 15px"/>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName" >Link</div>
                                    <input value="http://" type="text" class="textFeildBoarder" name="link" id="link" style="width: 300px; float: left; height: 13px; padding: 5px"/>
                                </div>
                                <div id="formRowButtons">
                                    <div class="tahoma_12_blue" id="formName"></div>
                                    <div id="formSinFeild">
                                        <input type="hidden" name="count" id="count" value="29" />
                                        <input type="image" src="../../../images/save.png" name="Login" id="Login"width="69" height="33"/>
                                        <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel"width="99" height="33" onclick="javascript: return hideAddView();"/>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <p style="font-size: 6px;">&nbsp;</p>
                    <?php
                    $showButton = (count($linksArr) > 0) ? true : false;
                    ?>


                    <div class="nextButton" style="width: 951px; border: 0">
                        <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="33" />
                        <img id="buttonNext" class="<?php echo!$showButton ? '' : 'wizardButton' ?>" onclick="javascript: takeAction('save');" src="../../../images/<?php echo!$showButton ? 'btn_next_disabled.png' : 'btn_next.png' ?>" width="89" height="33" />

                    </div>

                </div>


            </div>
        </div>

        <br class="clear"/>  <br class="clear"/> 
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>





        <div id="cover"></div>
        <div id="cover2"></div>
        <div id="noContent" title="Error!" style="text-align:center">
            <p>To use the Links module you need to add at least one link. If you don't need this module please click skip this module button.</p>
        </div>
        <div id="skipWarning" title="Warning!" style="text-align:center">
            <p>If you skip this module you cannot enter Links into your app at a later time. Your application will be submitted to Apple without a Links Module. Please acknowledge by pressing Accept or press Cancel to add Links.</p>
        </div>
        <div id="picCover1" class="photoAddOver" style="display: none"></div>
        <div id="picCover2" class="photoAddOver" style="display: none"></div>
        <script type="text/javascript">

            var linkCount = <?php echo isset($linksArr) ? count($linksArr) : '0'; ?>;
        </script>
    </body>
</html>