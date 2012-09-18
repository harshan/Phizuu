$.getScript('../../../common/ajaxfileupload.js');

(function($) {
    //
    // plugin definition
    //
    
    //Creating Dialogs on Load
    var fileInputCount = 0;

    $.fn.imagechooser = function(options) {
        var opts = $.extend({}, $.fn.imagechooser.defaults, options);
        // iterate and reformat each matched element
        this.each(function() {
            $.fn.imagechooser.prepareImages(this, opts);
        });

        return this;
    };

    $.fn.imagechooser.prepareImages = function(item, opts){
        if(item.funcs) {
            return; //Ignore already prepared items
        }

        var fileChooserInputId = 'imageChooserUploadedImage'+fileInputCount;

        var fileChooserInput = $('<input type="file" name="image"/> ')
                   .attr('id',fileChooserInputId);

        fileInputCount++;

        var settings = 'width='+opts.image_size.width;
        settings += '&height='+opts.image_size.height;
        settings += '&create_thumb='+opts.create_thumb;
        settings += '&thumb_width='+opts.thumb_size.width;
        settings += '&thumb_height='+opts.thumb_size.height;
        settings += '&image_catagory_name='+opts.image_catagory_name;
        settings += '&thumb_catagory_name='+opts.thumb_catagory_name;
        settings += '&crop_stage_width='+opts.crop_stage_width;
        settings += '&crop_stage_height='+opts.crop_stage_height;
        
        var $item = $(item);

        /*if ($item.attr('src')!=undefined && $item.attr('src')!='') {
            opts.container_size.width = $item.attr('width'); //If the size of the image is specified remember them
            opts.container_size.height = $item.attr('height');
        }*/

        var f = {
            showSelectImageFromBankDialog: function() {
                f.choose_from_bank_dialog.html(opts.dialog_load_text);
                f.choose_from_bank_dialog.dialog('open');
                f.choose_dialog.dialog('close');
                $.post('../../../controller/modules/common/CommonController.php?action=get_select_pic_list', {'editing_album':5}, function(data) {
                    f.choose_from_bank_dialog.hide().fadeOut(300, function(){
                        f.choose_from_bank_dialog.html(data)
                        .fadeIn(300)
                        .find('.bankListImageWrapperImageChooser')
                        .click(f.imageChoosenFromBankDialog);
                    });
                });
            },
            showSelectImageFromComDialog: function() {
                f.image_uploaded = false;
                f.bottom_div.html('');
                f.cropStatusSpan.html(' Select an image by pressing <b>Browse</b> button. Then press <b>Upload</b> button!');
                f.choose_from_com_dialog.dialog('open');
                f.choose_dialog.dialog('close');
            },
            itemClicked: function(){
                if (opts.method == 'ask') {
                    f.choose_dialog.dialog('open');
                } else if (opts.method == 'bank') {
                    f.showSelectImageFromBankDialog();
                } else if (opts.method == 'upload') {
                    f.showSelectImageFromComDialog();
                }
            },
            imageChoosenFromBankDialog: function() {
                var uri = $(this).find('.selURL').html();
                var thumbUri = $(this).find('.selThumbURL').html();

                if (opts.callback) {
                    opts.callback(uri, thumbUri);
                }
                
                f.choose_from_bank_dialog.dialog("close");
            },
            loadImage: function(src) {
                var newDate= new Date;
                src = src+'?prevent_cache='+newDate.getTime()
                var img = new Image;
                img.src = src;
                $item.attr('src',opts.loading_image.src);
                $item.attr('width',opts.loading_image.width);
                $item.attr('height',opts.loading_image.height);
                $(img).load(function(){
                    var tw = opts.container_size.width;
                    var th = opts.container_size.height;

                    var ow = $(img).attr('width');
                    var oh = $(img).attr('height');

                    var w = 0;
                    var h = 0;

                    w = tw;
                    h = (oh/ow)*w;
                    if (h>th) {
                        h = th;
                        w = (ow/oh) * h;
                    }

                    $item.attr('width', w)
                         .attr('height', h)
                         .attr('src', src);

                });
            },
            uploadImage: function() {
                if (fileChooserInput.val()=='') {
                    alert ('Please select an image to upload!');
                    return;
                } else if(!f.validateFileName()) {
                    alert ('Error! Image file should be one of jpg, gif or png file!');
                    return;
                }

                var uploadButton = $(this).parents('.ui-dialog').find('button');
                f.cropStatusSpan.html(' Uploading image..');
                f.changeDialogButtonStatus(true,uploadButton);
                $.ajaxFileUpload({
                    url:'../../../controller/modules/common/CommonController.php?action=upload_temp_picture&'+settings,
                    secureuri:false,
                    fileElementId: fileChooserInputId,
                    dataType: 'json',
                    success: function (data, status)
                    {
                        if(typeof(data.error) != 'undefined')
                        {
                            if(data.error != '') {
                                f.changeDialogButtonStatus(false,uploadButton);
                            } else {
                                f.cropStatusSpan.html(' Downloading image.. Please wait..');
                                var newDate = new Date();
                                f.changeCropImage(data.url+'?prevent_cache='+newDate.getTime());
                                f.changeDialogButtonStatus(false,uploadButton);
                            }
                        }
                    },
                    error: function (data, status, e)
                    {
                        f.changeDialogButtonStatus(false,uploadButton);
                        alert(data.responseText);
                    }
                });

                return false;
            },
            changeDialogButtonStatus: function(disabled, button, text) {
                if(text)
                    button.html(text);
                
                if (disabled) {
                    button.attr('disabled', true);
                    button.removeClass('ui-state-focus');
                    button.removeClass('ui-state-hover');
                    button.addClass('ui-state-disabled');
                } else {
                    button.removeClass('ui-state-disabled');
                    button.attr('disabled', false);
                }
            },
            changeCropImage: function(src) {
                if(f.crop_image_div)
                    f.crop_image_div.remove();

                f.crop_image_div = $('<div></div>').appendTo(f.bottom_div );
                f.crop_image = $('<img/>');
                f.crop_image.load(function() {
                    var ratio = opts.image_size.width/opts.image_size.height;

                    var selectorW = ratio*f.crop_image.height();
                    var selectorH = f.crop_image.height();
                    var x = (f.crop_image.width()-selectorW)/2;
                    var y=0;
                    if (selectorW > f.crop_image.width()) {
                        selectorH = (1/ratio)*f.crop_image.width();
                        selectorW = f.crop_image.width();
                        x=0;
                        y=(f.crop_image.height()-selectorH)/2;
                    }

                    f.crop_coordinates = {'x':x,'y':y,'w':selectorW,'h':selectorH};

                    if(!opts.restrict_image_size) {
                        ratio = null;
                    }

                    f.crop_image.Jcrop({
                        //onChange: showPreview,
                        onSelect: function(c){
                            f.crop_coordinates = c;
                        },
                        setSelect: [x,y,selectorW,selectorH],
                        aspectRatio: ratio
                    });
                    
                    f.image_uploaded = true;
                    f.cropStatusSpan.html(' Please start selecting area now. Use <b>Crop</b> Button at the bottom to complete.');
                });
                
                f.crop_image_div.append(f.crop_image);
                f.crop_image.attr('src',src);
            },
            cropImage: function() {
                if (!f.image_uploaded) {
                    alert("Please upload an image before pressing Crop. Press 'Browse' button above and select an image to be cropped.\n\nAfter choosing image press 'Upload' button to upload the image.");
                    return;
                }

                var data = $.extend({},f.crop_coordinates, opts);

                if(!opts.restrict_image_size) {
                    data.image_width = f.crop_coordinates.w;
                    data.image_height = f.crop_coordinates.h;
                } else {
                    data.image_width = opts.image_size.width;
                    data.image_height = opts.image_size.height;
                }
                
                data.thumb_width = opts.thumb_size.width;
                data.thumb_height = opts.thumb_size.height;
                data.callback = null;

                f.cropStatusSpan.html(' Cropping image.. Please wait..');
                var uploadButton = $(this).parents('.ui-dialog').find('button');
                f.changeDialogButtonStatus(true,uploadButton);
                $.post('../../../controller/modules/common/CommonController.php?action=crop_uploaded_image', data,
                function(data) {
                    f.changeDialogButtonStatus(false,uploadButton);

                    if (opts.callback) {
                        opts.callback(data.url, data.thumb_url, data.url_path);
                    }
                    
                    f.choose_from_com_dialog.dialog("close");
                }
                ,'json');

            },
            validateFileName: function() {
                var fileName = fileChooserInput.val();
                if (fileName.match(/((.jpg)|(.jpeg)|(.gif)|(.png)|(.JPG)|(.JPEG)|(.GIF)|(.PNG))$/)) {
                    return true;
                } else {
                    return false;
                }
            },
            changeBaseName: function(name) {
                opts.image_base_name = name;
            }
        }

        f.choose_dialog = $('<div></div>')
                            .attr('title','Choose Method')
                            .html('From where you want to choose the image?');

        f.choose_from_bank_dialog = $('<div></div>')
                            .attr('title','Choose image from bank')
                            .html('');


        /*Creating upload from com dialog*/
        f.choose_from_com_dialog = $('<div></div>')
                            .attr('title','Upload image from Your comuter')
                            

        var uploadBtn = $('<button>Upload</button>');
        uploadBtn.click(f.uploadImage);

        f.cropStatusSpan = $("<span/>").css('font-size','10px');
                       
        f.choose_from_com_dialog.append(
            $('<div class="image_chooser_top_row">Select Image:</div>')
            .append(fileChooserInput)
            .append(uploadBtn)
            .append(f.cropStatusSpan)

        );

        f.crop_image_div = null;
        f.bottom_div = $('<div class="image_chooser_bottom_row"></div>');
            
        f.choose_from_com_dialog.append(f.bottom_div );

        /*Done creating upload from com dialog*/


        f.choose_dialog.dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            minHeight: 100,
            resizable: false,
            buttons: {
                "Bank Images": f.showSelectImageFromBankDialog,
                "Your Computer": f.showSelectImageFromComDialog,
                "Cancel": function() {
                    $(this).dialog('close');
                }

            }
        });

        f.choose_from_bank_dialog.dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            height: 500,
            resizable: false,
            buttons: {
                Cancel: function() {
                    $(this).dialog('close');
                }
            }
        });

        f.choose_from_com_dialog.dialog({
            modal: true,
            autoOpen: false,
            width: 800,
            height: 600,
            minHeight: 120,
            position: top,
            resizable: false,
            buttons: {
                Cancel: function() {
                    $(this).dialog('close');
                },
                Crop: f.cropImage
            }
        });

        f.image_uploaded = false;

        item.funcs = f;

        $item.click(f.itemClicked);

        if (!$item.attr('src') || $item.attr('src') == '')
            $item.attr('src',opts.empty_image);

        $item.attr('title', opts.hint_text)
    }

    $.fn.imagechooserLoadImage = function(src) {
        return this.each(function() {
            this.funcs.loadImage(src);
        });
    }

    $.fn.imagechooserLoadImage = function(src) {
        return this.each(function() {
            this.funcs.loadImage(src);
        });
    }

    $.fn.imagechooserChangeBaseName = function(name) {
        return this.each(function() {
            this.funcs.changeBaseName(name);
        });
    }
    //
    // private function for debugging
    //
    function debug($obj) {
        if (window.console && window.console.log)
            window.console.log($obj);
    };
    
    //
    // plugin defaults
    //
    $.fn.imagechooser.defaults = {
        image_size: {width: 250, height: 300},
        container_size: {width: 200, height: 300},
        create_thumb: false,
        thumb_size: {width: 30, height: 60},
        image_catagory_name: 'image_plugin',
        thumb_catagory_name: 'image_plugin_thumbs',
        image_base_name:'1',
        callback: null, // function(image, thumb) {}
        loading_image: {'src':'../../../images/bigrotation2.gif','width':32,'height':32},
        method: 'ask', //bank or upload
        empty_image: '../../../images/icon_blank.png',
        hint_text: 'Click here to edit',
        dialog_load_text: "<img src='../../../images/bigrotation2.gif' height=32 width=32/> <br/>Loading...",
        crop_stage_width: 740,
        crop_stage_height: 480,
        restrict_image_size: true,
        output_image_type: 'jpg'
    };

// end of closure
//
})(jQuery);