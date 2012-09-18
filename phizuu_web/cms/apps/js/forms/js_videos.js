<!--
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
		if($.fn.drag_drop_selectable.stack[new_list_id].all.length < limit){
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
            
			arrow_icon_in($(this).attr('id'),list_id);
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
				moveBetweenLists:function(id,oldlist,newlist){ 
				return $.fn.drag_drop_selectable.moveBetweenLists($('#'+id).attr('dds'),$('#'+oldlist).attr('dds'),$('#'+newlist).attr('dds')); 
				},
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
      $('.edit').editable('../../../controller/video_all_controller.php?action=edit',{
         indicator : 'Saving...',
         tooltip   : 'Click to edit...'
     });

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

//var mynew_change=showHint(my_list1,my_list2);

    $( '#list_1_serialised').html( $.dds.serialize( 'list_1' ) );
    $( '#list_2_serialised').html( $.dds.serialize( 'list_2' ) );
	
});
	var xmlhttp=null;

function showHint(list1,list2)
{

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
  
var url="../../../controller/video_add_iphone_controller.php?list1=" + list1+"&list2="+ list2+"&status=update_list";
url=url+"&sid="+Math.random();
xmlhttp.open("GET",url,false);
xmlhttp.send(null);
 document.getElementById("div_error").innerHTML=xmlhttp.responseText;
// alert(xmlhttp.responseText);
}
function arrow_icon_in(id,new_list){
//alert(id+"**" +new_list);
link_id=id.replace("item", "lnk");
//alert(link_id);

if(new_list== "0"){
img="arrow.png";
new_list1="list_1";
old_list1="list_2";
}
else{
img="arrow2.png";
new_list1="list_2";
old_list1="list_1";
}
img_id=id.replace("item", "img");
//alert(link_id +"**"+ img_id);
document.getElementById(img_id).src="../../../images/"+img+"";

linebutton=document.getElementById(link_id);
linebutton.id=link_id;
linebutton.value='Edit'; // i.e. change the button label (this works)
linebutton.setAttribute("onclick","return arrow_icon('"+id+"','"+new_list1+"','"+old_list1+"');" ); 

}


function arrow_icon(id,old_list,new_list){
$.dds.moveBetweenLists( id, old_list, new_list );
my_list1=$.dds.serialize( 'list_1' );
my_list2=$.dds.serialize( 'list_2' );

showHint(my_list1,my_list2);

}

function delete_confirm(){
return confirm("Are you sure you want to delete");
 
}	
//--> 