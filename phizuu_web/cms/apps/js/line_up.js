var globals = {
    init: function(){
	$("#createArtistDialog").dialog({
	    modal: true,
	    resizable: false,
	    show: 'blind',
	    autoOpen: false,
	    hide: 'blind',
	    width: 450,
	    height: 500,
	    buttons: {
		"Cancel": function() {
		    $(this).dialog("close");
		},
		"Save": function() {
		    if (artistsController.edit_mode){
			artistsController.updateArtist();
		    }else{
			artistsController.createNewArtist();
		    }
		}
	    }
	});

	$("#createFestivalDayDialog").dialog({
	    modal: true,
	    resizable: false,
	    show: 'blind',
	    autoOpen: false,
	    hide: 'blind',
	    width: 425,
	    height: 150,
	    buttons: {
		"Cancel": function() {
		    $(this).dialog("close");
		},
		"AddFestival": function() {
		    if (festivalDaysController.edit_mode){
			festivalDaysController.updateFestivalDay();
		    }else{
			festivalDaysController.createNewFestivalDay();
		    }
		}
	    }
	});

	$("#createStageDialog").dialog({
	    modal: true,
	    resizable: false,
	    show: 'blind',
	    autoOpen: false,
	    hide: 'blind',
	    width: 430,
	    height: 180,
	    buttons: {
		"Cancel": function() {
		    $(this).dialog("close");
		},
		"AddStage": function() {
		    if (stagesController.edit_mode){
			stagesController.updateStage();
		    }else{
			stagesController.createNewStage();
		    }
		}
	    }
	});

	$("#addShowTimeDialog").dialog({
	    modal: true,
	    resizable: false,
	    show: 'blind',
	    autoOpen: false,
	    hide: 'blind',
	    width: 425,
	    height: 175,
	    buttons: {
		"Cancel": function() {
		    $(this).dialog("close");
		},
		"AddShowTime": function() {
		    if (showController.edit_mode){
			showController.updateShow();
		    }else{
			showController.createNewShow();
		    }
		}
	    }
	});

	$('#artistThumbImage').imagechooser({
	    method:'upload',
	    callback: function(image, thumb, url_path) {
		$('#artistThumbImage').imagechooserLoadImage(image);
		$("#imageURIEdit").val(image);
		artist.image_url = image;
		artist.artist_image = url_path;
	    },
	    image_size:{
		width:artistsController.image_size,
		height: artistsController.image_size
		},
	    container_size:{
		width:artistsController.image_size,
		height: artistsController.image_size
		},
	    create_thumb: false,
	    image_catagory_name: 'line_up_images',
	    image_base_name:'temp_file_replace_QFHEHFNELLK3432',
	    empty_image: '../../../images/lineup_no_image.jpg',
	    hint_text: 'Click here to edit'
	});

	$('#artistLogoImage').imagechooser({
	    method:'upload',
	    callback: function(image, thumb, url_path) {
		$('#artistLogoImage').imagechooserLoadImage(image);
		$("#imageURIEdit").val(image);
		artist.image_url_logo = image;
		artist.artist_image_logo = url_path;
	    },
	    image_size:{
		width:320,
		height: 100
	    },
	    container_size:{
		width:320,
		height: 100
	    },
	    create_thumb: false,
	    image_catagory_name: 'line_up_artist_logo_images',
	    image_base_name:'temp_file_replace_QFHEHFNELLK3432',
	    empty_image: '../../../images/no_artist_logo.jpg',
	    hint_text: 'Click here to edit'
	});

	$('#stageThumbImage').imagechooser({
	    method:'upload',
	    callback: function(image_url, thumb, file_path) {
		$('#stageThumbImage').imagechooserLoadImage(image_url);
		stagesController.temp_image_url = image_url;
		stagesController.temp_file_path = file_path;
	    },
	    image_size:{
		width:  stagesController.image_width,
		height: stagesController.image_height
		},
	    container_size:{
		width:  stagesController.image_width,
		height: stagesController.image_height
		},
	    create_thumb: false,
	    image_catagory_name: 'line_up_stage_images',
	    image_base_name:'temp_file_replace_QFHEHFNELLK3432',
	    empty_image: '../../../images/lineup_no_stage_image.jpg',
	    hint_text: 'Click here to edit'
	});


	$('.ui-dialog-buttonpane button:contains(Save)').attr("id","artist_save_button");
	$('.ui-dialog-buttonpane button:contains(AddFestival)').attr("id","festival_save_button");
	$('#festival_save_button').html('Save');

	$('.ui-dialog-buttonpane button:contains(AddStage)').attr("id","stage_save_button");
	$('#stage_save_button').html('Save');

	$('.ui-dialog-buttonpane button:contains(AddShowTime)').attr("id","show_save_button");
	$('#show_save_button').html('Save');

	artistsController.showArtistsList();
	festivalDaysController.init();
	stagesController.init();
	mainController.init();
	$('#list_3_container').hide();
	$('#list_4_container').hide();
	$('#list_5_container').hide();

	$(document).keypress(function(e){
	    if (stagesController.showing_stages) {
		stagesController.changeSortingOrder(e.keyCode, e);
		
	    }
	});

    }

};

$(globals.init);

// ************************************************************************************************

var mainController = {
    beingDraged: null,

    init: function() {
	$('#list_4').droppable({
	    activeClass: "drag-active",
	    hoverClass: "drag-hover",
	    drop: function( event, ui ) {
		festivalDaysController.itemDroped(mainController.beingDraged);
	    }
	});
	
	$('#list_5').droppable({
	    activeClass: "drag-active",
	    hoverClass: "drag-hover",
	    drop: function( event, ui ) {
		showController.onArtistDropped(ui.draggable);
	    }
	});
    },

    swapContainers: function(container1, container2) {
	var cont1 = $('#' + container1 + '_container');
	var cont2 = $('#' + container2 + '_container');

	cont1.fadeOut(300, function() {
	    cont2.fadeIn(300);
	});
    }
}

var showController = {

     edit_mode: false,
     artist_id: -1,
     dialog_opened: false,

    onArtistDropped: function(html_el) {
	var html_obj = $(html_el);
	html_obj.fadeOut(300,function(){
	    html_obj.fadeIn(300);
	});
	this.artist_id = html_obj.find('.artistId').html();
	if($('#chkPreventShowingShowDialog').is(':checked')) {
	    $("#addShowTimeDialog input").val("");
	    showController.createNewShow();
	    showController.dialog_opened = false;
	} else {
	    this.showAddShowTimeDialog();
	    showController.dialog_opened = true;
	}
    },

    showAddShowTimeDialog: function(){
	$("#addShowTimeDialog input").val("");
	$('#addShowTimeDialog').dialog('option', 'title', 'Add Show Time');

	$('#show_save_button').removeClass('ui-state-disabled');
	$('#show_save_button').attr("disabled", false);
	$('#show_save_button').html("Save");
	
	$("#addShowTimeDialog").dialog("open");
    },

    createNewShow: function(){
	var show_time = $("#txtShowTime").val();
	var show_end_time = $("#txtShowEndTime").val();

	if (show_time != ""){
	    if (this.isValidateTime(show_time) == false){
		alert("Please enter correct time in the given format.");
		return;
	    }
	} else {
	    show_time = "";
	}
	
	if (show_end_time != ""){
	     if (this.isValidateTime(show_end_time) == false){
		alert("Please enter correct time in the given format.");
		return;
	    }
	}
	else {
	    show_end_time = "";
	}

	this.edit_mode = false;
	var festival_id = festivalDaysController.current_day.festival_id;
	var stage_id = stagesController.current_stage.stage_id;
	var show = new Show(festival_id, stage_id, this.artist_id, show_time, show_end_time, "", "",0);
	show.create(function(rtn, duplicate){
	    if (rtn){
		$("#list_5").append(show.html_obj);

		if(showController.dialog_opened) {
		    $("#addShowTimeDialog").dialog("close");
		}
		
		stagesController.current_stage.shows_array.push(show);
		stagesController.current_stage.updateShowsList();
		$.jGrowl("Successfully created the show...");

	    } else{
		$("#addShowTimeDialog").dialog("close");
		if(duplicate)
		    alert("Error occured. Show conflict with an identical show.");
		else
		    alert("An error occuered while creating the show.");
	    }
	});

	$('#show_save_button').addClass('ui-state-disabled');
	$('#show_save_button').attr("disabled", true);
	$('#show_save_button').html("Saving...");
    },

    isValidateTime: function(timeStr){

	var matches = /^(\d{1,2}):(\d{1,2})(:(\d{2}))?$/.exec(timeStr); // YYYY-MM-DD
	if (matches == null) return false;
	var ss = 0;
	var hr = matches[1];
	var mm = matches[2];

	if (matches[4])
	    ss = matches[4];

	var composedDate = new Date(79,5,24, hr, mm, ss);
	return (composedDate.getHours() == hr && composedDate.getMinutes() == mm && composedDate.getSeconds() == ss) ;
    },

    updateShow: function(show, new_time, elem){
	show.show_time = new_time;
	show.update(function(rtn){
	    if (rtn) {
		show.updateHtmlObject();
		stagesController.current_stage.updateShowsList();
		$.jGrowl("Successfully edited the the show...");
	    } else {
		alert("An error occuered while editing the show.")
	    }
	});
    },
    
    updateShowEndTime: function(show, new_end_time, elem){
	show.show_end_time = new_end_time;
	show.updateEndTime(function(rtn){
	    if (rtn) {
		show.updateHtmlObject();
		//stagesController.current_stage.updateShowsList(); gawri...
		$.jGrowl("Successfully edited the the show...");
	    } else {
		alert("An error occuered while editing the show.")
	    }
	});
    },

    deleteShow: function(show){
	show.remove(function(rtn){
	    if (rtn){
		show.html_obj.css('background-color', 'pink');
		show.html_obj.hide(300);
		$.jGrowl("Successfully deleted the show...");
	    } else{
		alert("An error occuered while deleting the show.");
	    }
	});
    }
}

function Show(festival_id, stage_id, artist_id, show_time, show_end_time, artist_image_url, artist_name, show_id){
    this.festival_id = festival_id;
    this.show_id = show_id;
    this.stage_id = stage_id;
    this.artist_id = artist_id;
    this.show_time = show_time;
    this.show_end_time = show_end_time;
    this.artist_image_url = artist_image_url;
    this.artist_name = artist_name;

    this.html_obj = $("<li>" +
	"<img class='show_image' src='' />" +
	"<div class='show_name'></div>" +
	"<div class='show_time'></div>" +
	"<div class='show_end_time'></div>" +
	"<div class='show_options'>" +
        "<div class='tahoma_12_blue' id='iconBox'>" +
	    "<div id='icon' ></div>" +
	    "<img class = 'delete_btn' src='../../../images/cross.png' border='0' />"+
        "</div></div></li>");


    var _self = this;

    this.html_obj.find('.delete_btn').click (function(e) {
	showController.deleteShow(_self);
    });

    this.html_obj.find('.show_time').editable(
    function(value, settings) {
	if(value=='' || showController.isValidateTime(value)) {
	    showController.updateShow(_self, value, this);
	    return("<img src='../../../images/bigrotation2.gif' width='16px'/>");
	} else {
	    alert("Invalid time. Enter the time in either the HH:MM:SS or HH:MM format.");
	    return(_self.show_time);
	}
    },{
	indicator : 'Saving...',
	tooltip   : 'Click to edit and Enter to save',
	placeholder: 'Click to Edit Time'
    });
    
    this.html_obj.find('.show_end_time').editable(
    function(value1, settings1) {
	if(value1 == '' || showController.isValidateTime(value1)) {
	    showController.updateShowEndTime(_self, value1, this);
	    return("<img src='../../../images/bigrotation2.gif' width='16px'/>");
	} else {
	    alert("Invalid time. Enter the time in either the HH:MM:SS or HH:MM format.");
	    return(_self.show_end_time);
	}
    },{
	indicator : 'Saving...',
	tooltip   : 'Click to Edit End Time and Enter to save',
	placeholder: 'Click to Edit Time'
    });
}

Show.prototype.create = function(callback){
    var _self = this;

    $.post("LineUpController.php?action=save_show", {
	"festival_id": this.festival_id,
	"stage_id": this.stage_id,
	"artist_id": this.artist_id,
	"show_time" : this.show_time,
	"show_end_time" : this.show_end_time
    },
    function(data) {
	if (data.error){
	    callback(false, data.duplicate);
	} else {
	    _self.artist_image_url = data.result['image_url'];
	    _self.artist_name = data.result['artist_name'];
	    _self.show_id = data.result['show_id'];

	    _self.updateHtmlObject();
	    callback(true, data.duplicate);
	}
    }, 'json');
}

Show.prototype.updateHtmlObject = function(){
    //var newDate= new Date;
    //var src = this.stage_image_url;
    // src = src +'?prevent_cache='+newDate.getTime()

    if (this.artist_image_url == '') {
	this.html_obj.find('.show_image').attr('src', '../../../images/lineup_no_image.jpg');
    } else {
	this.html_obj.find('.show_image').attr('src', this.artist_image_url);
    }
    
    this.html_obj.find('.show_name').html(this.artist_name);
    
    var pad = function(number, length) {
	var str = '' + number;
	while (str.length < length) {
	    str = '0' + str;
	}

	return str;
    }
    
    if (this.show_time=='' || this.show_time==null)
	this.html_obj.find('.show_time').html('Click to Edit Time');
    else {
	var times = this.show_time.split(':');
	var showTime = pad(times[0],2) + ':' + pad(times[1],2);
	this.html_obj.find('.show_time').html(showTime);
    }
    
    if (this.show_end_time == '' || this.show_end_time == null)
    {
	this.html_obj.find('.show_end_time').html('Click to Edit Time');
    }
    else{
	var times = this.show_end_time.split(':');
	var showEndTime = pad(times[0],2) + ':' + pad(times[1],2);
	this.html_obj.find('.show_end_time').html(showEndTime);
    }
}

Show.prototype.remove = function(callback){
    var _self = this;
    $.post("LineUpController.php?action=delete_show", {
	"show_id": this.show_id
    },
    function(data) {
	if (data == "OK"){
	    var count = stagesController.current_stage.shows_array.length;

	    for ( var i = 0; i < count; i++ ){
		var show = stagesController.current_stage.shows_array[i];

		if (_self.show_id == show.show_id ) {
		    delete stagesController.current_stage.shows_array[i];
		    break;
		}
	    } 
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

Show.prototype.update = function(callback) {
    $.post("LineUpController.php?action=update_show", {
	"show_id": this.show_id,
	"show_time_new" : this.show_time
    },
    function(data) {
	if (data == "OK"){
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

Show.prototype.updateEndTime = function(callback) {
    $.post("LineUpController.php?action=update_show_end_time", {
	"show_id": this.show_id,
	"show_end_time_new" : this.show_end_time
    },
    function(data) {
	if (data == "OK"){
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

// ************************************************************************************************
var artistsController = {
    page_number: 1,
    total_pages: 0,
    rows_per_page: 8,
    image_size: 50,
    edit_mode: false,

    showCreateArtistDialog: function() {
	$("#createArtistDialog input, #createArtistDialog textarea").val("");
	$("#artistThumbImage").attr("src", '../../../images/lineup_no_image.jpg');
	$('#createArtistDialog').dialog('option', 'title', 'Add New Artist');
	$("#artistLogoImage").attr("src", '../../../images/no_artist_logo.jpg');

	artist.image_url = "";
	artist.artist_image = '';
	artist.artist_image_logo = '';
	artist.image_url_logo = '';
	this.edit_mode = false;

	$('#artist_save_button').removeClass('ui-state-disabled');
	$('#artist_save_button').attr("disabled", false);
	$('#artist_save_button').html("Save");

	$("#createArtistDialog").dialog("open");
    },

    createNewArtist: function(){
	artist.createArtist(function(rtn){
	    if (rtn){
		$("#createArtistDialog").dialog("close");
		$.jGrowl("Successfully added a new artist...");
		artistsController.showArtistsList();
	    } else{
		$("#createArtistDialog").dialog("close");
		alert("An error occuered while adding the artist.");
	    }
	});
    },

    showArtistsList: function() {
	this.page_number = 1;
	$("#btnSearch").attr("src", "../../../images/btnSearchDissabled.png");
	artistsController.refreshArtistsList();
    },

    refreshArtistsList: function() {
	var searchText = $("#txtSearchArtistName").val();

	$("#list_1 li").remove();
	$("#waitingSearchWheel").show();

	$.post("LineUpController.php?action=search_artists", {
	    "searchText": searchText,
	    "pageNumber": this.page_number,
	    "rowsPerPage": this.rows_per_page
	    },
	function(data) {
	    if (data.error) {
		alert("An error occured while the search")
	    } else {
		$("#waitingSearchWheel").hide();
		$("#list_1").fadeIn(300);
		$("#btnSearch").attr("src", "../../../images/btnSearch.png")
		$("#list_1").append(data.html);

		$("#list_1 li").draggable({
		    revert: "invalid", helper: "clone"
		});// $(this).attr('id');

		var totalRecords = data.totalRecords;
		$('#viewResult').html("");
		artistsController.total_pages = Math.ceil(totalRecords/artistsController.rows_per_page);
		$('#totalDiv').html('Page: '+ artistsController.page_number + '&nbsp;&nbsp;&nbsp;&nbsp;Total Pages: '+ artistsController.total_pages +  '&nbsp;&nbsp;&nbsp;&nbsp;Artists: '+ totalRecords);
		artistsController.updateButtons();
	    }
	}
	,'json');
    },

    updateButtons: function(){
	if (this.page_number == 1) {
	    $('#imgPrev').attr('src', '../../../images/btn_prev_disabled.png');
	} else {
	    $('#imgPrev').attr('src', '../../../images/btn_prev.png');
	}

	if (this.page_number == this.total_pages) {
	    $('#imgNext').attr('src', '../../../images/btn_next_disabled.png');
	} else {
	    $('#imgNext').attr('src', '../../../images/btn_next.png');
	}
    },

    goPreviousPage: function(){
	if(this.page_number > 1) {
	    this.page_number = this.page_number - 1;
	    this.refreshArtistsList();
	}
    },
   
    goNextPage: function(){
	if(this.page_number < this.total_pages) {
	    this.page_number = this.page_number + 1;
	    this.refreshArtistsList()();
	}
    },

    deleteArtist: function(ArtistId){
	$("#list_1 #artist_id_" + ArtistId).css('background-color', 'pink');
	$("#list_1 #artist_id_" + ArtistId).hide(500);
	artist.deleteArtist(ArtistId, function(rtn){
	    if (rtn){
		$.jGrowl("Successfully deleted the artist...");
	    } else{
		alert("An error occuered while deleting the user.");
	    }
	});
    },

    showEditArtistDialog: function(ArtistId){
	artist.image_url = '';
	artist.artist_image = '';
	artist.artist_image_logo = '';
	artist.image_url_logo = '';
	$('#artist_save_button').addClass('ui-state-disabled');
	$('#artist_save_button').attr("disabled", true);
	$('#artist_save_button').html("Loading...");
	$("#createArtistDialog input, #createArtistDialog textarea").val("").attr('disabled', true);
	$('#createArtistDialog').dialog('option', 'title', 'Edit Artist');
	$("#createArtistDialog").dialog("open");

	artist.getArtistInfo(ArtistId, function(rtn){
	    if (! rtn.error){
		artistsController.edit_mode = true;
		artist.showArtistInfo();
		$('#artist_save_button').removeClass('ui-state-disabled');
		$('#artist_save_button').attr("disabled", false);
		$('#artist_save_button').html("Save");
		$("#createArtistDialog input, #createArtistDialog textarea").attr('disabled', false);
	    } else{
		alert("An error occured while accessing the artist info.");
	    }
	});

    },

    updateArtist: function(){
	artist.artist_name = $("#txtArtistName").val();
	artist.artist_biography = $("#txtBiography").val();
	artist.artist_web_url = $("#txtWebUrl").val();
	artist.artist_facebook = $("#txtArtistFacebook").val();
	artist.artist_twitter = $("#txtArtistTwitter").val();
	artist.artist_video = $("#txtArtistVideo").val();
	artist.artist_music = $("#txtArtistMusic").val();
	artist.artist_site_image = $("#txtArtistSiteImg").val();
	artist.artist_site_logo = $("#txtArtistSiteLogo").val();

	if (artist.artist_web_url != ""){
	    if (artist.isUrl(artist.artist_web_url) == false){
		alert("Invalid web URL");
		return;
	    }
	}

	$('#artist_save_button').addClass('ui-state-disabled');
	$('#artist_save_button').attr("disabled", true);
	$('#artist_save_button').html("Updating...");
	artist.updateArtistInfo(function(rtn){
	    if (rtn){
		$("#createArtistDialog").dialog("close");
		$.jGrowl("Successfully updated the artist information...");

		$("#artist_id_"+ artist.artist_id + " .artistName").html(artist.artist_name);

		var src = artist.image_url.replace('temp_file_replace_QFHEHFNELLK3432',artist.artist_id);
		if (src!='') {
		    var newDate= new Date;
		    src = src +'?prevent_cache='+newDate.getTime()
                    
		    $("#artist_id_"+ artist.artist_id + " .artistImage").attr('src', src);
		}
	    } else{
		$("#createArtistDialog").dialog("close");
		alert("An error occuered while updating information.");
	    }
	});
    }
}

// ************************************************************************************************
var artist = {
    artist_id: 0,
    artist_name: "",
    artist_biography: "",
    artist_image: "",
    image_url:"",
    artist_web_url: "",
    artist_facebook: "",
    artist_twitter: "",
    artist_video: "",
    artist_music: "",
    image_url_logo: "",
    artist_image_logo: "",
    artist_site_image: "",
    artist_site_logo: "",
    
    createArtist: function(callback){
	this.artist_name = $("#txtArtistName").val();
	this.artist_biography = $("#txtBiography").val();

	this.artist_web_url = $("#txtWebUrl").val();
	this.artist_facebook = $("#txtArtistFacebook").val();
	this.artist_twitter = $("#txtArtistTwitter").val();
	this.artist_video = $("#txtArtistVideo").val();
	this.artist_music = $("#txtArtistMusic").val();

	this.artist_site_image = $("#txtArtistSiteImg").val();
	this.artist_site_logo = $("#txtArtistSiteLogo").val();

	if (this.artist_name == ""){
	    alert("Please fill all the fields and uplaod the image.");
	    return;
	}

	if (this.artist_web_url != ""){
	    if (this.isUrl(this.artist_web_url) == false){
		alert("Invalied web url.");
		return;
	    }
	}
	
	$.post("LineUpController.php?action=save_artist", {
	    "artistName": this.artist_name,
	    "biography": this.artist_biography,
	    "imageUrl": this.artist_image,
	    "artist_web_url": this.artist_web_url,
	    "artist_facebook": this.artist_facebook,
	    "artist_twitter": this.artist_twitter,
	    "artist_image_logo": this.artist_image_logo,
	    "artist_video": this.artist_video,
	    "artist_music": this.artist_music,
	    "artist_site_image": this.artist_site_image,
	    "artist_site_logo": this.artist_site_logo
	},
	
	function(data) {
	    if (data == "OK"){
		callback(true);
	    } else {
		callback(false);
	    }
	});

	$('#artist_save_button').addClass('ui-state-disabled');
	$('#artist_save_button').attr("disabled", true);
	$('#artist_save_button').html("Saving...");
    },

    deleteArtist: function(ArtistId, callback){
	$.post("LineUpController.php?action=delete_artist", {
	    "artistId": ArtistId
	},
	function(data) {
	    if (data == "OK"){
		callback(true);
	    } else {
		callback(false);
	    }
	});
	return false;
    },

    getArtistInfo: function(ArtistId, callback){

	$.post("LineUpController.php?action=get_artist_info", {
	    "artistId": ArtistId
	},
	function(data) {
	    if (data.error) {
		callback(false);
	    } else {
		artist.artist_name = data.data.artist_name;
		artist.artist_biography = data.data.biography;
		artist.artist_image = data.data.image_url;
		artist.artist_id = ArtistId;
		artist.artist_web_url = data.data.artist_web_url;
		artist.artist_facebook = data.data.artist_facebook;
		artist.artist_twitter = data.data.artist_twitter;
		artist.artist_image_logo = data.data.artist_image_logo;
		artist.artist_video = data.data.artist_video;
		artist.artist_music = data.data.artist_music;
		artist.artist_site_image = data.data.site_img;
		artist.artist_site_logo = data.data.site_logo;
		callback(data);
		artist.artist_image = '';
		artist.artist_image_logo = '';
	    }
	}, 'json');
    },

    showArtistInfo: function(){
	$("#txtArtistName").val(this.artist_name);
	$("#txtBiography").val(this.artist_biography);
	$("#txtArtistName").val(this.artist_name);

	$("#txtWebUrl").val(this.artist_web_url);
	$("#txtArtistFacebook").val(this.artist_facebook);
	$("#txtArtistTwitter").val(this.artist_twitter);
	$("#txtArtistVideo").val(this.artist_video);
	$("#txtArtistMusic").val(this.artist_music);
	 $("#txtArtistSiteImg").val(this.artist_site_image);
	 $("#txtArtistSiteLogo").val(this.artist_site_logo);

	if (this.artist_image == '') {
	    $("#artistThumbImage").attr("src", '../../../images/lineup_no_image.jpg');
	} else {
	    var newDate= new Date;
	    var src = this.artist_image +'?prevent_cache=' + newDate.getTime()
	    $("#artistThumbImage").attr("src", src);
	}

	if (this.artist_image_logo != '') {
	    newDate= new Date;
	    src = this.artist_image_logo +'?prevent_cache=' + newDate.getTime()
	    $("#artistLogoImage").attr("src", src);
	} else {
	    $("#artistLogoImage").attr("src", '../../../images/no_artist_logo.jpg');
	}
    },

    updateArtistInfo: function(callback){

	$.post("LineUpController.php?action=update_artist_info", {
	    "artist_id": this.artist_id,
	    "artist_name": this.artist_name,
	    "artist_biography": this.artist_biography,
	    "artist_image": this.artist_image,
	    "artist_web_url": this.artist_web_url,
	    "artist_facebook": this.artist_facebook,
	    "artist_twitter": this.artist_twitter,
	    "artist_image_logo": this.artist_image_logo,
	    "artist_video": this.artist_video,
	    "artist_music": this.artist_music,
	    "artist_site_image": this.artist_site_image,
	    "artist_site_logo": this.artist_site_logo
	},
	function(data) {
	    if (data == "OK"){
		callback(true);
	    } else {
		callback(false);
	    }
	});
    },

    isUrl: function(url) {
	var regexp = /(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	return regexp.test(url);
    }
}

// ************************************************************************************************
var festivalDaysController = {

    edit_mode: false,
    current_day: null,

    showCreateFestivalDayDialog: function() {
	$("#createFestivalDayDialog input").val("");
	$('#createFestivalDayDialog').dialog('option', 'title', 'Create new festival day');

	$('#festival_save_button').removeClass('ui-state-disabled');
	$('#festival_save_button').attr("disabled", false);
	$('#festival_save_button').html("Save");

	$("#createFestivalDayDialog").dialog("open");
    },

    createNewFestivalDay: function(){
	var festival_name = $("#txtFestivalName").val();
	var festival_date = $("#txtFestivalDate").val();

	if (festival_name == "" || (this.isValidDate(festival_date) == false)){
	    alert("Please enter correct data.");
	    return;
	}

	this.edit_mode = false;

	var festivalDay = new FestivalDay(festival_name, festival_date, -1);
	festivalDay.create(function(rtn){
	    if (rtn){
		$("#list_2").append(festivalDay.html_obj);

		$("#createFestivalDayDialog").dialog("close");
		$.jGrowl("Successfully added a festival date...");
                 
	    } else{
		$("#createFestivalDayDialog").dialog("close");
		alert("An error occuered while adding the artist.");
	    }
	});

	$('#festival_save_button').addClass('ui-state-disabled');
	$('#festial_save_button').attr("disabled", true);
	$('#festival_save_button').html("Saving...");
    },

    isValidDate: function(date)
    {
	var matches = /^(\d{4})[-\/](\d{2})[-\/](\d{2})$/.exec(date); // YYYY-MM-DD
	if (matches == null) return false;
	var y = matches[1];
	var m = matches[2] - 1;
	var d = matches[3];
	
	var composedDate = new Date(y, m, d);
	return (composedDate.getDate() == d && composedDate.getMonth() == m && composedDate.getFullYear() == y) ;
    },

    showUpdateFestivalDayDialog: function(festivalDay) {
	$("#createFestivalDayDialog input, #createFestivalDayDialog textarea").val("");
	$("#txtFestivalName").val(festivalDay.festival_name);
	$("#txtFestivalDate").val(festivalDay.festival_date);
	$('#createFestivalDayDialog').dialog('option', 'title', 'Edit festival day');
	$("#createFestivalDayDialog").dialog("open");

	$('#festival_save_button').removeClass('ui-state-disabled');
	$('#festival_save_button').attr("disabled", false);
	$('#festival_save_button').html("Save");

	this.edit_mode = true;
	this.current_day = festivalDay;
    },

    updateFestivalDay: function(){

	var festival_name = $("#txtFestivalName").val();
	var festival_date = $("#txtFestivalDate").val();

	if (festival_name == "" || (this.isValidDate(festival_date) == false)){
	    alert("Please enter correct data.");
	    return;
	}

	this.current_day.setdata(festival_name, festival_date);
	this.current_day.update(function(rtn){
	    if (rtn){
		$("#createFestivalDayDialog").dialog("close");
		$.jGrowl("Successfully updated the festival day...");
	    } else{
		alert("An error occuered while updating the festival day.");
		$("#createFestivalDayDialog").dialog("close");
	    }
	});

	$('#festival_save_button').addClass('ui-state-disabled');
	$('#festival_save_button').attr("disabled", true);
	$('#festival_save_button').html("Updating...");
    },

    deleteFestivalDay: function(festivalDay){
	festivalDay.html_obj.find('.festival_name, .festival_date').css('opacity', '0.2');

	festivalDay.remove(function(rtn){
	    if (rtn){
		festivalDay.html_obj.hide(500);
		$.jGrowl("Successfully deleted the festival day...");
	    } else{
		alert("An error occuered while deleting the festival day.");
	    }
	});
    },

    init: function(){

	$("#list_2 li").remove();
	$("#festivalDayswaitingWheel").show();
	
	$.post("LineUpController.php?action=get_festival_days",{},
	function(data) {

	    if (data.error) {
		alert("An error occured while loading the festival days.")
	    } else {
		$("#festivalDayswaitingWheel").hide();
		var count = data.festival_days.length;
		for ( var i = 0; i < count; i++ ){
		    var day = data.festival_days[i];
		    var festival_day = new FestivalDay(day['festival_name'], 
		    day['festival_date'], day['id']);

		    $("#list_2").append(festival_day.html_obj);
		}

		/*var x;
		for(x in festivalDaysController.map_festival_days) {
		    alert(festivalDaysController.map_festival_days[x].festival_name);

		}*/
	    }
	}, 'json');
    },

    onDayClick: function(day) {
	mainController.swapContainers('list_1', 'list_3');
	mainController.swapContainers('list_2', 'list_4');
	stagesController.showing_stages = true;
	$('.days_title_cls').html("Stages of '"+ day.festival_name +"'");
	
	this.current_day = day;

	if (day.is_stages_loaded){
	    festivalDaysController.showStagesforDay();
	    return;
	}

	$('#festivalDayStagesWaitingWheel').show();
	
	day.loadStages(function(rtn){
	    if (rtn){
		$('#festivalDayStagesWaitingWheel').hide();
		festivalDaysController.showStagesforDay();
	    } else{
		alert("An error occuered while loading stages.");
	    }
	});
    },

    showStagesforDay: function(){
	var indexes = new Array;
	var ids = new Array;

	for (var i in festivalDaysController.current_day.stage_orders_map) {
	    indexes.push(festivalDaysController.current_day.stage_orders_map[i]);
	    ids[i]=festivalDaysController.current_day.stage_orders_map[i];
	}

	indexes.sort();

	for(var i in indexes) {
	    var index = indexes[i];

	    for(var j in ids) {
		if (ids[j]==index) {

		    if(festivalDaysController.current_day.map_festival_day_stages[j]) {
			var stage = festivalDaysController.current_day.map_festival_day_stages[j];

			stage.html_obj.find('.edit_btn').hide();
			stage.html_obj.find('.delete_btn').hide();
			stage.html_obj.find('.secondary_delete_btn').show();

			$('#list_4').append(stage.html_obj);

			stage.html_obj.draggable( "option", "disabled", true ).removeClass('ui-state-disabled');
			stage.setClickHandler();
		    }
		    
		    delete ids[j];
		}
	    }
	}

	
    },

    itemDroped: function(stage) {

	if (this.current_day.map_festival_day_stages[stage.stage_id]){
	    alert("already added to the festival day");
	    return;
	}

	stage.html_obj.fadeOut(300, function(){
	    stage.html_obj.find('.edit_btn').hide();
	    stage.html_obj.find('.delete_btn').hide();
	    stage.html_obj.find('.secondary_delete_btn').show();
	    $('#list_4').prepend(stage.html_obj);
	    stage.html_obj.fadeIn(300);
	});

	//stagesController.current_stage = stage; // meka waradi neda. .ow eka thama kiwe
	stage.html_obj.bind('click', function(){
	    if(stage.html_obj.parent().attr('id')=='list_4')
		stagesController.onStageClick(stage);
	});
	
	this.current_day.addStage(stage, function(rtn){
	    if (rtn){
		$.jGrowl("Successfully added a stage to the date...");
	    } else{
		alert("An error occuered while adding the stage.");
	    }
	});
    },

    onBackToDaysClick: function() {
	
	mainController.swapContainers('list_3', 'list_1');
	mainController.swapContainers('list_4', 'list_2');
	stagesController.showing_stages = false;
	

	$("#list_4 li").find('.edit_btn').show();
	$("#list_4 li").find('.secondary_delete_btn').hide();
	$("#list_4 li").find('.delete_btn').show();
	
	$("#list_4 li").appendTo('#list_3').draggable( "option", "disabled", false );
    }
}

//*************************************************************************************************

function FestivalDay(festival_name, festival_date, festival_id){

    this.festival_name = festival_name;
    this.festival_date = festival_date;
    this.festival_id = festival_id;
    this.map_festival_day_stages = new Array;
    this.is_stages_loaded = false;
    this.stage_orders_map = new Array;

    this.html_obj = $("<li>" +
	"<div class=\"festival_name\"></div>" +
	"<div class=\"festival_date\"></div>" +
	"<div class=\"festival_date_options\"><div class=\"tahoma_12_blue\" id=\"iconBox\" style='width: 121px'>" +
	"<div id=\"icon\" style=\"cursor: pointer; width: 30px;\"><a>" +
	"<img class='edit_btn' src=\"../../../images/file.png\" border=\"0\" /></a></div>" +
	"<div id=\"icon\"><a>" +
	"<img class='delete_btn' src=\"../../../images/cross.png\" border=\"0\" /></a></div></div></div>" +
	"</li>");


    this.updateHtmlObject();

    var _self = this;

    this.html_obj.find('.edit_btn').click (function(e) {
	festivalDaysController.showUpdateFestivalDayDialog(_self);
	e.stopPropagation();
    });

    this.html_obj.find('.delete_btn').click (function(e) {
	festivalDaysController.deleteFestivalDay(_self);
	e.stopPropagation();
    });

    this.html_obj.click (function(e) {
	festivalDaysController.onDayClick(_self);
    });

    this.html_obj.find('.festival_name, .festival_date').css('opacity',0.7);
}

FestivalDay.prototype.updateHtmlObject = function(){
    this.html_obj.find('.festival_name').html(this.festival_name);
    var comps = this.festival_date.split('-');
    this.html_obj.find('.festival_date').html(comps[2]);
}

FestivalDay.prototype.create = function(callback){
    var _self = this;

    $.post("LineUpController.php?action=save_festival_day", {
	"festival_name": this.festival_name,
	"festival_date": this.festival_date
    },
    function(data) {
	if (data == "ERROR"){
	    callback(false);
	} else {
	    _self.festival_id = data;
	    callback(true);
	}
    });
}

FestivalDay.prototype.remove = function(callback){
    $.post("LineUpController.php?action=delete_festival_day", {
	"festival_id": this.festival_id
    },
    function(data) {
	if (data == "OK"){
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

FestivalDay.prototype.update = function(callback){
    $.post("LineUpController.php?action=update_festival_day", {
	"festival_id": this.festival_id,
	"festival_name": this.festival_name,
	"festival_date": this.festival_date
    },
    function(data) {
	if (data == "OK"){
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

FestivalDay.prototype.setdata = function(festival_name, festival_date){
    this.festival_name = festival_name;
    this.festival_date = festival_date;
}

FestivalDay.prototype.updateStagesOrderIndexes = function (callback) {
    var item_id_arr = new Array();
    var item_id_index = new Array();

    for(x in this.map_festival_day_stages) {
	var stage = this.map_festival_day_stages[x];
	item_id_arr.push(stage.stage_id);
	item_id_index.push(this.stage_orders_map[stage.stage_id]);
    }

    $.post("LineUpController.php?action=update_stages_order_indexes", {
	"festival_id": this.festival_id,
	"item_id_arr[]":item_id_arr,
	"item_id_index[]":item_id_index
    },
    function(data) {
	if (data == "OK"){
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

FestivalDay.prototype.loadStages = function(callback){
    
    var _self = this;

    $.post("LineUpController.php?action=load_stages_id", {
	"festival_id": this.festival_id
    },
    function(data) {
	//console.log(data);
	if (data.error){
	    callback(false);
	} else {

	    var id;
	    for (id in data.stages_id){
		_self.stage_orders_map[data.stages_id[id].stage_id] = data.stages_id[id].order_index;
		var stage = stagesController.map_stages[data.stages_id[id].stage_id];
		_self.map_festival_day_stages[stage.stage_id] = stage;
	    }
	    _self.is_stages_loaded = true;
	    callback(true);
	}
    }, 'json');
}

FestivalDay.prototype.addStage = function(stage, callback) {
    var _self = this;

    $.post("LineUpController.php?action=save_festival_day_stage", {
	"festival_id": this.festival_id,
	"stage_id": stage.stage_id
    },
    function(data) {
	if (data == "ERROR"){
	    callback(false);
	} else {
	    _self.map_festival_day_stages[stage.stage_id] = stage;
	    _self.stage_orders_map[stage.stage_id] = 0;
	    
	    callback(true);
	}
    });
}

FestivalDay.prototype.removeStage = function(stage){

    var _self = this;

    stage.html_obj.fadeOut(300,function() {
	stage.html_obj.find('.edit_btn').show();
	stage.html_obj.find('.delete_btn').show();
	stage.html_obj.find('.secondary_delete_btn').hide();
	$('#list_3').append(stage.html_obj);
	stage.html_obj.fadeIn(300);
    }).draggable( "option", "disabled", false );
    	
    $.post("LineUpController.php?action=delete_festival_day_stage", {
	"festival_id": this.festival_id,
	"stage_id": stage.stage_id
    },
    function(data) {
	if (data == "ERROR"){
	    alert("An error occured while deleting the stage");
	} else {	    
	    delete _self.map_festival_day_stages[stage.stage_id];
	}
    });
}
// ************************************************************************************************
var stagesController = {
    image_width: 320,
    image_height: 50,
    temp_image_url: "",  
    temp_file_path: "",  
    edit_mode: false,
    temp_stage: null,
    map_stages: new Array,
    current_stage: null,
    sorting_mode: false,
    selected_stage: null,
    showing_stages: false,
 
    showCreateStageDialog: function(){
	$("#createStageDialog input").val("");
	$("#stageThumbImage").attr("src", '../../../images/lineup_no_stage_image.jpg');
	$('#createStageDialog').dialog('option', 'title', 'Create a new stage');

	$('#stage_save_button').removeClass('ui-state-disabled');
	$('#stage_save_button').attr("disabled", false);
	$('#stage_save_button').html("Save");

	$("#createStageDialog").dialog("open");
    },

    createNewStage: function(){
	var stage_name = $("#txtStageName").val();

	if ((stage_name == "")){
	    alert("Please fill all the fields and uplaod the image.");
	    return;
	}
	
	this.edit_mode = false;

	var stage = new Stage(stage_name, this.temp_file_path, "", -1);
	stage.create(function(rtn){
	    if (rtn){
		$("#list_3").append(stage.html_obj);
		 stagesController.map_stages[stage.stage_id] = stage;

		$("#createStageDialog").dialog("close");
		$.jGrowl("Successfully added a stage...");

	    } else{
		$("#createStageDialog").dialog("close");
		alert("An error occuered while adding the stage.");
	    }
	});

	$('#stage_save_button').addClass('ui-state-disabled');
	$('#stage_save_button').attr("disabled", true);
	$('#stage_save_button').html("Saving...");
    },

    showUpdateStageDialog: function(stage) {

	$("#createStageDialog input").val("");
	$("#txtStageName").val(stage.stage_name);
	$("#stageThumbImage").attr("src", stage.stage_image_url);
	$('#createStageDialog').dialog('option', 'title', 'Edit stage');
	$("#createStageDialog").dialog("open");

	$('#stage_save_button').removeClass('ui-state-disabled');
	$('#stage_save_button').attr("disabled", false);
	$('#stage_save_button').html("Save");

	this.edit_mode = true;

	this.temp_image_url = "";
	this.temp_file_path = "";

	this.temp_stage = stage;
    },

    updateStage: function(){

	var stage_name = $("#txtStageName").val();

	if (stage_name == ""){
	    alert("Please fill all the fields and uplaod the image.");
	    return;
	}
	
	stagesController.temp_stage.setdata(stage_name, stagesController.temp_file_path);

	this.temp_stage.update(function(rtn){
	    if (rtn){
		stagesController.temp_stage.updateHtmlObject();
		$("#createStageDialog").dialog("close");
		$.jGrowl("Successfully updated the stage...");
	    } else{
		alert("An error occuered while updating the stage.");
		$("#createStageDialog").dialog("close");
	    }
	});

	$('#stage_save_button').addClass('ui-state-disabled');
	$('#stage_save_button').attr("disabled", true);
	$('#stage_save_button').html("Updating...");
    },

    deleteStage: function(stage){
	stage.remove(function(rtn){
	    if (rtn){
		stage.html_obj.css('background-color', 'pink');
		stage.html_obj.hide(300);
		delete stagesController.map_stages[stage.stage_id];
		$.jGrowl("Successfully deleted the stage...");
	    } else{
		alert("An error occuered while deleting the stage.");
	    }
	});
    },

    onStageOpen: function(stage) {
	mainController.swapContainers('list_4', 'list_5');
	mainController.swapContainers('list_3', 'list_1');
	stagesController.showing_stages = false;

	$('.artists_title_cls').html("Artist of '"+ stage.stage_name +"'");

	this.current_stage = stage;

	$("#waitingSearchWheelShows").show();
	stage.loadShows(function(rtn){
	    $("#waitingSearchWheelShows").hide();
	    if (rtn){
		stage.updateShowsList();
	    } else{
		$('#festivalStagesArtistWaitingWheel').hide();
		alert("An error occuered while loading shows.");
	    }
	});
    },

    onStageClick: function(stage) {
	if(stagesController.sorting_mode) {
	    stagesController.selected_stage = stage;
	    $('#list_4 li').css('border',"none");
	    stage.html_obj.css('border',"red solid 1px");

	} else {
	    stagesController.onStageOpen(stage);
	}
    },
    
    backToStagesDropView: function() {
	mainController.swapContainers('list_5', 'list_4');
	mainController.swapContainers('list_1', 'list_3');
	stagesController.showing_stages = true;
    },

    toggleSortingMode: function() {
	if (stagesController.sorting_mode) {
	    stagesController.sorting_mode = false;
	    $('#stages_sorting_button').attr('src', '../../../images/lineup_sorting_dect.png');
	    $('#list_4 li').css('border',"none");
	    stagesController.selected_stage = null;

	    festivalDaysController.current_day.updateStagesOrderIndexes(function(rtn){
		if (rtn) {
		    $.jGrowl("Successfully updated order of the stages...");
		} else {
		    alert("Error occured while updating the order of the Stages");
		}
	    })
	} else {
	    stagesController.sorting_mode = true;
	    $('#stages_sorting_button').attr('src', '../../../images/lineup_sorting_act.png');
	}
    },

    changeSortingOrder: function(keyCode, e) {
	if (stagesController.selected_stage == null)
	    return;
	
	var selected = stagesController.selected_stage.html_obj;
	
	if (keyCode == 38) {
	    var prev = selected.prev();
	    if(prev.attr("tagName")=='LI') {
		$(selected).remove();
		prev.before(selected);
	    }
	    e.preventDefault();
	} else if (keyCode == 40) {
	    var next = selected.next();
	    if(next.attr("tagName")=='LI') {
		
		$(selected).remove();
		next.after(selected);
	    }
	    e.preventDefault();
	}

	if (keyCode == 40 || keyCode == 38) {
	    for(x in festivalDaysController.current_day.map_festival_day_stages) {
		var stage = festivalDaysController.current_day.map_festival_day_stages[x];
		festivalDaysController.current_day.stage_orders_map[stage.stage_id] = $('#list_4 li').index(stage.html_obj)
	    }
	}
    },

    init: function(){

	$("#list_3 li").remove();
	$("#waitingSearchWheelStages").show();

	$.post("LineUpController.php?action=get_stages",{},
	function(data) {
	    if (data.error) {
		alert("An error occured while loading stages.")
	    } else {
		$("#waitingSearchWheelStages").hide();
		count = data.stages.length;
		for ( var i = 0; i < count; i++ ){
		    var temp_stage = data.stages[i];
		    var stage = new Stage(temp_stage['stage_name'],
		    "", temp_stage['image_url'], temp_stage['id'], temp_stage['order_index']);

		    stage.updateHtmlObject();
		    $("#list_3").append(stage.html_obj);

		    stagesController.map_stages[stage.stage_id] = stage;
		}

		/*var x;
		for(x in stagesController.map_stages) {
		    alert(stagesController.map_stages[x].stage_name);

		}*/
	    }
	}, 'json');
    }
}

// ************************************************************************************************

function Stage(stage_name, stage_file_path, stage_image_url, stage_id){
    this.stage_name = stage_name;
    this.stage_file_path = stage_file_path; // local path
    this.stage_image_url = stage_image_url; // db data
    this.stage_id = stage_id;
    this.shows_array = new Array;

    this.html_obj = $("<li>" +
	"<img class='stage_image_url' src='' />" +
	"<div class='stage_name'></div>" +
	"<div class='stage_options'>" +
        "<div class='tahoma_12_blue' id='iconBox1'>" +
	    "<div id='icon1' ><img class = 'edit_btn' src='../../../images/file.png' border='0' /></div>" +
	    "<img class = 'delete_btn'style='padding-top:20px;padding-left:5px;' src='../../../images/cross.png' border='0' />"+
	    "<img class = 'secondary_delete_btn' src='../../../images/cross.png' border='0' />"+
        "</div></div></li>");

    this.html_obj.find('.secondary_delete_btn').hide();
    var _self = this;

    this.html_obj.find('.stage_image_url');
    
    this.html_obj.find('.edit_btn').click (function(e) {
	stagesController.showUpdateStageDialog(_self);
	e.stopPropagation()
    });

    this.html_obj.find('.delete_btn').click (function(e) {
	stagesController.deleteStage(_self);
	e.stopPropagation();
    });

    this.html_obj.find('.secondary_delete_btn').click (function(e) {
	festivalDaysController.current_day.removeStage(_self);
	e.stopPropagation();
    });

    this.html_obj.draggable({
	revert: "invalid", helper: "clone",
	start: function() {
	    mainController.beingDraged = _self;
	}
    });

}

Stage.prototype.updateHtmlObject = function(){
    this.html_obj.find('.stage_name').html(this.stage_name);
    var newDate= new Date;
    var src = this.stage_image_url;
    src = src +'?prevent_cache='+newDate.getTime()
    this.html_obj.find('.stage_image_url').attr('src', src);
}

Stage.prototype.create = function(callback){
    var _self = this;

    $.post("LineUpController.php?action=save_stage", {
	"stage_name": this.stage_name,
	"stage_file_path": this.stage_file_path
    },
    function(data) {
	if (data.error){
	    callback(false);
	} else {
	    _self.stage_image_url = data.image_url;
	    _self.stage_id = data.id;
	    _self.updateHtmlObject();
	    callback(true);
	}
    }, 'json');
}

Stage.prototype.remove = function(callback){
    $.post("LineUpController.php?action=delete_stage", {
	"stage_id": this.stage_id
    },
    function(data) {
	if (data == "OK"){
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

Stage.prototype.setdata = function (stage_name, stage_file_path){
    this.stage_name = stage_name;
    this.stage_file_path = stage_file_path;

    this.updateHtmlObject();
}

Stage.prototype.update = function(callback){
    $.post("LineUpController.php?action=update_stage", {
	"stage_id": this.stage_id,
	"stage_name": this.stage_name,
	"stage_file_path": this.stage_file_path
    },
    function(data) {
	if (data == "OK"){
	    callback(true);
	} else {
	    callback(false);
	}
    });
}

Stage.prototype.setClickHandler = function() {
    var _self = this;
    _self.html_obj.bind('click', function(){
	if(_self.html_obj.parent().attr('id')=='list_4')
	    stagesController.onStageClick(_self);
    });
}

Stage.prototype.loadShows = function(callback) {
    var _self = this;

    var festival_id = festivalDaysController.current_day.festival_id;
    var stage_id = stagesController.current_stage.stage_id;

    $("#list_5 li").remove();
    $.post("LineUpController.php?action=load_shows", {
	"festival_id": festival_id,
	"stage_id": stage_id
    },
    function(data) {
	if (data.error) {
	    return false;
	} else {

	    var count = data.results.length;

	    for ( var i = 0; i < count; i++ ){
		var obj = data.results[i];
		
		if (obj['time'] == null) {
		    obj['time'] = '';
		}

		var show = new Show(festival_id, stage_id, obj['artist_id'],
		obj['time'], obj['end_time'], obj['image_url'], obj['artist_name'], obj['show_id']);

		_self.shows_array[i] = show;
	    }
	    callback(true);
	}
    }, 'json');
}

Stage.prototype.updateShowsList = function() {
    this.shows_array.sort(function (a,b) {
	if (a.show_time != '' && b.show_time == '') {
	    return -1;
	} else if (a.show_time == '' && b.show_time != '') {
	    return 1;
	} else if (a.show_time == '' && b.show_time == ''){
	    if (a.show_id < b.show_id) {
		return -1;
	    } else {
		return 1;
	    }
	}
	
	var formatTime = function(time) {
	    var timeArr = time.show_time.split(':');
	    if (!timeArr[2]) {
		timeArr[2] = 0;
	    }
	    
	    if (timeArr[0] == 12) {
		timeArr[0] = timeArr[0] - 12;
	    }
	    return timeArr;
	}
	
	var timeA = formatTime(a);
	var timeB = formatTime(b);
	
	if (timeA[0] > timeB[0]) {
	    return -1;
	} else if (timeA[0] < timeB[0]) {
	    return 1;
	} else {
	    if (timeA[1] > timeB[1]) {
		return -1;
	    } else if (timeA[1] < timeB[1]) {
		return 1;
	    } else {
		if (timeA[2] > timeB[2]) {
		    return -1;
		} else if (timeA[2] < timeB[2]) {
		    return 1;
		} else {
		    return 0;
		}
	    }
	}
    });
    
    var count = this.shows_array.length;

    $("#list_5 li").hide().appendTo('body');
    for ( var i = 0; i < count; i++ ){
	var show = this.shows_array[i];

	show.updateHtmlObject();
	$("#list_5").append(show.html_obj);
	show.html_obj.show();
    }    
}
