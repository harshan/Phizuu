<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/iphone_themes.css" rel="stylesheet" type="text/css" />
        
        <link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/color_picker/css/colorpicker.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="../../../css/jcarousel/skins/tango/skin.css" />

        <script type="text/JavaScript">
            var themes = eval((<?php echo json_encode($theme) ?>));
            var defaultPackageName = '<?php echo $defaultThemePackage; ?>';
            <?php
            if ($loadedTheme !== FALSE) {
                ?>
                var currentTheme = eval((<?php echo $loadedTheme ?>));
                var themeLoaded = true;
                <?php
            } else { ?>
                var currentTheme = new Object;
                currentTheme.basePackage = '<?php echo $themePackage; ?>';
                currentTheme.imageSet = new Object;
                currentTheme.imageSet['images'] = new Object;
                currentTheme.colorSet = new Object;
                currentTheme.colorSet['colors'] = new Object;
                currentTheme.iconSet = new Object;
                var themeLoaded = false;
            <?php
            }
            ?>

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
        <script type="text/javascript" src="../../../js/module/themes.js"></script>
        <script type="text/javascript" src="../../../js/colorpicker.js"></script>
        <script type="text/javascript" src="../../../common/jquery.Jcrop.min.js"></script>
        <script type="text/javascript" src="../../../common/jquery.imagechooser.js"></script>
        <script type="text/javascript" src="../../../common/json2.js"></script>
        <script type="text/javascript" src="../../../js/jcarousel/lib/jquery.jcarousel.min.js"></script>


        
        <script type="text/javascript">
            debug123(currentTheme);
            $(document).ready(function() {
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
                    save_2x: true,
                    create_thumb: false,
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

                $('.phoneTwitterPopup').css('opacity',0.8);
                $('.iphoneMiniPlayerContainer').css('opacity',0.8);

                preConfigureInterfaceForPackage(currentTheme.basePackage);

                if (themeLoaded) {
                    loadSavedTheme();
                } else {
                    changeMainTheme('Default');
                    $('.themeSelector_main').val('Default');
                }
            });

            var appId = <?php echo $userInfo['app_id'] ?>;

        </script>
        <style type="text/css">


        </style>

    </head>

    <body>
        <div id="mainWideDiv">
            <div id="middleDiv2" style="width: 973px">
                <div id="header">
                    <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logo.png" width="334" height="62" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="120" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
                </div>
                <div id="body">
                    <br/>
                    <div class="wizardTitle" >
                        <div class="left"><img src="../../../images/wizTitleLeft.png" width="10" height="34"/></div>
                        <div class="middle" style="width: 903px">You can select pre created themes or create one on your own</div>
                        <div class="right"><img src="../../../images/wizTitleRight.png" width="10" height="34"/></div>
                    </div>
                    <div class="savingStatusHeader">
                        <a href="#" onclick="return saveChanges()">[Save Now]</a>
                    </div>
                    <div class="themeSection">
                        <div class="configurationSection">
                            <div class="configurationSectionRow">
                                <div class="presetCaption">Main Theme:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_main"></div>
                                </div>
                            </div>
                            <div class="configurationSectionRow" >
                                <div class="presetCaption">Image set:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_images"></div>
                                </div>
                            </div>
                            <div class="configurationSectionRow" >
                                <div class="presetCaption">Tab bar icon set:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_icons_tab_bar"></div>
                                </div>
                            </div>
                            <div class="configurationSectionRow" >
                                <div class="presetCaption">General icon set:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_icons_general"></div>
                                </div>
                            </div>
                            <div class="configurationSectionRow" >
                                <div class="presetCaption">Miniplayer icon set:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_icons_music"></div>
                                </div>
                            </div>
                            <div class="configurationSectionRow" >
                                <div class="presetCaption">Color set:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_colors"></div>
                                </div>
                            </div>
                            <div class="configurationSectionRow" >
                                <div class="presetCaption">Grid Background:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_icons_grid_images"></div>
                                </div>
                            </div>
                            
                            <div class="configurationSectionRow">
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="imageCaption">Navigation Bar Image: </div>
                                <div class="imageSelector ImageChooser_navigationbar_png">
                                    <div class="imageSelectorCover"></div>
                                    <img class="imageSelectorImage ImagePreview_navigationbar_png"/>
                                </div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="imageCaption">Tab Bar Image: </div>
                                <div class="imageSelector ImageChooser_tab-bar-background_png">
                                    <div class="imageSelectorCover"></div>
                                    <img class="imageSelectorImage ImagePreview_tab-bar-background_png"/>
                                </div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="imageCaption">Tool Bar Image: </div>
                                <div class="imageSelector ImageChooser_tool-bar_png">
                                    <div class="imageSelectorCover"></div>
                                    <img class="imageSelectorImage ImagePreview_tool-bar_png"/>
                                </div>
                            </div>
                            
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Table cell odd color: </div>
                                <div class="colorSelector ColorChooser_table_cell_odd_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Table cell even color: </div>
                                <div class="colorSelector ColorChooser_table_cell_even_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Table cell seperator color: </div>
                                <div class="colorSelector ColorChooser_table_seperator_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Table view background color: </div>
                                <div class="colorSelector ColorChooser_table_background_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Table view text color:</div>
                                <div class="colorSelector ColorChooser_table_text_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Miniplayer text color: </div>
                                <div class="colorSelector ColorChooser_mini_player_foreground_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Popup background color: </div>
                                <div class="colorSelector ColorChooser_popup_background_color"></div>
                            </div>
                            
                        </div>
                        <div class="previewSection">
                            <!-- Home Screen -->

                            <div class="iphoneScreenContainer">
                                <div class="iphoneScreen iphoneColor_background_color">
                                    <div class="iphoneBatteryBar"></div>
                                    <div class="iphoneNavigationBar">
                                        <img class="iphoneNavigationBarImage" src=""/>
                                        <img class="iphoneNavigationBarTitle" src="../../../images/themes/navigation-title.png"/>
                                    </div>
                                    <div class="iphoneMiniPlayerHider">
                                        <div class="iphoneMiniPlayerContainer">
                                            <img class="iphoneIcon_miniplayer_bg_png"/>
                                            <img class="iphoneMiniPlayerRewind iphoneIcon_rewind_png"/>
                                            <img class="iphoneMiniPlayerPlay iphoneIcon_play_png"/>
                                            <img class="iphoneMiniPlayerForward iphoneIcon_forward_png"/>
                                            <img src="../../../images/themes/miniplayer_progress_shading.png" class="iphoneMiniPlayerDetails"/>
                                            <div class="iphoneMiniPlayerSongTitle">Ain't No Sunshine - Bill...</div>
                                            <div class="iphoneMiniPlayerSongDuration">0:00:00</div>
                                            <img class="iphoneMiniPlayerCloseButton iphoneIcon_close_png"/>
                                            <div class="iphoneMiniPlayerDetailsBg"></div>
                                        </div>
                                    </div>
                                    <div class="iphoneBody">
                                        <img class="iphoneHomeImage" src="../../../images/themes/home_image.jpg"/>
                                    </div>
                                    <div class="iphoneTwitterPopupContainer">
                                        <div class="iphoneTwitterPopup iphoneColor_popup_background_color">
                                            <img src="../../../images/themes/twitter_post.png"/>
                                        </div>
                                    </div>
                                    <div class="iphoneTabBar">
                                        <img class="iphoneTabBarImage" src=""/>
                                        <div class="iphontTabBarCover"><img src="../../../images/themes/tab_bar.png"/></div>
                                        <div class="iphontTabBarTabContainer">
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_home-tab_png"/>
                                                <div class="iphoneTabBarText">Home</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_music-tab_png"/>
                                                <div class="iphoneTabBarText">Music</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_photos-tab_png"/>
                                                <div class="iphoneTabBarText">Album</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_events-tab_png"/>
                                                <div class="iphoneTabBarText">Events</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon" src="../../../images/themes/more-icon.png"/>
                                                <div class="iphoneTabBarText">More</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="iphoneGridMenu">
                                        <div></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table View -->
                            <div class="iphoneScreenContainer">
                                <div class="iphoneScreen iphoneColor_background_color">
                                    <div class="iphoneBatteryBar"></div>
                                    <div class="iphoneNavigationBar">
                                        <img class="iphoneNavigationBarImage" src=""/>
                                        <img class="iphoneNavigationBarTitle" src="../../../images/themes/navigation-title.png"/>
                                        <div class="iphoneNavigationButton">
                                            <img class="iphoneNavButImage" src="../../../images/themes/nav_but.png"/>
                                            <div class="iphoneNavRoundedRec">
                                              <b class="spiffy">
                                              <b class="spiffy1"><b></b></b>
                                              <b class="spiffy2"><b></b></b>
                                              <b class="spiffy3"></b>
                                              <b class="spiffy4"></b>
                                              <b class="spiffy5"></b></b>

                                              <div class="spiffyfg">
                                                <!-- content goes here -->
                                              </div>

                                              <b class="spiffy">
                                              <b class="spiffy5"></b>
                                              <b class="spiffy4"></b>
                                              <b class="spiffy3"></b>
                                              <b class="spiffy2"><b></b></b>
                                              <b class="spiffy1"><b></b></b></b>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="iphoneBody iphoneColor_table_background_color">
                                        <?php
                                        $itemCount = 5;
                                        $moduleArr = array("Fan Wall","About","Bio","Facebook","Twitter","New release - Buy This","Cool release");

                                        for ($i=0; $i<$itemCount; $i++) {
                                            if($i%2==0){
                                                $class = 'iphoneColor_table_cell_odd_color';
                                            } else {
                                                $class = 'iphoneColor_table_cell_even_color';
                                            }
                                        
                                        ?>

                                        <div class="iphoneTableItem <?php echo $class ?>">
                                            <div class="iphoneTableText"><?php echo $moduleArr[$i]; ?></div>
                                            <div class="iphoneTableTextChevron">&gt;</div>
                                        </div>

                                        <?php
                                        }
                                        ?>
                                        <div class="iphoneTableItem iphoneColor_table_cell_even_color">
                                            <div class="iphoneTableText iphoneTableBuyText"><?php echo $moduleArr[5]; ?></div>
                                            <div class="iphoneTableImageChevron">
                                                <img class="iphoneIcon_itunes-download-icon_png"/>
                                            </div>
                                        </div>
                                        <div class="iphoneTableItem iphoneColor_table_cell_odd_color">
                                            <div class="iphoneTableText iphoneTableBuyText"><?php echo $moduleArr[6]; ?></div>
                                            <div class="iphoneTableImageChevron">
                                                <img class="iphoneIcon_itunes-download-icon_png"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="iphoneTabBar">
                                        <img class="iphoneTabBarImage" src=""/>
                                        <div class="iphontTabBarCover"><img src="../../../images/themes/tab_bar.png"/></div>
                                        <div class="iphontTabBarTabContainer">
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_home-tab_png"/>
                                                <div class="iphoneTabBarText">Home</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_music-tab_png"/>
                                                <div class="iphoneTabBarText">Music</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_photos-tab_png"/>
                                                <div class="iphoneTabBarText">Album</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon iphoneIcon_events-tab_png"/>
                                                <div class="iphoneTabBarText">Events</div>
                                            </div>
                                            <div class="iphoneTab">
                                                <img class="iphoneTabBarIcon" src="../../../images/themes/more-icon.png"/>
                                                <div class="iphoneTabBarText">More</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="themeSection">
                        <div class="configurationSection">
                            <div class="configurationSectionRow" >
                                <div class="presetCaption">Sub tab bar icon set:</div>
                                <div class="presetSelector">
                                    <div class="themeSelector_icons_middle_tabs"></div>
                                </div>
                            </div>
                            <div class="configurationSectionRow">
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Sub tabs text color: </div>
                                <div class="colorSelector ColorChooser_tab_text_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Navigation bar button tint color: </div>
                                <div class="colorSelector ColorChooser_navbar_tint_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Top banner background color: </div>
                                <div class="colorSelector ColorChooser_banner_background_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">Top banner text color: </div>
                                <div class="colorSelector ColorChooser_banner_text_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">General background color: </div>
                                <div class="colorSelector ColorChooser_background_color"></div>
                            </div>
                            <div class="configurationSectionColorsRow">
                                <div class="colorCaption">General text color: </div>
                                <div class="colorSelector ColorChooser_foreground_color"></div>
                            </div>
                        </div>

                        <div class="previewSection">
                            <!-- Events screen:  -->

                            <div class="iphoneScreenContainer">
                                <div class="iphoneScreen iphoneColor_background_color">
                                    <div class="iphoneBatteryBar"></div>
                                    <div class="iphoneNavigationBar">
                                        <img class="iphoneNavigationBarImage" src=""/>
                                        <img class="iphoneNavigationBarTitle" src="../../../images/themes/navigation-title.png"/>
                                    </div>
                                    <div class="iphoneBodyEvents">
                                        <div class="iphoneBodyEventsTop iphoneColor_banner_background_color">
                                            <div class="iphoneBodyEventsTopImage">
                                                <img src="../../../images/themes/event_thumb.jpg"/>
                                            </div>
                                            <div class="iphoneBodyEventsTopText">
                                                <div style="margin: 6px 10px; font-size: 16px">December 07, 2011</div>
                                                <div style="margin: 6px 10px; font-size: 14px; font-weight: bold">Silicon Valley, Ca</div>
                                                <div style="margin: 6px 10px; font-size: 12px">phizuu LLC</div>
                                            </div>
                                            
                                        </div>
                                        <div class="iphoneEventTabBar">
                                            <div class="iphoneEventTab">
                                                <img class="iphoneEventImage iphoneIcon_sub_tab_inactive_png"/>
                                                <img class="iphoneEventTabIcon iphoneIcon_book_png"/>
                                                <div class="iphoneEventTabText">Details</div>
                                            </div>
                                            <div class="iphoneEventTab">
                                                <img class="iphoneEventImage iphoneIcon_sub_tab_active_png"/>
                                                <div class="iphoneEventTabBg iphoneColor_table_cell_odd_color"></div>
                                                <img class="iphoneEventTabIcon iphoneIcon_ilike_png"/>
                                                <div class="iphoneEventTabText">I Like</div>
                                            </div>
                                            <div class="iphoneEventTab">
                                                <img class="iphoneEventImage iphoneIcon_sub_tab_inactive_png"/>
                                                <img class="iphoneEventTabIcon iphoneIcon_buy_png"/>
                                                <div class="iphoneEventTabText">Buy</div>
                                            </div>
                                            <div class="iphoneEventTab">
                                                <img class="iphoneEventImage iphoneIcon_sub_tab_inactive_png"/>
                                                <img class="iphoneEventTabIcon iphoneIcon_comment_png"/>
                                                <div class="iphoneEventTabText">Comments</div>
                                            </div>
                                        </div>
                                        <div class="iphoneEventsTabBody iphoneColor_table_cell_odd_color">
                                            <div style="margin: 5px;">
                                            phizuu:connect is a mobile application platform targeted towards the music industry. Its main focus is to give musicians the opportunity to make an intimate connection with their fans around the globe. phizuu:connect even has an online content management system which allows the artist to update most of their application content "on the fly." Check out the phizuu:connect Tour page to get a better idea about what it can offer you.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="iphoneToolBarContainer ">
                                        <img class="iphoneToolBarImage"/>
                                        <div class="iphoneNavigationButton iphoneToolbarButton">
                                            <img class="iphoneNavButImage" src="../../../images/themes/share_button.png"/>
                                            <div class="iphoneNavRoundedRec">
                                              <b class="spiffy">
                                              <b class="spiffy1"><b></b></b>
                                              <b class="spiffy2"><b></b></b>
                                              <b class="spiffy3"></b>
                                              <b class="spiffy4"></b>
                                              <b class="spiffy5"></b></b>

                                              <div class="spiffyfg">
                                                <!-- content goes here -->
                                              </div>

                                              <b class="spiffy">
                                              <b class="spiffy5"></b>
                                              <b class="spiffy4"></b>
                                              <b class="spiffy3"></b>
                                              <b class="spiffy2"><b></b></b>
                                              <b class="spiffy1"><b></b></b></b>
                                            </div>
                                        </div>
                                        <img class="iphoneToolbarShareButton" src="../../../images/themes/share_icon.png"/>
                                    </div>
                                </div>
                            </div>

                            <!-- About screen:  -->

                            <div class="iphoneScreenContainer">
                                <div class="iphoneScreen iphoneColor_background_color">
                                    <div class="iphoneBatteryBar"></div>
                                    <div class="iphoneNavigationBar">
                                        <img class="iphoneNavigationBarImage" src=""/>
                                        <img class="iphoneNavigationBarTitle" src="../../../images/themes/navigation-title.png"/>
                                        <div class="iphoneNavigationButton">
                                            <img class="iphoneNavButImage" src="../../../images/themes/nav_but.png"/>
                                            <div class="iphoneNavRoundedRec">
                                              <b class="spiffy">
                                              <b class="spiffy1"><b></b></b>
                                              <b class="spiffy2"><b></b></b>
                                              <b class="spiffy3"></b>
                                              <b class="spiffy4"></b>
                                              <b class="spiffy5"></b></b>

                                              <div class="spiffyfg">
                                                <!-- content goes here -->
                                              </div>

                                              <b class="spiffy">
                                              <b class="spiffy5"></b>
                                              <b class="spiffy4"></b>
                                              <b class="spiffy3"></b>
                                              <b class="spiffy2"><b></b></b>
                                              <b class="spiffy1"><b></b></b></b>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="iphoneBodyEvents iphoneColor_background_color iphoneAboutPane">
                                        <div style="margin: 5px;">
                                            Phizuu is an application development company for the mobile space. We specialize in development for the iPhone / iTouch, but the road doesn't end there. We can also help you get your application onto the Blackberry and Android platforms.
                                            <br/>
                                            <br/>
                                            phizuu:connect is a mobile application platform targeted towards the music industry. Its main focus is to give musicians the opportunity to make an intimate connection with their fans around the globe. phizuu:connect even has an online content management system which allows the artist to update most of their application content "on the fly." Check out the phizuu:connect Tour page to get a better idea about what it can offer you.
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                        </div>

                    </div>
                    <div id="saveThemeSection">
                        <a href="#" onclick="return finishTheme()">Finish Theme</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="footerMain">
            <div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>




        <div id="cover"></div>
        <div id="cover2"></div>
        <div id="noContent" title="Error!" style="text-align:center">
            <p>To use the Links module you need to add at least one link. If you don't need this module please click skip this module button.</p>
        </div>
        <div id="skipWarning" title="Warning!" style="text-align:center">
            <p>If you skip this module you cannot enter Links into your app at a later time. Your application will be submitted to Apple without a Links Module. Please acknowledge by pressing Accept or press Cancel to add Links.</p>
        </div>

        <div class="setSelector selectorContainer_main">
            <ul  id="main_SetSelector" class="jcarousel-skin-tango" >
                <?php foreach ($mainThemes as $mainTheme) { ?>
                <li title="<?php echo $mainTheme['name'] ?>" >
                    <img src="<?php echo $mainTheme['preview'] ?>" width="67" height="100"/>
                </li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="setSelector selectorContainer_images">
            <ul  id="images_SetSelector" class="jcarousel-skin-tango" >
                <?php foreach ($imageSets as $imageSet) { ?>
                <li title="<?php echo $imageSet['name'] ?>" >
                    <img src="<?php echo $imageSet['preview'] ?>" width="67" height="100"/>
                </li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="setSelector selectorContainer_icons_tab_bar">
            <ul  id="icons_tab_bar_SetSelector" class="jcarousel-skin-tango" >
                <?php
                $iconSetsTab = $iconSets['tab_bar'];
                foreach ($iconSetsTab as $iconSet) { ?>

                <li title="<?php echo $iconSet['name'] ?>" >
                    <img src="<?php echo $iconSet['preview'] ?>" width="67" height="100"/>
                </li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="setSelector selectorContainer_icons_general">
            <ul  id="icons_general_SetSelector" class="jcarousel-skin-tango" >
                <?php
                $iconSetTabs = $iconSets['general'];
                foreach ($iconSetTabs as $iconSet) { ?>

                <li title="<?php echo $iconSet['name'] ?>" >
                    <img src="<?php echo $iconSet['preview'] ?>" width="67" height="100"/>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div class="setSelector selectorContainer_icons_grid_images">
            <ul id="icons_grid_images_SetSelector" class="jcarousel-skin-tango" >
                <?php
                $iconSetGrid = $iconSets['grid_images'];
                foreach ($iconSetGrid as $iconSet) { ?>
                <li title="<?php echo $iconSet['name'] ?>" >
                    <img src="<?php echo $iconSet['preview'] ?>" width="67" height="100"/>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div class="setSelector selectorContainer_icons_music">
            <ul  id="icons_music_SetSelector" class="jcarousel-skin-tango" >
                <?php
                $iconSetMusics = $iconSets['music'];
                foreach ($iconSetMusics as $iconSet) { ?>

                <li title="<?php echo $iconSet['name'] ?>" >
                    <img src="<?php echo $iconSet['preview'] ?>" width="67" height="100"/>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div class="setSelector selectorContainer_colors">
            <ul  id="colors_SetSelector" class="jcarousel-skin-tango" >
                <?php foreach ($colorSets as $colorSet) { ?>
                <li title="<?php echo $colorSet['name'] ?>" >
                    <?php
                    $cnt = 0;
                    foreach ($colorSet as $key=>$color) {
                        
                        if($key!='name' && $cnt<10) {?>
                    <div class="colorPreviewDiv" style="background-color: <?php echo '#'.$color ?>"></div>
                    <?php 
                    $cnt++;
                    } } ?>
                </li>
                <?php } ?>
            </ul>
        </div>
        
        <div class="setSelector selectorContainer_icons_middle_tabs">
            <ul  id="icons_middle_tabs_SetSelector" class="jcarousel-skin-tango" >
                <?php
                $iconSetMusics = $iconSets['middle_tabs'];
                foreach ($iconSetMusics as $iconSet) { ?>

                <li title="<?php echo $iconSet['name'] ?>" >
                    <img src="<?php echo $iconSet['preview'] ?>" width="67" height="100"/>
                </li>
                <?php } ?>
            </ul>
        </div>
    </body>
</html>