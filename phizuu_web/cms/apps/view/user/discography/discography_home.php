<?php
require_once("../../../controller/session_controller.php");

$menu_item = 'discography';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>phizuu - Discography</title>
        <style type="text/css">
            .buyLinkRow {
                height: 20px; padding: 8px; float: left; background-color: #F3F3F3; margin-bottom: 1px;
            }
        </style>

        <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

    </head>

    <body>
        <div id="header">
            <div id="headerContent">
                <?php include("../../../view/user/common/header.php"); ?>
            </div>
            <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
        </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">

                <?php include("../../../view/user/common/navigator.php"); ?>
                <div id="body">
                    <div id="lightBlueHeader2">

                        <div class="tahoma_14_white" id="newsHeader">Discography Lists Section</div>

                    </div>
                    <div style="width: 14px; height: 20px" class="titleDivs tahoma_14_white"></div>
                    <div style="width: 125px; height: 20px" class="titleDivs tahoma_14_white">Title</div>
                    <div style="width: 253px; height: 20px" class="titleDivs tahoma_14_white">Info</div>
                    <div style="width: 247px; height: 20px" class="titleDivs tahoma_14_white">Details</div>
                    <div style="width: 60px; height: 20px" class="titleDivs tahoma_14_white">Buy Links</div>
                    <div style="width: 100px; height: 20px" class="titleDivs tahoma_14_white">Image</div>
                    <div style="width: 24px; height: 20px" class="titleDivs tahoma_14_white"></div>

                    <ul id="discographySortable">
                        <?php
                        $discoArr = $popArr['discographies'];
                        // This inclution is done because, when adding new item it should be appended to the list
                        // adding items uses AJAX
                        include 'new_line_sub_view.php';
                        ?>
                    </ul>
                    <p style="font-size: 6px;">&nbsp;</p>
                    <div id="addTourButton">
                        <img style="cursor: pointer " src="../../../images/add_new.png" width="99" height="33" onclick="showAddView();" />
                    </div>
                    <p style="font-size: 6px;">&nbsp;</p>
                    <div id="addDiv" style=" float: left; width: 948px">
                        <form id="form" action="DiscographyController.php?action=add_new_item" name="form" method="post" style="display: none;" onsubmit="javascritp: return formSubmit();" enctype="multipart/form-data">
                            <div id="lightBlueHeader2">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle2" >Add New Link</div>

                            </div>
                            <div id="addMusicBttn2">
                                <div id="errorRow" class="formRow tahoma_12_blue" style="display: none">
                                    <div id="errorMsg" ></div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName" >Title</div>
                                    <input type="text" class="textFeildBoarder" name="title" id="titleInput" style="width: 300px; float: left; height: 20px;"/>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName" >Info</div>
                                    <input type="text" class="textFeildBoarder" name="info" id="info" style="width: 300px; float: left; height: 20px;"/>
                                </div>
                                <div id="formRow" style="height: 85px">
                                    <div class="tahoma_12_blue" id="formName" >Details</div>
                                    <textarea class="textFeildBoarder" name="details" id="details" style="width: 300px; float: left; height: 70px;"></textarea>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName" >Image</div>
                                    <input type="file" class="textFeildBoarder" name="image" id="image" style="width: 300px; float: left; height: 20px;"/>
                                </div>
                                <div style="float: left; width: 800px;">
                                    <div class="tahoma_12_blue" id="formName" >Buy URLs</div>
                                    <div style="float: left; width: 700px; padding: 10px; border: 1px solid #C0C0C0; margin-bottom: 10px;">
                                        <div style="float: left; width: 700px;" id="buyLinkContainerDiv">
                                        </div>
                                        <div style="width: 620px; float: left; padding: 0px 10px 0px 10px;">
                                            <div style="float: right; padding-right: 10px">
                                                <input type="button"  value="Add New" onclick="javascript: addNewBuyLink();"/>
                                            </div>
                                        </div>
                                    </div>
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
                </div>
            </div> <br class="clear"/> <br class="clear"/>
        </div>
        <br class="clear"/>
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>

        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>

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



                $(document).ready(function() {
        
                    $(".ttip div").mouseover(function(){
               
                        var arr = $(this).attr('id').split("_");;
                        var id = arr[1];
                    
                        $("#delete_"+id).mouseover(function(){
                   
                            $("#div_tooltip_common_"+id).css({
                                "margin":"35px 0 0 895px",
                                "padding":"10px 0 0 12px"
                            });
                            $("#div_tooltip_common_"+id).show();
                            $("#delete_"+id).mouseout(function(){
                                $("#div_tooltip_common_"+id).hide();
                            });
                    
                        });
                
                
                            
                    });
            
                    $("#discographySortable").sortable({handle : '.dragHandle'});

                    $('#discographySortable').bind('sortupdate', function(event, ui) {
                        $("#discographySortable").sortable( 'disable' );
                        $("#discographySortable").css('cursor', 'wait');
                        $("#discographySortable .dragHandle").css('cursor', 'wait');
                        var order = $('#discographySortable').sortable('serialize');
                        $.post('DiscographyController.php?action=order&'+order, function(data) {
                            $("#discographySortable").sortable( 'enable' );
                            $("#discographySortable").css('cursor', '');
                            $("#discographySortable .dragHandle").css('cursor', 'move');
                        });
                    });

                    $('.edit').editable('DiscographyController.php?action=edit',{
                        indicator : 'Saving...',
                        tooltip   : 'Click to edit...',
                        submit : 'Change',
                        type   : 'textarea',
                        cancel : 'Cancel',
                        height: 80,
                        data: function(value, settings) {
                            /* Convert <br> to newline. */
                            var retval = value.replace(/<br[\s\/]?>/gi, '\n');
                            return retval;
                        }

                    });

                    $('.edit_text').editable('DiscographyController.php?action=edit',{
                        indicator : 'Saving...',
                        tooltip   : 'Click to edit...'
                    });

                    $("#buyLinkEditDiv").dialog({
                        modal: true,
                        autoOpen: false,
                        width: 800,
                        height: 450,
                        resizable: false,
                        buttons: {
                            "OK": function() {
                                $(this).dialog('close');
                            },
                            "Add New": function() {
                                $("#addNewBuyLinkDiv input").val('');
                                $("#addNewBuyLinkDiv").dialog('open');
                            }

                        }
                    });

                    $("#addNewBuyLinkDiv").dialog({
                        modal: true,
                        autoOpen: false,
                        width: 400,
                        height: 150,
                        resizable: false,
                        buttons: {
                            "Cancel": function() {
                                $(this).dialog('close');
                            },
                            "Add": function() {
                                if ($('#buyURLTitleInput').val()=='' || $('#buyURLLinkInput').val()=='') {
                                    $('#errorDialog').html('Both Title and Link <b>should not</b> be empty!');
                                    $('#errorDialog').dialog('open');
                                    return;
                                }

                                if (!validateLink($('#buyURLLinkInput').val())) {
                                    alert('Error! The link is invalid. Please prefix http:// or relavant scheme to the link.');
                                    return;
                                }
                    
                                disableButton('buyLinkAddButton', 'Adding..');
                                $.post("DiscographyController.php?action=add_new_buy_link_ajax", { 'id': editingBuyLinkId, 'title':$('#buyURLTitleInput').val(), 'link':$('#buyURLLinkInput').val() },
                                function(data){
                                    $('#buyLinkContDiv').append(data);
                                    refreshEditsInBuyURLDialog();
                                    enableButton('buyLinkAddButton', 'Add');
                                    $("#addNewBuyLinkDiv").dialog('close');
                                });
                            }
                        }
                    });
        
                    $("#showEditImageDiv").dialog({
                        modal: true,
                        autoOpen: false,
                        width: 265,
                        height: 350,
                        resizable: false,
                        buttons: {
                            "Cancel": function() {
                                $(this).dialog('close');
                            },
                            "Change": function() {
                                if ($('#changeImageUploadImage').val()=='') {
                                    $('#errorDialog').html('Please select a file to be use as the image or click cancel to close the preview.');
                                    $('#errorDialog').dialog('open');
                                    return;
                                }

                                if ( !$('#changeImageUploadImage').val().match(/((.jpg)|(.jpeg)|(.gif)|(.png))$/)) {
                                    $('#errorDialog').html('Error! Image file should be one of jpg, gif or png file!');
                                    $('#errorDialog').dialog('open');

                                    return;
                                }

                                $('#imageUpdateForm').attr('action','DiscographyController.php?action=update_image&id='+imageEditId)
                                disableButton('changeImageButton', 'Uploading..');
                                document.getElementById('imageUpdateForm').submit();
                            }
                        }
                    });

                    $('.ui-dialog-buttonpane button:contains(Add)').attr("id","buyLinkAddButton");
                    $('.ui-dialog-buttonpane button:contains(Add New)').attr("id","buyLinkAddNewButton");
                    $('.ui-dialog-buttonpane button:contains(Change)').attr("id","changeImageButton");
        

                    $("#errorDialog").dialog({
                        modal: true,
                        autoOpen: false,
                        width: 360,
                        resizable: false,
                        buttons: {
                            Ok: function() {
                                $(this).dialog('close');
                            }
                        }
                    });
<?php
if (isset($_GET['message'])) {
    $msg = $_GET['message'];
    ?>
                                $('#errorDialog').html('<?php echo $msg ?>');

                                $('#errorDialog').dialog('open');
    <?php
}
?>

                            addNewBuyLink();
                        });

                        function disableButton(id, text) {
                            $('#' + id).addClass('ui-state-disabled');
                            $('#' + id).attr("disabled", true);
                            $('#' + id).html(text);
                        }

                        function enableButton(id, text) {
                            $('#' + id).removeClass('ui-state-disabled');
                            $('#' + id).attr("disabled", false);
                            $('#' + id).html(text);
                        }

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

                        function validateLinks() {
                            var validity = true;
                            $("#buyLinkContainerDiv .link").each(function(){
                                if ($(this).val()!='' && !validateLink($(this).val())) {
                                    validity = false;
                                }
                            });

                            return validity;
                        }

                        function formSubmit() {
                            $('#errorRow').hide();
                            if($("#titleInput").val()=='' || $("#image").val()=='') {
                                $('#errorRow').html('Error! Title or Image fields cannot be empty!');
                                $('#errorRow').show(500);
                                return false;
                            }

                            if (!validateLinks()) {
                                $('#errorRow').html('Error! One or more links are invalid. Please prefix http:// or relavant scheme to the link.');
                                $('#errorRow').show(500);
                                return false;
                            }
        
                            if ( !$("#image").val().match(/((.jpg)|(.jpeg)|(.gif)|(.png))$/)) {
                                $('#errorRow').html('Error! Image file should be one of jpg, gif or png file!');
                                $('#errorRow').show(500);
                                return false;
                            }
                            return true;
                        }

                        function deleteItem(id) {
                            //$("#newsSortable").
                            var itemId = id;
                            var item = $("#id_"+id);

                            $.post("DiscographyController.php?action=delete_item", { 'id': id },
                            function(data){
                                if (data!='ok') {
                                    alert("Error! while deleting\n\n"+data);
                                    $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                                } else{
                                    item.hide(500,function(){
                                        item.remove();
                                    });
                                }
                            });

                            $('#id_'+itemId).children().css('background-color', 'pink');
                        }


                        function addNewBuyLink() {
                            var linkText = '<div style="float: left; width: 700px; padding-bottom:10px" class="buyLinkItemDiv"><div class="tahoma_12_blue" style="float: left; width: 35px; ">Title: </div><input type="text" class="textFeildBoarder" name="buyURLTitle[]" style="width: 200px; float: left; height: 20px;"/><div class="tahoma_12_blue" style="float: left; width: 35px; padding-left: 35px ">Link: </div><input type="text" class="textFeildBoarder link" name="buyURLLink[]" style="width: 300px; float: left; height: 20px;"/><div style="float: left; padding-left: 10px"><input type="button"  value="Delete" onclick="javascript: deleteBuyLinkInput(this);"/></div></div>';
                            $(linkText).appendTo("#buyLinkContainerDiv").hide().slideDown(300);
                        }

                        function deleteBuyLinkInput(item) {
                            $(item).parents('.buyLinkItemDiv').slideUp(300, function(){$(item).parents('.buyLinkItemDiv').remove();});
                        }

                        var editingBuyLinkId = null;

                        function showBuyURLEdit(id) {
                            $('#buyLinkContDiv').html("<img src='../../../images/bigrotation2.gif' height=32 width=32/>");
                            $("#buyLinkEditDiv").dialog("open");

                            editingBuyLinkId = id;
                            disableButton('buyLinkAddNewButton', 'Loading..');
                            $.post("DiscographyController.php?action=get_buy_links_ajax", { 'id': id },
                            function(data){
                                $('#buyLinkContDiv').html(data);
                                refreshEditsInBuyURLDialog();
                                enableButton('buyLinkAddNewButton', 'Add New');
                            });

                            return false;
                        }

                        function refreshEditsInBuyURLDialog() {
                            var oldVal='';
                            $('.edit_buy').editable(function(value, settings) {
                                var item = $(this);
                                var arr = $(this).attr('id').split("_");
                                var url = arr[0];
                                
                                if(url=='link'){
                                    if(!validateLink(value)){
                                        alert('Error! Invalid link format. Please prefix http:// or relavant scheme to the link.');
                                        return oldVal;
                                    }
                                }
                                if(url=='title'){
                                    if(value.trim() ==""){
                                        alert("Title can't be blank!");
                                        return oldVal;
                                    }
                                }
                                
                                    item.html('Saving..');
                                    $.post('../../../controller/modules/discography/DiscographyController.php?action=edit_buy_link',{'id':item.attr('id'),'value':value}, function(data){
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

                        function deleteBuyLink(id) {
                            var itemId = id;
                            var item = $("#id_buy_link"+id);

                            $.post("DiscographyController.php?action=delete_buy_link", { 'id': id },
                            function(data){
                                if (data!='ok') {
                                    alert("Error! while deleting\n\n"+data);
                                    $('#id_buy_link'+itemId).children().css('background-color', '#F3F3F3');
                                } else{
                                    item.hide(500,function(){
                                        item.remove();
                                    });
                                }
                            });

                            $('#id_buy_link'+itemId).children().css('background-color', 'pink');
                        }

                        var imageEditId = null;

                        function showImageEdit(id, url) {
                            var newDate = new Date();
                            $('#fullImage').attr('src', url+'?prevent_cache='+newDate.getTime());
                            $('#showEditImageDiv').dialog('open');
                            imageEditId = id;
                        }

        </script>

        <div id="errorDialog" title="Error!"></div>
        <div id="buyLinkEditDiv" title="Show/Edit Buy Links">
            <div style="width: 780px; float: left; ">
                <div style="width: 200px; height: 20px" class="titleDivs tahoma_14_white">Title</div>
                <div style="width: 548px; height: 20px" class="titleDivs tahoma_14_white">Link</div>
            </div>
            <div id="buyLinkContDiv" style="width: 778px; float: left; height: 339px; overflow-y: scroll; overflow-x: hidden; border: 1px solid #043F53">

            </div>
            <div id="addNewBuyLinkDiv" title="Add New Buy Link">
                <table border="0" cellpadding="5px" cellspacing="0px">
                    <tr>
                        <td width="40px">Title</td>
                        <td>
                            <input type="text" class="textFeildBoarder" name="title" id="buyURLTitleInput" style="width: 300px; float: left; height: 20px;"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Link</td>
                        <td>
                            <input type="text" class="textFeildBoarder" name="title" id="buyURLLinkInput" style="width: 300px; float: left; height: 20px;"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="showEditImageDiv" title="Add New Buy Link">
                <form action=""  name="form" method="post" id="imageUpdateForm" enctype="multipart/form-data">
                    <table border="0" cellpadding="5px" cellspacing="0px" >
                        <tr>
                            <td  align="center">
                                <img id="fullImage" src="" width="225" height="225"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="file" class="textFeildBoarder" name="image" id="changeImageUploadImage" style="width: 215px; float: left; height: 20px;"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>

</html>
