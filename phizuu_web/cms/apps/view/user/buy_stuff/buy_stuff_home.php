<?php
require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');



$menu_item = 'buy_stuff';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>phizuu - Links</title>
        <style type="text/css">

        </style>

        <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>

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

                        <div class="tahoma_14_white" id="newsHeader">Tours Lists Section</div>

                    </div>
                    <div style="width: 14px; height: 20px" class="titleDivs tahoma_14_white"></div>
                    <div style="width: 300px; height: 20px" class="titleDivs tahoma_14_white">Title</div>
                    <div style="width: 573px; height: 20px" class="titleDivs tahoma_14_white">Link</div>

                    <ul id="linkSortable">
                        <?php
                        $linksArr = $popArr['links'];
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
                        <form id="form" name="form" method="post" style="display: none;" onsubmit="javascritp: return formSubmit();">
                            <div id="lightBlueHeader2">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Add New Buy Stuff</div>

                            </div>
                            <div id="addMusicBttn2">
                                <div id="errorRow" class="formRow tahoma_12_blue" style="display: none">
                                    <div id="errorMsg" ></div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Title</div>
                                    <input type="text" class="textFeildBoarder" name="title" id="title" style="width: 200px;padding-left: 5px"/>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName" >Link</div>
                                    <input type="text" class="textFeildBoarder" name="link" id="link" style="width: 300px; float: left; height: 20px;"/>
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

                </div>



            </div>

        </div>
        <br class="clear"/> 
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>

    </body>

</html>

<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
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
                        "margin":"45px 0 0 890px",
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
                $.post('BuyStuffController.php?action=order&'+order, function(data) {
                    $("#linkSortable").sortable( 'enable' );
                    $("#linkSortable").css('cursor', '');
                    $("#linkSortable .dragHandle").css('cursor', 'move');
                });
            });

            refreshEdits();
        });

        function refreshEdits() {
            var oldVal='';
            $('.edit').editable(function(value, settings) {
                var item = $(this);
                var arr = $(this).attr('id').split("_");
                var url = arr[0];
                
                if(url==2){
                    if(!validateLink(value)){
                        alert('Error! Invalid link format. Please prefix http:// or relavant scheme to the link.');
                        return oldVal;
                    }
                }
                if(url==1){
                    if(value.trim() ==""){
                        alert("Title can't be blank!");
                        return oldVal;
                    }
                }
                
                    item.html('Saving..');
                    $.post('../../../controller/modules/buy_stuff/BuyStuffController.php?action=edit',{'id':item.attr('id'),'value':value}, function(data){
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

            $.post("BuyStuffController.php?action=add_new", { "title": $("#title").val(), "link": $("#link").val() },
            function(data){
                //alert(data);
                if(data.status == 'ok') {
                    $("#linkSortable").append(data.line).slideDown('slow');
                    $('#form').hide();

                    refreshEdits();
                }
            }, 'json');
            return false;
        }

        function deleteItem(id) {
            //$("#newsSortable").
            var itemId = id;
            var item = $("#id_"+id);

            $.post("BuyStuffController.php?action=delete_link", { 'id': id },
            function(data){
                if (data!='ok') {
                    alert("Error! while deleting\n\n"+data);
                    $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                } else{
                    item.hide(500,function(){

                        document.getElementById('linkSortable').removeChild(document.getElementById('id_'+itemId));
                    });
                }
            });

            $('#id_'+itemId).children().css('background-color', 'pink');

        }
</script>
