
<?php
$news = new News();
$lid = '2';
$rss_list = $news->getRssFeed($lid);
if (sizeof($rss_list) > 0) {
    $rss_stat = "1";
} else {
    $rss_stat = "0";
}


foreach ($rss_list as $rss_one) {
    $rss_val = $rss_one->value;
    $rss_id = $rss_one->id;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

        <script type="text/JavaScript">
            function deleteItem(id) {
                //$("#newsSortable").
                var itemId = id;
                var item = $("#id_"+id);

                $.post("../../../controller/news_all_controller.php?action=delete_news", { 'id': id },
                function(data){
                    if (data!='ok') {
                        alert("Error! while deleting\n\n"+data);
                        $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                    } else{
                        item.hide(500,function(){
                            document.getElementById('newsSortable').removeChild(document.getElementById('id_'+itemId));
                            newsCount--;
                            if(newsCount==0) {
                                $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
                            }
                        });
                    }

                });

                $('#id_'+itemId).children().css('background-color', 'pink');

            }

            var submitRSSForm = false;

            function submitRSS() {
                var val = document.getElementById('txtRss').value;

                if(val==''){
                    document.getElementById('rssForm').submit();
                }

                if(val.substring(0, 7)!='http://' && val.substring(0, 7)!='') {
                    if (confirm("News feed URL should start with http://. Are you sure want to use this URL?")){
                        submitRSSForm = true;
                        validateRSS();
                    }
                } else {
                    submitRSSForm = true;
                    validateRSS();
                }

                return false;
            }

            function validateRSS() {
                var url = $("#txtRss").val();
                if(url!='') {
                    $("#rssResDiv").html("Checking..");
                    $.post("../../../controller/news_all_controller.php?action=validate_rss", { 'url': url },
                    function(data){
                        if (data=='RSS') {
                            var image = "../../../images/wizard_dot_grn.png";
                            var title = "Valid RSS feed"
                            if (submitRSSForm) {
                                document.getElementById('rssForm').submit();
                            }
                        } else if (data=='ATOM'){
                            var image = "../../../images/wizard_dot_grn.png";
                            var title = "Valid ATOM feed"
                            if (submitRSSForm) {
                                document.getElementById('rssForm').submit();
                            }
                        } else if (data=='INV'){
                            var image = "../../../images/wizard_dot_red.png";
                            var title = "Invalid RSS feed URL!"
                            alert (title);
                        } else {
                            alert("Error! while checking feed\n\n"+data);
                        }

                        $("#rssResDiv").html("<img src='" + image + "' alt='"+title+"' title='"+title+"'/>");
                        submitRSSForm = false;
                    });
                }
            }


            function takeAction(action) {
                if (action=='skip') {
                    $("#skipWarning").dialog( "open" );
                } else if (action=='save') {
                    if(<?php echo (isset($rss_val) && $rss_val != '') ? 'false' : 'true'; ?>){
                        if (newsCount==0) {
                            $("#noContent").dialog( "open" );
                        } else {
                            window.location = "AppWizardControllerNew.php?action=news_module_save";
                        }
                    } else {
                        window.location = "AppWizardControllerNew.php?action=news_module_save";
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
                            "margin":"45px 0 0 921px",
                            "padding":"10px 0 0 12px"
                        });
                        $("#div_tooltip_common_"+id).show();
                        $("#delete_"+id).mouseout(function(){
                            $("#div_tooltip_common_"+id).hide();
                        });
                    
                    });
                
                
                            
                });
                $("#newsSortable").sortable({handle : '.dragHandle'});


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
                            window.location = "AppWizardControllerNew.php?action=news_module_skip";
                            $(this).dialog('close');
                        }
                    }
                });
                //$("#tourSortable").disableSelection();
                /*$('#tourSortable').sortable({
                update: function(event, ui) { alert('er'); }
            });*/
                $('#newsSortable').bind('sortupdate', function(event, ui) {
                    $("#newsSortable").sortable( 'disable' );
                    $("#newsSortable").css('cursor', 'wait');
                    $("#newsSortable .dragHandle").css('cursor', 'wait');
                    var order = $('#newsSortable').sortable('serialize');
                    $.post('../../../controller/news_all_controller.php?action=order&'+order, function(data) {
                        $("#newsSortable").sortable( 'enable' );
                        $("#newsSortable .dragHandle").css('cursor', 'move');
                        $("#newsSortable").css('cursor', '');
                    });


                });


                // wait for form submission
                $("#form").submit(function() {
                    // get the input element and text
                    var name = $("#title");
                    var date = $("#date");
                    var notes = $("#notes");
                    var count = $("#count");

                    var txt_name = name.val().trim();
                    var txt_date = date.val().trim();
                    var txt_notes = notes.val().trim();
                    var txt_count = count.val();
                    var txt_count1 =parseInt(txt_count)+1;

                    // check if text was entered
                    if(txt_name.length > 0 ) {


                        //post data to process.php and get json
                        
                        $.post('../../../controller/news_newline_controller.php', {name: txt_name,date: txt_date,notes: txt_notes}, function(data) {
                            var element = $('<li>' + data.text + '</li>');
                            //element.prependTo("#queue").slideDown();
                            element.appendTo("#newsSortable").slideDown();
                            // clear input field
                            name.val('');
                            date.val('');
                            notes.val('');
                            count.val(txt_count1);
                            document.getElementById('buttonContainer').style.display="none";

                            newsCount++;
                            showEdits();
                            if (newsCount!=0) {
                                $('#buttonNext').attr('src','../../../images/btn_next.png');
                                $('#buttonNext').addClass('wizardButton');
                            } else {
                                $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
                                $('#buttonNext').removeClass('wizardButton');
                            }
                        }, 'json');

                    }

                    // prevent default form action
                    return false;
                });


                showEdits();


            });


            function calendar(btn,id,div_id){
                var cal = Calendar.setup({
                    onSelect: function(cal) {cal.hide()
                        var selectionObject = cal.selection;
                        var selectedDate = selectionObject.get();
                        if(selectedDate != ""){


                            var date=selectionObject.print("%Y-%m-%d");
                            $.post('../../../controller/news_inline_controller.php', {id: div_id,value: date}, function(data) {

                                // document.getElementById(div_id).innerHTML=selectionObject.print("%Y-%m-%d");
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
            function showEdits(){
                $(".click").editable("../../../controller/news_inline_controller.php", {
                    indicator : "Saving..",
                    tooltip   : "Click to edit...",
                    style  : "inherit"
                });

                $(".editable_textarea").editable("../../../controller/news_inline_controller.php", {
                    indicator : "Saving..",
                    tooltip   : "Click to edit...",
                    style  : "inherit",
                    type: "textarea",
                    select : true,
                    onblur : 'submit'

                });

                $('.editable_textarea').tooltip({
                    delay: 0,
                    showURL: true,
                    showBody: " - ",
                    track: true,
                    fade: 250,
                    opacity: 0.85,
                    bodyHandler: function() {

                        if (this.innerHTML.substring(0,5)!='<form' && this.innerHTML != ''){
                            return this.innerHTML.replace(/\n/g,"<br/>");
                        }
                    }
                });
            }

            function show_div(){
                document.getElementById('buttonContainer').style.display="inline";
            }

        </script>
        <!--calendar-->
        <script src="../../../js/calendar/jscal2.js"></script>
        <script src="../../../js/calendar/en.js"></script>
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



        <link href="../../../css/swf_up/default.css" rel="stylesheet" type="text/css" />


    </head>


    <body onload="validateRSS();">

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
                    <div id="bodyNews">
                        <div class="wizardTitle" >

                            <div class="middle" style="width: 910px;padding-top: 5px;height: 27px">Please add your news here</div>

                        </div>

                        <div class="wizardSecondTitle">
                            You can add any number of news items. Alternatively, you can provide valid RSS feed URL as the news source.
                        </div>

                        <div id="lightBlueHeader2">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">News Lists Section</div>

                        </div>

                        <div id="titleBoxNewsBox">
                            <div class="tahoma_14_white" id="titleNews">Name</div>
                            <div class="tahoma_14_white" id="dateNews">Date</div>
                            <div class="tahoma_14_white" id="descriptionNews">Description</div>
                        </div>
                        <ul id="newsSortable" class="tahoma_12_blue">
                            <?php
                            if (isset($rss_val) && $rss_val != '') {
                                echo "<li>News stories section is disabled since you have entered RSS feed URL. Clear RSS url to enable News stories</li>";
                            } else {
                                include('supporting/list_news1.php');
                            }
                            ?>
                        </ul>

                    </div>
                    <div id="buttonContainer1">
                        <div id="addMusicBttn2_hide">
                            <div id="addTourButton" style="cursor: pointer"><img style="<?php echo isset($rss_val) && $rss_val != '' ? 'display:none' : ''; ?> " src="../../../images/addNews1.png" width="141" height="33" onclick="show_div();" /></div>
                        </div>
                    </div>
                    <div id="buttonContainer"  style="display:none">
                        <form id="form" name="form" method="post">
                            <div id="lightBlueHeader2">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Add News Section</div>

                            </div>
                            <div id="addMusicBttn2">
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Name</div>
                                    <div id="formSinFeild">
                                        <input type="text" class="textFeildBoarder" style="width: 225px;padding-left: 0px;height: 14px" name="title" id="title" /><?php if (isset($_REQUEST['msg_error'])) {
                                echo $msg_error;
                            } ?>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Date</div>
                                    <div id="formSinFeild">
                                        <input type="Text" id="date" maxlength="25" size="25" class="textFeildBoarder" style="width:227px; height:21px;" readonly="readonly"><img src="../../../images/cal.gif" id="f_btn1"  onclick="calendar_add();" onMouseOver="calendar_add();" />
                                    </div>
                                </div>
                                <div id="formRowMulti">
                                    <div class="tahoma_12_blue" id="formName">Description</div>
                                    <div id="formMultiFeild">
                                        <textarea class="textFeildBoarder" style="width:227px; height:100px;" name="notes" rows="5" id="notes"></textarea>
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
                    <div id="buttonContainer">
                        <form  action="../../../controller/news_add_iphone_controller.php?wizard=yes"  name="form2" id="rssForm" method="post">

                            <div id="lightBlueHeader2">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Add News RSS Feed</div>

                            </div>
                            <div style="width:700px">

                                <div class="tahoma_12_blue" id="formName" style="width:200px">Alternative News RSS Feed</div>
                                <div id="formSinFeild"  style="width:400px">
                                    <input name="txtRss" id="txtRss" type="text" class="textFeildBoarder" style="width:227px; height:21px;float: left" value="<?php if (sizeof($rss_list) > 0) {
                                echo $rss_val;
                            } ?>" /><div class="tahoma_12_blue" id="rssResDiv" style="float: left; width: 100px; padding-left: 4px"></div>             <?php //if (sizeof($rss_list)>0){ echo 'readonly="readonly"';} ?>
                                    <input name="txtRssId" type="hidden" style="width:227px; height:21px;" value="<?php if (sizeof($rss_list) > 0) {
                                echo $rss_id;
                            } ?>"  />
                                    <input name="status" type="hidden" style="width:227px; height:21px;" value="RssStatus"/>
                                    <input name="stat" type="hidden" style="width:227px; height:21px;" value="<?php echo $rss_stat; ?>"/>

                                </div>

                                <div id="formRowButtons">

                                    <div id="formSinFeild">

                                        <img src="../../../images/save.png" style="cursor: pointer" name="Login" id="Login" width="69" height="33" onclick="javascript: return submitRSS();"/>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
<?php
$showButton = ((isset($rss_val) && $rss_val != '') || count($news_list) > 0) ? true : false;
?>


                    <div class="nextButton" style="width: 927px; border: 0">
                        <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="33" />
                        <img id="buttonNext" class="<?php echo!$showButton ? '' : 'wizardButton' ?>" onclick="javascript: takeAction('save');" src="../../../images/<?php echo!$showButton ? 'btn_next_disabled.png' : 'btn_next.png' ?>" width="89" height="33" />

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
            <p>To use the News module you need to add at least one news. If you don't need this module please click skip this module button.</p>
        </div>
        <div id="skipWarning" title="Warning!" style="text-align:center">
            <p>If you skip this module you cannot enter News into your app at a later time. Your application will be submitted to Apple without a News Module. Please acknowledge by pressing Accept or press Cancel to add News.</p>
        </div>

        <div id="picCover1" class="photoAddOver" style="display: none"></div>
        <div id="picCover2" class="photoAddOver" style="display: none"></div>
        <script type="text/javascript">

            var newsCount = <?php echo isset($news_list) ? count($news_list) : '0'; ?>;
        </script>
    </body>
</html>