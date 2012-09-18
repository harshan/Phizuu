function delete_confirm(id) {
    if( confirm("Are you sure to delete this picture?")){
        $('#id_'+id+' .iconLeft').html("<img src='../../../images/bigrotation2.gif' height=16 width=16/>");
        $('#id_'+id+' .iconLeft').css('background-image', "url('../../../images/empty.png')");
        
        $.post('../../../controller/photo_all_controller.php?action=delete_picture',{'id':id}, function(data) {
            $('#id_'+id).fadeOut();
            if(flickrListed) {

                $('#spanListingText').html("Refreshing image list..");
                flickrGetImages();
            }
        });
    }

    return false;
}

function delete_confirm_album(id) {
    if( confirm("If you delete the album, all the images in it will be moved to the bank list. Are you sure you want to delete this album?")){
        $('#aid_'+id+' .iconLeft').html("<img src='../../../images/bigrotation2.gif' height=16 width=16/>");
        $('#aid_'+id+' .iconLeft').css('background-image', "url('../../../images/empty.png')");
        $.post('../../../controller/photo_all_controller.php?action=delete_album',{'id':id}, function(data) {
            if(data!='')
                $(data).appendTo("#list_1").fadeIn(300);

            albumCount--;
            $('#aid_'+id).fadeOut();
        });
    }

    return false;
}

$(function(){
    if (picCountInBank<=0) {
        $('#addAllButtonLnk').hide();
    }

    $('.edit').editable('../../../controller/photo_all_controller.php?action=edit',{
        indicator : 'Saving...',
        tooltip   : 'Click to edit and Enter to Save',
        rows : 3
    });

    $("#list_2").sortable({
        placeholder: 'highlight',
        handle : '.dragHandlePhoto'
    });

    $("#list_3").sortable({
        placeholder: 'highlight_album',
        handle : '.dragHandlePhoto'
    });


    $('#list_2').bind('sortupdate', updateList);
    $('#list_3').bind('sortupdate', updateAlbumList);

    $('#selectCover').css('opacity', 0.2);

    $('.albumWrapper .description, .albumWrapper .image').click(selectAlbum);
    $('#selectCover').click(unselectAlbum);
    
    $("#thumbWaitDiv").dialog({
            modal: true,
            closeOnEscape: false,
            closeText: 'hide',
            hide: 'blind',
            resizable: false,
            open: function(event, ui) {$('#thumbWaitDiv').parent().find('a.ui-dialog-titlebar-close').hide();},
            show: 'blind',
            autoOpen: false

    });

    $("#zoomImage").dialog({
            modal: true,
            closeText: 'hide',
            hide: 'blind',
            resizable: false,
            show: 'blind',
            autoOpen: false,
            width: 620,
            position: ['center',20]
    });


    $("#flickrToolTip").dialog({
            modal: true,
            closeOnEscape: false,
            closeText: 'hide',
            hide: 'blind',
            resizable: false,
            show: 'blind',
            autoOpen: false,
            width: 500,
            height: 200
    });

    $("#createEditAlbumDiv").dialog({
            modal: true,
            resizable: false,
            show: 'blind',
            autoOpen: false,
            hide: 'blind',
            width: 400,
            height: 585,
            buttons: { 
                "Cancel": function() {
                    $(this).dialog("close");
                },
                "Save": function() {
                    if (editingAlbum == -1)
                        createAlbum();
                    else
                        editAlbum();
                }
            }

    });
    $("#uploadImage").dialog({
            modal: true,
            resizable: false,
            show: 'blind',
            autoOpen: false,
            hide: 'blind',
            width: 410,
            height: 210,
            buttons: { 
                "Cancel": function() {
                    $(this).dialog("close");
                },
                "Upload": function() {
                    var iframeEl = document.getElementById('imageUploadIFrame');
                    if ( iframeEl.contentDocument ) { // DOM
                        var form = iframeEl.contentDocument.getElementById('uploadForm');
                        var file = iframeEl.contentDocument.getElementById('fileImage');
                    } else if ( iframeEl.contentWindow ) { // IE win
                        var form = iframeEl.contentWindow.document.getElementById('uploadForm');
                        var file = iframeEl.contentWindow.document.getElementById('fileImage');
                    }
                    if(file.value == ''){
                        alert("Please select a image to upload!");
                        return;
                    }

                    var extension = file.value.substring(file.value.length-3);
                    extension = extension.toLowerCase();
                    //alert(extension);
                    if(!(extension=='jpg' || extension=='png' || extension=='gif')) {
                        alert("Only jpg,png and gif are supported!");
                        return;
                    }
                    
                    form.submit();

                    $('#image_button').addClass('ui-state-disabled');
                    $('#image_button').attr("disabled", true);
                    $('#image_button').html("Uploading...");
                }
            }

    });
     $("#invalidFlickrUser").dialog({
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

     $("#alreadyAddedPic").dialog({
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

    $("#alreadyAddedPic").dialog({
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

     $("#showErrorDialog").dialog({
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

    $("#selectImage").dialog({
        modal: true,
        autoOpen: false,
        width: 400,
        height: 500,
        resizable: false,
        buttons: {
            Ok: function() {
                $(this).dialog('close');
            }
        }
    });

    $('.ui-dialog-buttonpane button:contains(Save)').attr("id","album_edit_save_button");
    $('.ui-dialog-buttonpane button:contains(Upload)').attr("id","image_button");

    $(function() {
        $("#albumDate").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                changeYear: true
            }
        );
    });

    var settings = {
        flash_url : "../../../common/swfupload.swf",
        upload_url: "../../../controller/image_uploader.php",
        post_params: {"PHPSESSID" : phpSessionId},
        file_size_limit : "100 MB",
        file_types : "*.jpg;*.png;*.gif",
        file_types_description : "Image files",
        file_upload_limit : 100,
        file_queue_limit : 10,
        custom_settings : {
                progressTarget : "fsUploadProgress",
                cancelButtonId : "btnCancel"
        },
        debug: false,
        prevent_swf_caching: false,

        // Button settings
        button_image_url: "../../../images/upload_4btn.png",
        button_width: "88",
        button_height: "25",
        button_placeholder_id: "spanButtonPlaceHolder",
        button_text: '<span class="theFont"></span>',
        button_text_style: ".theFont { font-size: 16; }",
        button_text_left_padding: 12,
        button_text_top_padding: 3,

        // The event handler functions are defined in handlers.js
        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        upload_start_handler : uploadStart,
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,
        queue_complete_handler : queueComplete	// Queue plugin event
    };

    swfu = new SWFUpload(settings);
});

var selectAlbum = function() {
    var li = $(this).parents('li');
    
    var idArr = li.attr('id').split("_");
    var selId = idArr[1];

    $(".albumWrapper").css('background-image',"url('../../../images/album_view.png')");
    $(this).parents('.albumWrapper').css('background-image',"url('../../../images/album_view_selected.png')");
 
    selectedAlbum = selId;
}

var unselectAlbum = function() {
    selectedAlbum = -1;
    $('#selectCover').fadeOut(100);
}

var updateList = function(event, ui) {
    $("#list_2").sortable( 'disable' );
    $("#list_2").css('cursor', 'wait');
    $("#list_2 .dragHandlePhoto").css('cursor', 'wait');
    var order = $('#list_2').sortable('serialize');
    $.post('../../../controller/photo_all_controller.php?action=order&'+order, function(data) {
        $("#list_2").sortable( 'enable' );
        $("#list_2 .dragHandlePhoto").css('cursor', 'move');
        $("#list_2").css('cursor', '');
    });
}

var updateAlbumList = function(event, ui) {
    $("#list_3").sortable( 'disable' );
    $("#list_3").css('cursor', 'wait');
    $("#list_3 .dragHandlePhoto").css('cursor', 'wait');
    var order = $('#list_3').sortable('serialize');
    $.post('../../../controller/photo_all_controller.php?action=album_order&'+order, function(data) {
        $("#list_3").sortable( 'enable' );
        $("#list_3 .dragHandlePhoto").css('cursor', 'move');
        $("#list_3").css('cursor', '');
    });
}

function moveToRight(li) {
    if (picCountInList>=picLimit) {
        alert("Sorry! You have reached maximum limit of " + picLimit + " picures. Please remove picures in iPhone list if you want to add new.");
        return;
    }

    if (selectedAlbum==-1 && openedAlbum ==-1) {
        showErrorDialog("<p>Please select or open an album before add images to iPhone list.<br/><br/>You can select an album by clicking or open an album by clicking <img src='../../../images/album_open.png' align='top'/> icon of the album.</p>");
        return;
    }
    
    li.fadeOut(
        function() {
            if(openedAlbum !=-1) {
                li.appendTo("#list_2").fadeIn();
                $("#list_2").sortable( "refresh" );
            }
        }
    );

    if(openedAlbum ==-1) {
        $('#selectCover').show();
        $('#selectCover').css('top',li.offset().top);
        $('#selectCover').css('left',li.offset().left);
        $('#selectCover').css('height',153);
        $('#selectCover').css('width',91);
        $('#selectCover').animate({
            top: $("#aid_"+selectedAlbum).offset().top,
            left: $("#aid_"+selectedAlbum).offset().left,
            height: 104,
            width: 99
        }, 300, function() {
            $('#selectCover').hide();
            });
    }

    picCountInList++;
    picCountInBank--;

    var albumId = 0;
    if (openedAlbum==-1) {
        albumId = selectedAlbum;
    } else {
        albumId = openedAlbum;
    }
    
    $('#aid_'+albumId+' .imageCountSel').html(parseInt($('#aid_'+albumId+' .imageCountSel').html())+1);
    
    if (picCountInBank<=0) {
        $('#addAllButtonLnk').fadeOut();
    }

    var idArr = li.attr('id').split("_");
    var selId = idArr[1];
    
    addImageToAlbum(selId);
}

function addImageToAlbum(imageId) {
    var albumId = 0;

    if(openedAlbum !=-1) {
        albumId = openedAlbum;
    } else {
        albumId = selectedAlbum;
    }
    
    $.post('../../../controller/photo_all_controller.php?action=add_picture_to_album', {'image_id':imageId, 'album_id': albumId} ,function(data) {
        var refreshAlbum = albumId;
        var imageCount = parseInt($('#aid_'+refreshAlbum+' .imageCountSel').html());
        if (imageCount<=1) {
            //refreshAlbumThumb(refreshAlbum, imageCount);
        }
    });
}

function removeImageFromAlbum(imageId) {
    var albumId = openedAlbum;
    $.post('../../../controller/photo_all_controller.php?action=remove_picture_from_album', {'image_id':imageId, 'album_id': albumId} ,function(data) {
        var refreshAlbum = albumId;
        var imageCount = parseInt($('#aid_'+refreshAlbum+' .imageCountSel').html());
        if (imageCount<=0) {
            //refreshAlbumThumb(refreshAlbum, imageCount);
        }
    });
}

function moveToLeft(li) {

    li.fadeOut(
        function() {
            li.appendTo("#list_1").fadeIn();
            $("#list_2").sortable( "refresh" );
            picCountInList--;
            picCountInBank++;

            if (picCountInBank>0) {
                $('#addAllButtonLnk').fadeIn();
            }

        }
    );

    var idArr = li.attr('id').split("_");
    var selId = idArr[1];

    $('#aid_'+openedAlbum+' .imageCountSel').html(parseInt($('#aid_'+openedAlbum+' .imageCountSel').html())-1);

    removeImageFromAlbum(selId);
}

function moveItem(elem) {
    var item = $(elem);

    var ul = item.parents("ul");
    var li = item.parents("li");
    
    if(ul.attr('id')=="list_1") {
        moveToRight(li);
        
    } else {
        moveToLeft(li);
        
    }
}

function refreshAlbumThumb(albumId, imageCount) {
    if (imageCount==0 || imageCount==2) {
        $("#waitDivInsideTitle").html("Refreshing thumbnail...");
    } else {
        $("#waitDivInsideTitle").html("Creating thumbnail...");
    }
    $("#thumbWaitDiv").dialog("open");
    $.post('../../../controller/photo_all_controller.php?action=refresh_thumb', {'album_id': albumId} ,function(data) {
        var refreshThumb = albumId;
        $("#thumbWaitDiv").dialog("close");
        $('#aid_'+refreshThumb+' .imageSel').attr('src',data);
    });
}

function showCreateAlbum() {
    if(albumCount>=albumLimit) {
        alert("Sorry!! Your package can have maximum "+ albumLimit +" albums.");
        return;
    }
    lastEditImageURL = '';
    $("#divCoverImage").hide();
    $("#divCoverImageEditLink").show();
    if (openedAlbum == -1) {
        $('#album_edit_save_button').removeClass('ui-state-disabled');
        $('#album_edit_save_button').attr("disabled", false);
        $('#createEditAlbumDiv input, #createEditAlbumDiv textarea').val('');
        $('#album_edit_save_button').html("OK");
        $("#createEditAlbumDiv").dialog( "option", "title", 'Create Album' );
        editingAlbum = -1;
        $("#createEditAlbumDiv").dialog("open");

    }
}

function openAlbum(albumId) {
    $('#aid_'+albumId+' .iconMiddle').html("<img src='../../../images/bigrotation2.gif' height=16 width=16/>");
    $('#aid_'+albumId+' .iconMiddle').css('background-image', "url('../../../images/empty.png')");
    $('#list_2').empty();
    
    $.post('../../../controller/photo_all_controller.php?action=open_album', {'album_id': albumId}, function(data) {
        $('#list_3').fadeOut(500, function(){
            $('#albumTitleDiv').html($('#aid_'+albumId+' .titleSel').html()).fadeIn(500);
            $('#list_2').append(data);
            $('#list_2').fadeIn(500);
            $('.edit').editable('../../../controller/photo_all_controller.php?action=edit',{
                indicator : 'Saving...',
                tooltip   : 'Click to edit and Enter to Save',
                rows : 3
            });
            $('#newAlbumBtn').attr('src','../../../images/new_album_dis.png');
            $('#listAlbumBtn').attr('src','../../../images/list_album.png');
            openedAlbum = albumId;
        });
    });
}


function listAlbums() {
    if (openedAlbum==-1)
        return;

    $('#albumTitleDiv').fadeOut();
    $('#listAlbumBtn').attr('src','../../../images/list_album_dis.png');
    $('#newAlbumBtn').attr('src','../../../images/new_album.png');
    $('.albumWrapper .iconMiddle').html('').css('background-image',"url('../../../images/album_open.png')");
    
    $('#list_2').fadeOut(500, function(){
        $('#list_3').fadeIn(500);
    });
    
    openedAlbum = -1;
}

var addAllPhotos = function () {

    $( "#list_1 li" ).each(
        function() {
            if (picCountInList>=picLimit) {
                alert("Sorry! You have reached maximum limit of " + picLimit + " picures. Please remove picures in iPhone list if you want to add new.")
                return false;
            }

            var li = $(this);

            document.getElementById("list_1").removeChild(this);
            li.appendTo("#list_2").fadeIn();
            picCountInList++;
            picCountInBank--;

            $('#addAllButtonLnk').fadeOut();
    });

    $("#list_2").sortable( "refresh" );
    updateList();

    return false;
}

function createAlbum() {
    var name = $("#albumName").val().trim();
    
    if (name=='') {
        alert("Album name cannot be empty");
        return;
    }

    if (lastEditImageURL=='') {
        alert("You must upload an image for the cover image to create an album. This can be changed later");
        return;
    }

    $('#album_edit_save_button').addClass('ui-state-disabled');
    $('#album_edit_save_button').attr("disabled", true);
    $('#album_edit_save_button').html("Saving...");

    var postData = {
        "name": $("#albumName").val(),
        "date": $("#albumDate").val(),
        "location": $("#albumLocation").val(),
        "description": $("#albumDesc").val(),
        "image_url": lastEditImageURL
    }

    $.post('../../../controller/photo_all_controller.php?action=add_album', postData ,function(data) {
        $('#album_edit_save_button').removeClass('ui-state-disabled');
        $('#album_edit_save_button').attr("disabled", false);
        if (data.error==false) {
            $(data.html).appendTo('#list_3').hide().fadeIn(300).find('.albumWrapper .description, .albumWrapper .image').click(selectAlbum);
            $("#createEditAlbumDiv").dialog("close");
            albumCount++;
        } else {
            $('#album_edit_save_button').html("Try Again");
            alert("Error occured while saving. Please try again!")
        }

    },'json');
}

function editAlbum() {
    var name = $("#albumName").val();

    if (name=='') {
        alert("Album name cannot be empty");
        return;
    }

    $('#album_edit_save_button').addClass('ui-state-disabled');
    $('#album_edit_save_button').attr("disabled", true);
    $('#album_edit_save_button').html("Saving...");

    var postData = {
        "name": $("#albumName").val(),
        "date": $("#albumDate").val(),
        "location": $("#albumLocation").val(),
        "description": $("#albumDesc").val(),
        "album_id": editingAlbum,
        "image_url": lastEditImageURL
    }

    $.post('../../../controller/photo_all_controller.php?action=edit_album', postData ,function(data) {
        $('#album_edit_save_button').removeClass('ui-state-disabled');
        $('#album_edit_save_button').attr("disabled", false);

        $('#aid_'+editingAlbum+' .imageSel').attr('src' , $('#aid_'+editingAlbum+' .imageSel').attr('src')+'x') ;

        if (data.error==false) {
            $('#aid_'+editingAlbum+' .titleSel').html(data.html);
            $("#createEditAlbumDiv").dialog("close");
        } else {
            $('#album_edit_save_button').html("Try Again");
            alert("No change has been done. Please try again or cancel if you don't want to edit!")
        }

    },'json');
}

function showEditAlbum(albumId) {
    lastEditImageURL = '';
    if (openedAlbum == -1) {
        $("#divCoverImage").show();
        $("#divCoverImageEditLink").hide();
        $('#album_edit_save_button').addClass('ui-state-disabled');
        $('#album_edit_save_button').attr("disabled", true);
        $('#createEditAlbumDiv input, #createEditAlbumDiv textarea').val('').attr("disabled", true);
        $('#album_edit_save_button').html("Loading...");
        $("#createEditAlbumDiv").dialog( "option", "title", 'Edit Album' );
        $("#createEditAlbumDiv").dialog("open");
        editingAlbum = albumId;
        $.post('../../../controller/photo_all_controller.php?action=get_album_details_ajax', {'album_id':albumId} ,function(data) {
            $('#album_edit_save_button').removeClass('ui-state-disabled');
            $('#album_edit_save_button').attr("disabled", false);
            $('#album_edit_save_button').html("OK");
            $('#createEditAlbumDiv input, #createEditAlbumDiv textarea').attr("disabled", false);
            $("#albumName").val(data.album_name);
            $("#albumDate").val(data.album_date);
            $("#albumLocation").val(data.location);
            $("#albumDesc").val(data.description);

            loadCoverImage(data.image_uri + "?rand=" + Math.random());
        },'json'); 
    }
}

function loadCoverImage(url) {
    var image = new Image;
    image.src = url;
    $("#editImage").attr('width',32);
    $("#editImage").attr('height',32);
    $("#editImage").attr('src','../../../images/bigrotation2.gif');
    image.onload = function() {
        $("#editImage").attr('width',image.width);
        $("#editImage").attr('height',image.height);
        $("#editImage").attr('src',url);
    }
}

function zoomImage(url) {
  
    $("#zoomImage").dialog('open');
    
    var image = new Image;
    image.src = url;
    $("#imgZoomImage").attr('width',32);
    $("#imgZoomImage").attr('height',32);
    $("#imgZoomImage").attr('src','../../../images/bigrotation2.gif');
    image.onload = function() {
        $("#imgZoomImage").attr('width',image.width);
        $("#imgZoomImage").attr('height',image.height);
//        $( "#imgZoomImage" ).dialog( "option", "width", image.width + 20 );
//        $( "#imgZoomImage" ).dialog( "option", "height", image.height + 20 );
        $("#imgZoomImage").attr('src',url);
    }
}


function flickrGetCollections() {

   var username = $('#flickrUserName').val();
   $('#flickrButton').hide();
   $('#flickrButtonWait').show();
   $('#flickrUserName').attr('disabled','disabled');
   $('#flickrCollection').attr('disabled','disabled');
   $.post('../../../controller/photo_all_controller.php?action=list_flickr_pics', {'username':username}, function(data) {
     
      $('#flickrButton').show();
      $('#flickrButtonWait').hide();
      $('#flickrUserName').attr('disabled','');

      if (data.all == '') {
          $("#invalidFlickrUser").dialog("open");
      } else {
          $('#flickrCollection').attr('disabled','');
          var sets = data.sets;
          $('#flickrCollection').empty()
          $('#flickrCollection').append($("<option></option>").attr("value","-1").text("-- Please Select Photo Set --"));
          $('#flickrCollection').append($("<option></option>").attr("value","").text("All (" + data.all + ")"));
          for (i=0; i<sets.length; i++) {
              //alert(sets[i].title);
              $('#flickrCollection').append($("<option></option>").attr("value",sets[i].id ).text(sets[i].title + " ("+sets[i].photos+")"));
          }
          //flickrGetImages('');
      }
  },'json');
}
function facebookGetCollections() {
   $('#flickrButtonWait').show();
//   $('#flickrCollection').attr('disabled','disabled');
//   $.post('../../../controller/photo_all_controller.php?action=list_facebook_pics', function(data) {
//      
//     alert(data);
//      
//      
//  },'json');
}

function loadFlickrImages() {
   $('#spanListingText').html("Listing images..");
    
        flickrGetImages();
    
}

//function flickrGetImages() {
//   var id = $('#flickrCollection').val();
//
//   var username = $('#flickrUserName').val();
//
//   if (username == '-1') {
//       return;
//   }
//   
//   $('#flickrButton').hide();
//   $('#listingWaiting').show();
//   $('#flickrUserName').attr('disabled','disabled');
//   $('#flickrCollection').attr('disabled','disabled');
//   $('#addAllButtonLnk').hide();
//
//   $('#list_4').empty();
//   $.post('../../../controller/photo_all_controller.php?action=get_photos_album', {'id':id,'username':username}, function(data) {
//      $('#flickrButton').show();
//      $('#listingWaiting').hide();
//      $('#flickrUserName').attr('disabled','');
//      $('#flickrCollection').attr('disabled','');
//      $('#addAllButtonLnk').show();
//      $(data.html).appendTo('#list_4');
//      flickrListed = true;
//  },'json');
//}

function flickrGetImages() {
    var id = $('#flickrCollection').val();
    var selectedAlbum = document.getElementById('flickrCollection').value;

    if(selectedAlbum != 0){
    $('#flickrButton').hide();
    $('#listingWaiting').show();
    $('#flickrUserName').attr('disabled','disabled');
    $('#flickrCollection').attr('disabled','disabled');
    $('#addAllButtonLnk').hide()

    $('#list_4').empty();
    $.post('../../../controller/photo_all_controller.php?action=get_photos_album_facebook', {'id':id}, function(data) {
     // alert(data);
     //data =  JSON.parse(data);
     //alert(data.html);

      $('#flickrButton').show();
      $('#listingWaiting').hide();
      $('#flickrUserName').attr('disabled','');
      $('#flickrCollection').attr('disabled','');
      $('#addAllButtonLnk').show();
      $(data.html).appendTo('#list_4');
      flickrListed = true;
  },'json');
    }
}

function addItemFlickr(clicked, added) {
   if (added) {
       $('#alreadyAddedPic').dialog('open');
       return;
   }

    var li = $(clicked).parents("li");
    li.find('.addIcon').css('background-image', "url('../../../images/empty.png')");
    li.find('.addIcon').html("<img src='../../../images/bigrotation2.gif' height=16 width=16/>");
    var name = li.children('.selName').html();
    var url = li.children('.selURL').html();
    var thumb = li.children('.selThumbURL').html();
    var pid = li.children('.selPID').html();
    
    $.post('../../../controller/photo_all_controller.php?action=add_pic_album', {'name':name,'uri':url,'thumb_uri':thumb,'pid':pid}, function(data) {
        //alert(data);
        li.fadeOut(300);
        $(data).appendTo('#list_1').hide().fadeIn(300);
    });
}

function showImageUploader(id) {
    //writeContentToIFrame('test engine');
    
    $('#image_button').addClass('ui-state-disabled');
    $('#image_button').attr("disabled", true);
    $('#image_button').html("Loading...");
    
    $('#imageUploadIFrame').attr('src','../../../view/user/pictures/album/iframe_upload.php');
    $('#uploadImage').dialog('open');
}

function loadedIFrame() {
    $('#image_button').removeClass('ui-state-disabled');
    $('#image_button').attr("disabled", false);
    $('#image_button').html("Upload");
}

function coverImageUploaded(url, path) {
    lastEditImageURL = path;
    $("#divCoverImage").show();
    $("#divCoverImageEditLink").hide();
    loadCoverImage(url + "?rand=" + Math.random());
    $('#uploadImage').dialog('close');
}

function showSelectImageList() {
    $('#selectImage').html("<img src='../../../images/bigrotation2.gif' height=32 width=32/> <br/>Loading...");
    $.post('../../../controller/photo_all_controller.php?action=get_select_pic_list', {'editing_album':editingAlbum}, function(data) {
        $('#selectImage').hide().fadeOut(300, function(){$('#selectImage').html(data).fadeIn(300);});
    });
    $('#selectImage').dialog('open');
}


function selectPicture(itemElem) {
    var item = $(itemElem);
    $('#selectImage').html("<img src='../../../images/bigrotation2.gif' height=32 width=32/> <br/>Please wait, preparing the image (This may take 3s to 60s depending on the size of the image)...");

    $.post('../../../controller/photo_all_controller.php?action=upload_image_from_web', {'image_url':item.children('.selURL').html()}, function(data) {
        coverImageUploaded(data.url,data.path);
        $('#selectImage').dialog('close');
    },'json');
    
}

function listFanPhotos() {
    $('#fanPhotoListingButton').html("<img src='../../../images/bigrotation2.gif' height=32 width=32/> <br/>Please wait, retrieving images from servers (This may take 3s to 60s depending on the number of events)...");
    $.post('../../../controller/photo_all_controller.php?action=get_photos_album_fan_photos', {'id':'x'}, function(data) {
        $('#fanPhotoListingButton').hide();
        $('#list_5').append(data);
    });
}


function showErrorDialog(msg) {
    $('#showErrorDialog').html(msg);
    $('#showErrorDialog').dialog('open');
}

function shareAlbumFaceBook() {
    var albumId = '';
    if(openedAlbum==-1 && selectedAlbum==-1) {
        showErrorDialog('Please select or open an album before share!');
        return;
    } else if(openedAlbum!=-1) {
        albumId = openedAlbum;
    } else {
        albumId = selectedAlbum;
    }

    window.location= 'share_album.php?album_id='+albumId;
}

var selectedAlbum = -1;
var openedAlbum = -1;
var editingAlbum = -1;
var flickrListed = false;
var lastEditImageURL = '';

