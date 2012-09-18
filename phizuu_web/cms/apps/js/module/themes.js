function preConfigureInterfaceForPackage(packageName) {
    if(packageName == "phizuu_pro") {
        $(".iphoneTabBar").hide();
        $(".iphoneTwitterPopupContainer").hide();
        $(".iphoneBody").css('height', '416px');
        $(".iphoneHomeImage").attr('src', '../../../images/themes/home_image_pro.jpg');
    }
}

function changeMainTheme(themeName) {
    var theme = themes.mainThemes[themeName];

    loadImages(theme.images);
    loadIconSets(theme.icon_sets);
    loadColors(theme.colors);

    loadAnimations();
    hideCustomOption('main');
    hideCustomOption('image');
    hideCustomOption('color');
    currentTheme.mainTheme = themeName;
    $('.themeSelector_main').html(themeName);
}

function loadSavedTheme() {
    if(currentTheme.mainTheme != 'Custom') {
        changeMainTheme(currentTheme.mainTheme);
    } else {
        if (currentTheme.imageSet.name != 'Custom') {
            loadImages (currentTheme.imageSet.name);
        } else {
            loadImages (null,currentTheme.imageSet.images);
        }

        if (currentTheme.colorSet.name != 'Custom') {
            loadColors (currentTheme.colorSet.name);
        } else {
            loadColors (null,currentTheme.colorSet.colors);
        }

        loadIconSets(currentTheme.iconSet);
    }
}

function loadIconSets (iconSetObj) {
    for (iconSetType in iconSetObj) {
        loadIconSet(iconSetObj[iconSetType],iconSetType);
        $('#'+iconSetType+'_Icon_Set').val(iconSetObj[iconSetType]);
    }
}

function loadIconSet(iconSet, type) {
    showCustomOption('main');
    currentTheme.iconSet[type] = iconSet;

    $('.themeSelector_icons_'+type).html(iconSet);

    var icons = themes.iconSets[type][iconSet].images;

    //window.console.log(icons);
    for (icon in icons) {
        setImageURL(icons[icon].url, '.iphoneIcon_'+icon);
    }
}

function loadImages(imageSetName, imageSetObj) {
    var imageSet = null;
    
    if (imageSetObj) {
        imageSet = imageSetObj;
        
        showCustomOption('images');
        currentTheme.imageSet['name'] = 'Custom';
    } else {
        imageSet = themes.imageSets[imageSetName].images;
        
        hideCustomOption('image');
        currentTheme.imageSet['name'] = imageSetName;
        $('.themeSelector_images').html(imageSetName);
    }

    showCustomOption('main');

    for (imageName in imageSet) {
        loadImage(imageSet[imageName], imageName);
    }
}

function loadImage(imageObj, imageName, refresh) {
    var newDate= new Date;
    var src = imageObj.url;

    if(refresh) {
        src = src+'?prevent_cache='+newDate.getTime();
    }
    
    if (imageName == 'tab-bar-background_png') {
        setImageURL(src, '.iphoneTabBarImage');
    } else if (imageName == 'navigationbar_png') {
        setImageURL(src, '.iphoneNavigationBarImage');
    } else if (imageName == 'tool-bar_png') {
        setImageURL(src, '.iphoneToolBarImage');
    }

    if(!refresh) {
        src = src+'?prevent_cache='+newDate.getTime();
    }
    
    $('.ImagePreview_' + imageName).attr('src',src);

    currentTheme.imageSet['images'][imageName] = imageObj;
}

function showCustomOption(type) {
    //$('.customOption_'+type).show();
    //if(!$('#'+type+'_SetSelector customOption').length) {
        //$('#'+type+'_SetSelector').append('<li title="Custom" class="customOption">Custom</li>');
    //}
    
    $('.themeSelector_'+type).html('Custom');

    if (type=='main') {
        currentTheme.mainTheme = 'Custom';
    }
}

function hideCustomOption(type) {
    $('.customOption_'+type).hide();
}

function loadColors(colorSetName, colorSetObj) {
    var colorSet = null;

    if (colorSetObj) {
        colorSet = colorSetObj;
        showCustomOption('colors');
    } else {
        colorSet = themes.colorSets[colorSetName];
        currentTheme.colorSet['name'] = colorSetName;
        hideCustomOption('color');
        $('.themeSelector_colors').html(colorSetName);
    }

    showCustomOption('main');

    for (color in colorSet) {
        loadColor(color,colorSet[color]);
    }   
}

function loadColor(colorName, color) {
    if(colorName == 'name')
        return;
    
    if (colorName == 'table_seperator_color') {
        $('.iphoneTableItem').css('border-bottom','1px solid #'+color);
    } else if (colorName == 'table_text_color') {
        $('.iphoneTableItem').css('color','#'+color);
        $('.iphoneEventsTabBody').css('color','#'+color);
    } else if (colorName == 'navbar_tint_color') {
        for(var i=1;i<=5;i++){
            $('.iphoneNavRoundedRec').find('.spiffy'+i)
            .css('border-right','#'+color)
            .css('border-left','1px solid #'+color)
            .css('background','#'+color);
        }

        $('.iphoneNavRoundedRec').find('.spiffyfg').css('background','#'+color);
    } else if (colorName == 'banner_text_color') {
        $('.iphoneBodyEventsTopText').css('color','#'+color);
    } else if (colorName == 'foreground_color') {
        $('.iphoneAboutPane').css('color','#'+color);
    } else if (colorName == 'tab_text_color') {
        $('.iphoneEventTabText').css('color','#'+color);
    } else if (colorName == 'mini_player_foreground_color') {
        $('.iphoneMiniPlayerContainer').css('color','#'+color);
        $('.iphoneMiniPlayerDetailsBg').css('background-color','#'+color);
    } else {
        $('.iphoneColor_'+colorName).css('background-color','#'+color);
    }

    $('.ColorChooser_'+ colorName).ColorPickerSetColor(color);
    $('.ColorChooser_'+ colorName).css('backgroundColor', '#' + color);
    currentTheme.colorSet['colors'][colorName] = color;
}

function loadAnimations() {
    if (currentTheme.pacakgeName == defaultPackageName) {
        $('.iphoneTwitterPopup').animate({
            top: 70,
            opacity:0
        }, 200, function() {
            $('.iphoneTwitterPopup').animate({
                top: 0,
                opacity:0.8
            }, 500, function() {
            // Animation complete.
            });
        });
    }

    $('.iphoneMiniPlayerContainer').animate({
        left: -320
    }, 500, function() {
        $('.iphoneMiniPlayerContainer').animate({
            left: -320
        }, 1000, function() {
            $('.iphoneMiniPlayerContainer').animate({
                left: 0
            }, 500, function() {
                // Animation complete.
            });
        });
    });
}

function setImageURL(src,selector) {
    $(selector).attr('src',src)
}

function createNavigationButton(container) {
    $('<div>').addClass("");
}


function createColorPicker (colorName) {
    var selector = '.ColorChooser_'+colorName;
    $(selector).ColorPicker({
        color: '#0000ff',
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            return false;
        },
        onChange: function (hsb, hex, rgb) {
            loadColor(colorName, hex);
            showCustomOption('colors');
            showCustomOption('main');
            currentTheme.colorSet['name'] = 'Custom';
        }
    });
}

function createImageChooser(imageName, width, height, fileName) {
    $('.ImageChooser_' + imageName).imagechooser({
        method:'upload',
        callback: function(image, thumb, url_path) {
            currentTheme.imageSet['name'] = 'Custom';
            showCustomOption('images');
            showCustomOption('main');
            var imageObj = {'url':image, 'path':url_path, 'name':fileName};
            loadImage(imageObj, imageName, true);
        },
        image_size:{width:width*2, height: height*2},
        create_thumb: false,
        image_catagory_name: 'theme_images',
        image_base_name: imageName,
        hint_text: 'Click here to change',
        output_image_type: 'png'
    });
}

function saveChanges() {
    $('.savingStatusHeader').html('Saving theme..');
    var data = JSON.stringify(currentTheme);

    $.post('../../../controller/modules/themes/ThemeController.php?action=save_theme', {'theme_data':data},
        function(data) {
            $('.savingStatusHeader').html('Theme saved on '+myLastSaveTime()+" - " + '<a href="#" onclick="return saveChanges()">[Save Now]</a>');
        }
    );

    return false;
}

function prepareSelector(type) {
    var setSel = '#'+type+'_SetSelector';

    $(setSel).jcarousel({
        scroll: 2,
        wrap: 'both'
    });

    $('.themeSelector_' + type).click(function(event) {
        $('.setSelector').hide();
        $(setSel + ' li img').removeClass('selectedItem').addClass('unselectedItem');
        $(setSel + ' li[title|="'+$('.themeSelector_'+type).html()+'"] img' )
        .removeClass('unselectedItem')
        .addClass('selectedItem');
        
        $('.selectorContainer_'+type).show(200);
        var offset = $('.themeSelector_'+type).offset();

        $('.selectorContainer_'+type).css('left',offset.left-150)
                                     .css('top',offset.top+15);

        event.stopPropagation();
    });

    $('.setSelector').click(function(event) {event.stopPropagation()});

    $(setSel+' li').click(function(event) {
        var value = $(this).attr('title');
        switch (type) {
            case 'main':
                changeMainTheme(value);
                break;
            case 'images':
                loadImages(value);
                break;
            case 'icons_tab_bar':
                loadIconSet(value,'tab_bar');
                break;
            case 'icons_general':
                loadIconSet(value,'general');
                break;
            case 'icons_grid_images':
                loadIconSet(value,'grid_images');
                break;
            case 'icons_music':
                loadIconSet(value,'music');
                break;
            case 'icons_middle_tabs':
                loadIconSet(value,'middle_tabs');
                break;
            case 'colors':
                loadColors(value);
                break;
        }

        $('.themeSelector_'+type).html(value)
        $('.selectorContainer_'+type).hide(200);
    });
}

$(document).ready(function() {
    createColorPicker('foreground_color');
    createColorPicker('background_color');
    createColorPicker('table_cell_odd_color');
    createColorPicker('table_cell_even_color');
    createColorPicker('table_seperator_color');
    createColorPicker('table_background_color');
    createColorPicker('table_text_color');
    createColorPicker('banner_background_color');
    createColorPicker('banner_text_color');
    createColorPicker('tab_text_color');
    createColorPicker('mini_player_foreground_color');
    createColorPicker('popup_background_color');
    createColorPicker('navbar_tint_color');

    createImageChooser('navigationbar_png',320,44,'navigationbar.png');
    createImageChooser('tab-bar-background_png',320,49,'tab-bar-background.png');
    createImageChooser('tool-bar_png',320,44,'tool-bar.png')

    window.setInterval ( "saveChanges()", 30000);


    $(document).click(function() {
        $('.setSelector').hide(200);
    });

    prepareSelector('main');
    prepareSelector('images');
    prepareSelector('icons_tab_bar');
    prepareSelector('icons_general');
    prepareSelector('icons_music');
    prepareSelector('icons_middle_tabs');
    prepareSelector('colors');
    prepareSelector('icons_grid_images');
    
    $('.setSelector').hide();
});


var debug123 = function(obj) {
    if(window.console) {
        window.console.log(obj);
    }
}

function myLastSaveTime() {
    var dd = new Date();
    var hh = dd.getHours();
    var mm = dd.getMinutes();
    var ss = dd.getSeconds();
    return hh + ":" + mm + ":" + ss;
}

function finishTheme() {
    $('#saveThemeSection').html('Processing Theme..');
    $.post('../../../controller/modules/themes/ThemeController.php?action=finish_theme', {'theme':JSON.stringify(currentTheme)},
        function(data) {
            alert(data);
            $('#saveThemeSection').html('<a href="../../../controller/modules/themes/ThemeController.php?action=download_theme&user_id=7">Download Theme</a>');
        }
    );

    return false;
}



