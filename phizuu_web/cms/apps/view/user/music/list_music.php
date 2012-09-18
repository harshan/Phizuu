<?php
include("../../../config/config.php");
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/music_controller.php');
include('../../../model/music_model.php');
include('../../../config/error_config.php');
include('../../../controller/limit_files_controller.php');
include('../../../model/limit_files_model.php');

$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'music');

//session_start();
$bmusic= new Music();
$bank_music = $bmusic->listBankMusic($_SESSION['user_id']);
$count=1;
$imusic= new Music();
$iphone_music = $imusic->listIphoneMusic($_SESSION['user_id']);
$icount=1;

$box_music_user = $imusic->getBoxAccount($_SESSION['user_id']);
if(sizeof($box_music_user) >0){
$_SESSION['box_user']=$box_music_user->user;
$_SESSION['box_pwd']=$box_music_user->password;
}

include('../../../controller/boxnet/box_config.php');

// Get Ticket to Proceed

$ticket_return = $box->getTicket ();

if ($box->isError()) {
     $box->getErrorMsg();
} else {
	
	$ticket = $ticket_return['ticket'];

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body,html {
    color:#333;
    font-family:Calibri;
    font-size:11px;
}
.panel {float:left;width:200px;margin:20px;}
/*ash color ul section -#ccc */
ul {
    list-style-type:none;
    border:1px solid #999;
    background:#ccc;
    padding:20px;
    min-height:150px;
    width:400px;
}

li {
    display:block;
    border:1px solid #999;
    background:#fff;
    width:80px;
    padding:5px 10px;
    margin-bottom:5px;
}

.dds_selected {
    background:#ffc;
}
.dds_ghost {
    opacity:0.5;
}
.dds_move {
    background:#cfc;
}
.dds_hover {
    background:#fc9;
    border:3px dashed #c96;
}

.holder {
    border:3px dashed #333;
    background:#fff;
}

</style>
   
<link href="../../../css/swf_up/default.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../../js/swf_up/swfupload.js"></script>
<script type="text/javascript" src="../../../js/swf_up/swfupload.queue.js"></script>
<script type="text/javascript" src="../../../js/swf_up/fileprogress.js"></script>
<script type="text/javascript" src="../../../js/swf_up/handlers.js"></script>
<script type="text/javascript">
		var upload1, upload2;
		window.onload = function() {
		
			upload1 = new SWFUpload({
				// Backend Settings
				upload_url: "../../../controller/upload_controller.php",
				post_params: {"PHPSESSID" : "<?php echo "1"; ?>","auth_token" : "<?php echo $_SESSION['auth_token']; ?>","api_key" : "<?php echo  $_SESSION['api_key']; ?>","user_id" : "<?php echo  $_SESSION['user_id']; ?>"},

				// File Upload Settings
				file_size_limit : "102400",	// 100MB
				file_types : "*.mp3",
				file_types_description : "MP3 Files",
				file_upload_limit : "10",
				file_queue_limit : "0",

				// Event Handler Settings (all my handlers are in the Handler.js file)
				file_dialog_start_handler : fileDialogStart,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "../../../images/XPButtonUploadText_61x22.png",
				button_placeholder_id : "spanButtonPlaceholder1",
				button_width: 61,
				button_height: 22,
				
				// Flash Settings
				flash_url : "../../../flash/swf_up/swfupload.swf",
				

				custom_settings : {
					progressTarget : "fsUploadProgress1",
					cancelButtonId : "btnCancel1"
				},
				
				// Debug Settings
				debug: false
			});
			
	     }
	</script>

   <script type="text/javascript">
        var GB_ROOT_DIR = "../../../js/greybox/";
    </script>
	<script type="text/javascript" src="../../../js/mootools.js"></script>
    <script type="text/javascript" src="../../../js/AJS.js"></script>
    <script type="text/javascript" src="../../../js/AJS_fx.js"></script>
    <script type="text/javascript" src="../../../js/gb_scripts.js"></script>
    <link href="../../../css/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
    
    <!--multi drag-->
    <script type="text/javascript" src="../../../js/Select%20and%20drag_files/jquery.js"></script>
	<script type="text/javascript" src="../../../js/Select%20and%20drag_files/jquery-ui.js"></script>
<script type="text/javascript">
/**
 *   Multi-Select And Drag
 *  
 *   Not elegant solution to this problem, but the problem, despite being easily 
 *   desribed is not simple. This code is more a proof of concept, but should be
 *   extendable by anyone with the time / inclination, there I grant permission 
 *   for it to be re-used in accodance with the MIT license:
 *
 *   Copyright (c) 2009 Chris Walker (http://thechriswalker.net/)
 *
 *   Permission is hereby granted, free of charge, to any person obtaining a copy
 *   of this software and associated documentation files (the "Software"), to deal
 *   in the Software without restriction, including without limitation the rights
 *   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *   copies of the Software, and to permit persons to whom the Software is
 *   furnished to do so, subject to the following conditions:
 *
 *   The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *   THE SOFTWARE.
 */
(function($){
    $.fn.drag_drop_selectable = function( options ){
        $.fn.captureKeys();
        var $_this = this;
        var settings = $.extend({},$.fn.drag_drop_selectable.defaults,options||{});
        return $(this).each(function(i){
            var $list = $(this);
            var list_id = $.fn.drag_drop_selectable.unique++;
            $.fn.drag_drop_selectable.stack[list_id]={"selected":[ ],"all":[ ]};//we hold all as well as selected so we can invert and stuff...
            $list.attr('dds',list_id);
            $.fn.drag_drop_selectable.settings[list_id] = settings;
            $list.find('li')
            //make all list elements selectable with click and ctrl+click.
            .each(function(){
                var $item = $(this);
                //add item to list!
                var item_id = $.fn.drag_drop_selectable.unique++;
				
                $item.attr('dds',item_id);
                $.fn.drag_drop_selectable.stack[list_id].all.push(item_id);
                $(this).bind('click.dds_select',function(e){
                    if($.fn.isPressed(CTRL_KEY) || ($.fn.drag_drop_selectable.stack[$.fn.drag_drop_selectable.getListId( $(this).attr('dds') )].selected.length == 1 && $(this).hasClass('dds_selected'))){
                        //ctrl pressed add to selection
                        $.fn.drag_drop_selectable.toggle(item_id);
                    }else{
                        //ctrl not pressed make new selection
                        $.fn.drag_drop_selectable.replace(item_id);
                    }
                }).bind('dds.select',function(){
                    $(this).addClass('dds_selected').addClass( $.fn.drag_drop_selectable.settings[$.fn.drag_drop_selectable.getListId($(this).attr('dds'))].selectClass );
                    
                }).bind('dds.deselect',function(){
                    $(this).removeClass('dds_selected').removeClass( $.fn.drag_drop_selectable.settings[$.fn.drag_drop_selectable.getListId($(this).attr('dds'))].selectClass );;
                }).css({cursor:'pointer'});
            })
            //OK so they are selectable. now I need to make them draggable, in such a way that they pick up their friends when dragged. hmmm how do I do that?
            .draggable({
                 helper:function(){
                    $clicked = $(this);
                    if( ! $clicked.hasClass('dds_selected') ){
                        //trigger the click function.
                        $clicked.trigger('click.dds_select');
                    }
                    var list = $.fn.drag_drop_selectable.getListId($clicked.attr('dds'));
                    var $helper = $('<div dds_list="'+list+'"><div style="margin-top:-'+$.fn.drag_drop_selectable.getMarginForDragging( $clicked )+'px;" /></div>').append( $.fn.drag_drop_selectable.getSelectedForDragging( $clicked.attr('dds') ) );
                        $.fn.drag_drop_selectable.getListItems( list ).filter('.dds_selected').addClass($.fn.drag_drop_selectable.settings[list].ghostClass);
                    return $helper;
                 },
                 distance:5, //give bit of leeway to allow selecting with click.
                 revert:'invalid',
                 cursor:'move',
                 stop:function(e, ui){
                    var list = $.fn.drag_drop_selectable.getListId($clicked.attr('dds'));
                    $.fn.drag_drop_selectable.getListItems( list ).filter('.dds_selected').removeClass($.fn.drag_drop_selectable.settings[list].ghostClass);
                 }
            });
            $list.droppable({
                drop:function(e,ui){ 
                    var oldlist = parseInt(ui.helper.attr('dds_list'));
                    ui.helper.find('li.dds_selected').each(function(){
                        var iid = parseInt( $(this).attr('dds_drag') );
                        $.fn.drag_drop_selectable.moveBetweenLists( iid, oldlist, list_id );
                    });
                    //now call callbacks!
                    if( $.fn.drag_drop_selectable.settings[oldlist] && typeof($.fn.drag_drop_selectable.settings[oldlist].onListChange) == 'function'){
                        setTimeout(function(){ $.fn.drag_drop_selectable.settings[oldlist].onListChange( $('ul[dds='+oldlist+']') ); },50);
                    }
                    if( $.fn.drag_drop_selectable.settings[list_id] && typeof($.fn.drag_drop_selectable.settings[list_id].onListChange) == 'function'){
                        setTimeout(function(){ $.fn.drag_drop_selectable.settings[list_id].onListChange( $('ul[dds='+list_id+']') ); },50);
                    }
                    
                    
                },
                accept:function(d){
                    if( $.fn.drag_drop_selectable.getListId( d.attr('dds') ) == $(this).attr('dds')){
                        return false;
                    }
                    return true;
                },
                hoverClass:$.fn.drag_drop_selectable.settings[list_id].hoverClass,
                tolerance:'pointer'
            });
        });  
    };
    $.fn.drag_drop_selectable.moveBetweenLists=function(item_id, old_list_id, new_list_id){
        //first deselect.
        $.fn.drag_drop_selectable.deselect(parseInt(item_id));
        //now remove from stack
        $.fn.drag_drop_selectable.stack[old_list_id].all.splice( $.inArray( parseInt(item_id),$.fn.drag_drop_selectable.stack[old_list_id].all ),1);
		if($.fn.drag_drop_selectable.stack[new_list_id].all.length<<?php echo $limit_count ->music_limit;?>){
        //now add to new stack.
        $.fn.drag_drop_selectable.stack[new_list_id].all.push( parseInt(item_id) );
        //now move DOM Object.
        $('ul[dds='+old_list_id+']').find('li[dds='+item_id+']').removeClass($.fn.drag_drop_selectable.settings[old_list_id].ghostClass).appendTo( $('ul[dds='+new_list_id+']') );
    };
	}
    $.fn.drag_drop_selectable.getSelectedForDragging=function(item_id){
        var list = $.fn.drag_drop_selectable.getListId( item_id );
        var $others = $.fn.drag_drop_selectable.getListItems( list ).clone().each(function(){
            $(this).not('.dds_selected').css({visibility:'hidden'});
            $(this).filter('.dds_selected').addClass( $.fn.drag_drop_selectable.settings[list].moveClass ).css({opacity:$.fn.drag_drop_selectable.settings[list].moveOpacity});;
            $(this).attr('dds_drag',$(this).attr('dds'))
            $(this).attr('dds','');
        });
        return $others;
    };
    $.fn.drag_drop_selectable.getMarginForDragging=function($item){
        //find this items offset and the first items offset.
        var this_offset = $item.position().top;
        var first_offset = $.fn.drag_drop_selectable.getListItems( $.fn.drag_drop_selectable.getListId( $item.attr('dds') ) ).eq(0).position().top;
        return this_offset-first_offset;
    }
    
    $.fn.drag_drop_selectable.toggle=function(id){
        if(!$.fn.drag_drop_selectable.isSelected(id)){
            $.fn.drag_drop_selectable.select(id);
        }else{
            $.fn.drag_drop_selectable.deselect(id);
        }
    };
    $.fn.drag_drop_selectable.select=function(id){
        if(!$.fn.drag_drop_selectable.isSelected(id)){
            var list = $.fn.drag_drop_selectable.getListId(id);
            $.fn.drag_drop_selectable.stack[list].selected.push(id);
            $('[dds='+id+']').trigger('dds.select');
        }
    };
    $.fn.drag_drop_selectable.deselect=function(id){
        if($.fn.drag_drop_selectable.isSelected(id)){
            var list = $.fn.drag_drop_selectable.getListId(id);
            $.fn.drag_drop_selectable.stack[list].selected.splice($.inArray(id,$.fn.drag_drop_selectable.stack[list].selected),1);
            $('[dds='+id+']').trigger('dds.deselect');
        }
    };
    $.fn.drag_drop_selectable.isSelected=function(id){
        return $('li[dds='+id+']').hasClass('dds_selected');
    };
    $.fn.drag_drop_selectable.replace=function(id){
        //find the list this is in!
        var list = $.fn.drag_drop_selectable.getListId(id);
        $.fn.drag_drop_selectable.selectNone(list);
        $.fn.drag_drop_selectable.stack[list].selected.push(id);
        $('[dds='+id+']').trigger('dds.select');
    };
    $.fn.drag_drop_selectable.selectNone=function(list_id){
        $.fn.drag_drop_selectable.getListItems(list_id).each(function(){
            $.fn.drag_drop_selectable.deselect( $(this).attr('dds') );
        });return false;
    };
    $.fn.drag_drop_selectable.selectAll=function(list_id){
        $.fn.drag_drop_selectable.getListItems(list_id).each(function(){
            $.fn.drag_drop_selectable.select( $(this).attr('dds') );
        });return false;
    };
    $.fn.drag_drop_selectable.selectInvert=function(list_id){
        $.fn.drag_drop_selectable.getListItems(list_id).each(function(){
            $.fn.drag_drop_selectable.toggle( $(this).attr('dds') );
        });return false;
    };
    $.fn.drag_drop_selectable.getListItems=function(list_id){
        return $('ul[dds='+list_id+'] li');
    };
    $.fn.drag_drop_selectable.getListId=function(item_id){
        return parseInt($('li[dds='+item_id+']').parent('ul').eq(0).attr('dds'));
    };
    $.fn.drag_drop_selectable.serializeArray=function( list_id ){
        var out = [];
        $.fn.drag_drop_selectable.getListItems(list_id).each(function(){
            out.push($(this).attr('id'));
        });
        return out;
    };
    $.fn.drag_drop_selectable.serialize=function( list_id ){
            return $.fn.drag_drop_selectable.serializeArray( list_id ).join(", ");
    };
    
    $.fn.drag_drop_selectable.unique=0;
    $.fn.drag_drop_selectable.stack=[];
    $.fn.drag_drop_selectable.defaults={
        moveOpacity: 0.8, //opacity of moving items
        ghostClass: 'dds_ghost', //class for "left-behind" item.
        hoverClass: 'dds_hover', //class for acceptable drop targets on hover
        moveClass:  'dds_move', //class to apply to items whilst moving them.
        selectedClass: 'dds_selected', //this default will be aplied any way, but the overridden one too.
        onListChange: function(list){ console.log( list.attr('id') );} //called once when the list changes
    }
    $.fn.drag_drop_selectable.settings=[];
    
    
    $.extend({
        dds:{
                selectAll:function(id){ return $.fn.drag_drop_selectable.selectAll($('#'+id).attr('dds')); },
                selectNone:function(id){ return $.fn.drag_drop_selectable.selectNone($('#'+id).attr('dds')); },
                selectInvert:function(id){ return $.fn.drag_drop_selectable.selectInvert($('#'+id).attr('dds')); },
                serialize:function(id){ return $.fn.drag_drop_selectable.serialize($('#'+id).attr('dds')); }
            }
    });
    
    var CTRL_KEY = 17;
    var ALT_KEY = 18;
    var SHIFT_KEY = 16;
    var META_KEY = 92;
    $.fn.captureKeys=function(){
        if($.fn.captureKeys.capturing){ return; }
        $(document).keydown(function(e){
            if(e.keyCode == CTRL_KEY ){ $.fn.captureKeys.stack.CTRL_KEY  = true  }
            if(e.keyCode == SHIFT_KEY){ $.fn.captureKeys.stack.SHIFT_KEY = true  }
            if(e.keyCode == ALT_KEY  ){ $.fn.captureKeys.stack.ALT_KEY   = true  }
            if(e.keyCode == META_KEY ){ $.fn.captureKeys.stack.META_KEY  = true  }
        }).keyup(function(e){
            if(e.keyCode == CTRL_KEY ){ $.fn.captureKeys.stack.CTRL_KEY  = false }
            if(e.keyCode == SHIFT_KEY){ $.fn.captureKeys.stack.SHIFT_KEY = false }
            if(e.keyCode == ALT_KEY  ){ $.fn.captureKeys.stack.ALT_KEY   = false }
            if(e.keyCode == META_KEY ){ $.fn.captureKeys.stack.META_KEY  = false }
        });
    };
    $.fn.captureKeys.stack={ CTRL_KEY:false, SHIFT_KEY:false, ALT_KEY:false, META_KEY:false }
    $.fn.captureKeys.capturing=false;
    $.fn.isPressed=function(key){
        switch(key){
            case  CTRL_KEY: return $.fn.captureKeys.stack.CTRL_KEY;
            case   ALT_KEY: return $.fn.captureKeys.stack.ALT_KEY;
            case SHIFT_KEY: return $.fn.captureKeys.stack.SHIFT_KEY;
            case  META_KEY: return $.fn.captureKeys.stack.META_KEY;
            default: return false;
        }
    }
})(jQuery);

var my_list1,my_list2;
$(function(){
    mychange = function ( $list ){
        $( '#'+$list.attr('id')+'_serialised').html( $.dds.serialize( $list.attr('id')) );
		my_list1=$.dds.serialize( 'list_1' );
		my_list2=$.dds.serialize( 'list_2' );
		showHint(my_list1,my_list2);
    }
    $('ul').drag_drop_selectable({
        onListChange:mychange
		
    });
	
		my_list1=$.dds.serialize( 'list_1' );
		my_list2=$.dds.serialize( 'list_2' );
var mynew_change=showHint(my_list1,my_list2);	

var mynew_change=showHint(my_list1,my_list2);

    $( '#list_1_serialised').html( $.dds.serialize( 'list_1' ) );
    $( '#list_2_serialised').html( $.dds.serialize( 'list_2' ) );
	
});
	var xmlhttp=null;

function make_httpRequest(){
		if (window.XMLHttpRequest)
		  {
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else if (window.ActiveXObject)
		  {
		  // code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		else
		  {
		  alert("Your browser does not support XMLHTTP!");
		  return;
		  }
}

function request_url(url){

url=url+"&sid="+Math.random();
xmlhttp.open("GET",url,false);

xmlhttp.send(null);

}

function showHint(list1,list2)
{

make_httpRequest();
  
var url="../../../controller/music_add_iphone_controller.php?list1=" + list1+"&list2="+ list2+"&status=update_list";

request_url(url);
//alert(url);
 document.getElementById("div_error").innerHTML=xmlhttp.responseText;
}
function showDelete(page,id,status)
{
  make_httpRequest();
 
  var url=""+page+".php?id="+id+"&status="+status;
  alert(url);
  request_url(url);
} 

function showUpload(page)
{
  make_httpRequest();
 
  var url=""+page+".php?id=";
  alert(url);
  request_url(url);
  document.getElementById("div_upload").innerHTML=xmlhttp.responseText;
}

function checkLoad(){
document.getElementById("div_upload").style.visibility= 'visible';
}

function delete_confirm(){
return confirm("Are you sure you want to delete");
 
}
</script><script charset="utf-8" id="injection_graph_func" src=
"../../../js/Select%20and%20drag_files/injection_graph_func.js"></script>

</head>

<body>
<table width="200" border="1">
  <tr>
    <td><a href="#" onclick="checkLoad()"><!--<a href="upload_music.php">-->music upload</a></td>
    <td>Iphone</td>
  </tr>
   <tr>
    <td colspan="2"><div id="div_error"></div></td>

  </tr>
  <tr>
    <td>
    <div id="div_iphone">
    <table width="200" border="1">
      <tr>
        <td>Title</td>
        <td>Duration</td>
        <td>Thumbnail</td>
      </tr>
      <tr>
        <td colspan="2">
        <ul class="ui-droppable" id="list_1">
      <?php 
	  if(sizeof(bank_music) >0){
	  foreach($bank_music as $bmusic){?>
      <li class="ui-draggable" style="cursor: pointer;"  id="list_1_item_<?php echo $bmusic->id;?>">Item <?php echo $count;?> - <?php echo $count."  -  ".$bmusic->title;?><a href="../../../controller/music_add_iphone_controller.php?id=<?php echo $bmusic->id;?>&status=add"><?php echo $bmusic->id. " Add "; ?></a>  <a href="edit_music.php?id=<?php echo $bmusic->id;?>"  rel="gb_page_center[640, 480]">Edit</a> <a href="#" onclick="showDelete('<?php echo addslashes("../../../controller/music_add_iphone_controller");?>','<?php echo $bmusic->id;?>','delete')" onclick="return delete_confirm();">Delete</a></li>
      
      <?php 
	  $count++;
	  }
	  }?>
      </ul></td>
        
      </tr>
      <tr>
        <td colspan="3">
        <p>This list contains: <span id="list_1_serialised">list_1_item_1, list_1_item_2, list_1_item_3, list_1_item_4, list_1_item_5, list_1_item_6</span></p>
        </td>
        </tr>
    </table>
    </div>
    </td>
    <td>
    <div id="div_bank">

    <table width="200" border="1">
      <tr>
        <td>Title</td>
        <td>Duration</td>
        <td>Thumbnail</td>
      </tr>
       <tr>
        <td colspan="3">
		<ul class="ui-droppable" id="list_2">
		<?php 
	   if(sizeof(iphone_music) >0){
	   foreach($iphone_music as $imusic){?>
        <li class="ui-draggable" style="cursor: pointer;" id="list_2_item_<?php echo $imusic->id;?>">Item <?php echo $icount;?> -  <?php echo $icount."  -  ".$imusic->title;?>
      <a href="../../../controller/music_add_iphone_controller.php?id=<?php echo $imusic->id;?>&status=remove" onclick="return delete_confirm();"><?php //echo $imusic->id;?> Delete</a>  <a href="edit_music.php?id=<?php echo $imusic->id;?>"  rel="gb_page_center[640, 480]">Edit</a></li>
     
      
	  <?php 
	  $icount++;
	  }
	  }
	  ?>
      </ul>
      </td>
        </tr>
      <tr>
        <td><p>This list contains: <span id="list_2_serialised">list_2_item_7, list_2_item_8, list_2_item_9, list_2_item_10, list_2_item_11, list_2_item_12</span></p></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    </div>
    </td>
  </tr>
  <tr>
    <td><a href="add_music.php">Add music</a><!--<span id="txtHint"></span>--></td>
    <td></td>
  </tr>
</table>
<table>
<tr>
<td>
<div id="div_upload" style="visibility:hidden">
<h2>Multi-Instance Demo</h2>
	<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
		<p>This page demonstrates how multiple instances of SWFUpload can be loaded on the same page.
			It also demonstrates the use of the graceful degradation plugin and the queue plugin.</p>
		<table>
			<tr valign="top">
				<td>
					<div>
						<div class="fieldset flash" id="fsUploadProgress1">
							<span class="legend">Large File Upload Site</span>
						</div>
						<div style="padding-left: 5px;">
							<span id="spanButtonPlaceholder1"></span>
							<input id="btnCancel1" type="button" value="Cancel Uploads" onclick="cancelQueue(upload1);" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
							<br />
						</div>
					</div>
				</td>
				<td>
				</td>
			</tr>
		</table>
	</form>
</div>
</td>
</tr>
</table>
<script type="text/javascript">
GB_myShow = function(caption, url, /* optional */ height, width, callback_fn) {
    var options = {
        caption: caption,
        height: height || 500,
        width: width || 500,
        fullscreen: false,
        show_loading: false,
        callback_fn: callback_fn
    }
    var win = new GB_Window(options);
    return win.show(url);
}
</script>
</body>
</html>
