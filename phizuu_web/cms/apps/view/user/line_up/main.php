<?php
$menu_item = "line_up";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Line Up - phizuu CMS</title>

        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
	<link href="../../../view/user/line_up/styles_main.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../common/jquery.Jcrop.min.js"></script>
        <script type="text/javascript" src="../../../common/jquery.imagechooser.js"></script>
        <script type="text/JavaScript" src="../../../js/line_up.js"></script>
	<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
        
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
                <div id="bodyLineUp">

                    <div id="lineUpLeft">
			<div id="list_1_container" class="list_container">
			    <div id="lightBlueHeader">
				
				<div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Artists</div>
				
			    </div>
			    <div class="lineUpLine tahoma_12_blue" >
				<div style="float: left;height: 33px;padding-top: 5px;">Search: </div>
				<input id="txtSearchArtistName" style="width: 166px; border: 1px solid #CCCCCC; border-radius: 5px 5px 5px 5px; float: left; height: 23px; margin-left: 5px; margin-right: 5px;"/>
				<img src="../../../images/btnSearch.png"  style="float: left; height: 33px;width: 117px; padding-right: 5px; cursor: pointer" onclick ="javascript: artistsController.showArtistsList();" id="btnSearch"/>
				<img src="../../../images/addArtistButton.png" style="float: left; height: 33px;width: 117px;cursor: pointer" onclick ="javascript: artistsController.showCreateArtistDialog();"/>
			    </div>

			    <div id="waitingSearchWheel" style="float: left; width:464px; height: 0px; display: none;  text-align: center"><img style="margin-top: 15px" src="../../../images/bigrotation2.gif"/></div>
			    <ul  id="list_1" class="tahoma_12_blue">
			    </ul>

			    <div id="photoHolderBox" style="height: 15px"></div>
			    <div class="row_box tahoma_12_blue" style="text-align: right">
			      <div id="totalDiv" style="float:left; font-weight: bold"></div>
			      <img id="imgNext" align="top" src="../../../images/btn_next.png" title="Next Page" style="float: right; height: 33px;cursor: pointer" onclick="javascript: artistsController.goNextPage()"/>
			      <img id="imgPrev" align="top" src="../../../images/btn_prev.png" title="Previous Page" style="float: right; height: 33px; padding-right: 5px; cursor: pointer" onclick="javascript: artistsController.goPreviousPage()"/>
			    </div>
			</div>
			<div id="list_3_container" class="list_container">
			    <div id="lightBlueHeader">
				
				<div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 439px">All Stages</div>
				
			    </div>
			    <div class="lineUpLine tahoma_12_blue" >
				<img src="../../../images/festival_add_stage.png" style="float: left; height: 33px;width: 104px;cursor: pointer" onclick ="javascript: stagesController.showCreateStageDialog();"/>
				<div style="float: left;height: 33px;padding-top: 5px; padding-left: 5px;">All the stages in the festival</div>
			    </div>

			    <div id="waitingSearchWheelStages" style="float: left; width:464px; height: 0px; display: none;  text-align: center"><img style="margin-top: 15px" src="../../../images/bigrotation2.gif"/></div>
			    <ul  id="list_3" class="tahoma_12_blue">
			    </ul>
			</div>
                    </div>

                    <div id="lineUpRight">
			<div id="list_2_container" class="list_container">
			    <div id="lightBlueHeader">
				
				<div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 439px">Festival Days</div>
				
			    </div>
			    <div class="lineUpLine tahoma_12_blue" >
				<img src="../../../images/addDayButton.png" style="float: left; height: 33px;cursor: pointer" onclick ="javascript: festivalDaysController.showCreateFestivalDayDialog();"/>
				<div style="float: left;height: 33px;padding-top: 5px; padding-left: 5px;">Click on a day to add stages</div>
			    </div>

			    <div id="festivalDayswaitingWheel" style="float: left; width:464px; height: 0px; display: none;  text-align: center"><img style="margin-top: 15px" src="../../../images/bigrotation2.gif"/></div>
			    <ul  id="list_2" class="tahoma_12_blue">
			    </ul>
			</div>
			<div id="list_4_container" class="list_container">
			    <div id="lightBlueHeader">
				
				<div class="tahoma_14_white days_title_cls" id="lightBlueHeaderMiddle" style="width: 439px">Stages of 'Monday'</div>
				
			    </div>
			    <div class="lineUpLine tahoma_12_blue" >
				<img src="../../../images/festival_back_button.png" style="float: left; height: 33px;width: 134px;cursor: pointer; padding-right: 5px" onclick ="javascript: festivalDaysController.onBackToDaysClick();"/>
				<img id="stages_sorting_button" src="../../../images/lineup_sorting_dect.png" style="float: left; height: 25px;cursor: pointer" onclick ="javascript: stagesController.toggleSortingMode();"/>
				<div style="float: left;height: 33px;padding-top: 5px; padding-left: 5px;">Drag-and-drop stages to days</div>
			    </div>

			    <ul  id="list_4" class="tahoma_12_blue" style="margin-top: 5px">
				<div id="festivalDayStagesWaitingWheel" style="float: left; width:464px; height: 0px; display: none;  text-align: center"><img style="margin-top: 15px" src="../../../images/bigrotation2.gif"/></div>
			    </ul>
			</div>
			<div id="list_5_container" class="list_container">
			    <div id="lightBlueHeader">
				
				<div class="tahoma_14_white artists_title_cls" id="lightBlueHeaderMiddle" style="width: 439px">Artists in 'Stage 1'</div>
				
			    </div>
			    <div class="lineUpLine tahoma_12_blue" >
				<img src="../../../images/festival_back_stage_button.png" style="float: left; width: 143px;height: 33px;cursor: pointer" onclick ="javascript: stagesController.backToStagesDropView();"/>
				<div style="float: left;height: 33px;padding-top: 5px; padding-left: 5px;">Drag-and-drop artists to stages. Click time to edit.</div>
			    </div>
			    
			    <div id="waitingSearchWheelShows" style="float: left; width:464px; height: 0px; display: none;  text-align: center"><img style="margin-top: 15px" src="../../../images/bigrotation2.gif"/></div>
			    <ul  id="list_5" class="tahoma_12_blue" style="margin-top: 5px">
			    </ul>
			</div>
                    </div>

                </div>


            </div> <br class="clear"/> <br class="clear"/>
        </div>
       
        
        
        <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>

        <!-- Dialogs -->
        <div id="createArtistDialog" style="width: 300px;" title="Add New Artist">
            <div class="editRow">
                <div class="lable">Name</div>
                <div class="input">
                    <input id="txtArtistName" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>
            <div class="editRow">
                <div class="lable">Biography</div>
                <div class="input">
                    <textarea id="txtBiography" class="textFeildBoarder"  style="resize:none; height: 250px; width: 310px;"></textarea>
                </div>
            </div>
            <div class="editRow" id="divCoverImage">
                <div class="lable" >Artist Image</div>
                <div class="input" style="font-size: 12px">
                    <img id="artistThumbImage" style="float:left; width: 50px; height: 50px;"></img><div style="float:left; padding-left: 5px"> (Click on the image to add or change)</div>
                </div>
            </div>
	    <div class="editRow">
                <div class="lable">Web URL</div>
                <div class="input">
                    <input id="txtWebUrl" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>
	    <div class="editRow">
                <div class="lable">Facebook</div>
                <div class="input">
                    <input id="txtArtistFacebook" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>
	    <div class="editRow">
                <div class="lable">Twitter</div>
                <div class="input">
                    <input id="txtArtistTwitter" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>

	    <div class="editRow" id="divCoverImage">
                <div class="lable" >Logo</div>
                <div class="input" style="font-size: 12px">
                    <img id="artistLogoImage" style="float:left; width: 320px; height: 100px;"></img><div style="float:left; padding-left: 5px"> (Click on the image to add or change)</div>
                </div>
            </div>

	     <div class="editRow">
                <div class="lable">Video</div>
                <div class="input">
                    <input id="txtArtistVideo" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>

	     <div class="editRow">
                <div class="lable">Music</div>
                <div class="input">
                    <input id="txtArtistMusic" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>

	    <div class="editRow">
                <div class="lable">Site Image</div>
                <div class="input">
                    <input id="txtArtistSiteImg" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>

	     <div class="editRow">
                <div class="lable">Site Logo</div>
                <div class="input">
                    <input id="txtArtistSiteLogo" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>

        </div>
	<div id="createFestivalDayDialog" style="width: 300px;" title="Add Festival Date">
	    <div class="editRow">
                <div class="lable">Name</div>
                <div class="input">
                    <input id="txtFestivalName" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>
	    <div class="editRow">
                <div class="lable">Date</div>
                <div class="input">
                    <input id="txtFestivalDate" class="textFeildBoarder" style="float:left; height: 18px; width: 100px"></input>
		    <div style="float:left; padding-left: 5px; padding-top: 5px; font-size: 12px"> (YYYY-MM-DD)</div>
                </div>
            </div>
	</div>
	<div id="createStageDialog" style="width: 300px;" title="Add Stage">
            <div class="editRow">
                <div class="lable">Name</div>
                <div class="input">
                    <input id="txtStageName" class="textFeildBoarder" style="height: 18px; width: 310px"></input>
                </div>
            </div>
            <div class="editRow" id="divStageImage">
                <div class="lable" >Image</div>
                <div class="input" style="font-size: 12px">
		    <img id="stageThumbImage" style="float:left; width: 320px; height: 50px;"></img><div style="float:left; padding-left: 5px"> (Click on the image to add or change)</div>
                </div>
            </div>
        </div>

	<div id="addShowTimeDialog" style="width: 300px;" title="Add Show Time">
	    <div class="editRow">
                <div class="lable">Start Time</div>
                <div class="input">
		    <input id="txtShowTime" class="textFeildBoarder" style="float:left; height: 18px; width: 100px"></input>
		    <div style="float:left; padding-left: 5px; padding-top: 5px; font-size: 12px"> (HH:MM)</div>
                </div>
		
            </div>
	    <div class="editRow">
                <div class="lable">End Time</div>
                <div class="input">
		    <input id="txtShowEndTime" class="textFeildBoarder" style="float:left; height: 18px; width: 100px"></input>
		    <div style="float:left; padding-left: 5px; padding-top: 5px; font-size: 12px"> (HH:MM)</div>
                </div>
		
            </div>
	    <div class="editRow" style="padding-top: 10px;" >
		<div class="input" style="width:330px; ">
		    <input type="checkbox" id="chkPreventShowingShowDialog" style="height:12px; padding-right: 5px;"/> Prevent showing this dialog again for this <b>session</b>
		</div>
	    </div>
	</div>
        <script type="text/javascript">

        </script>
    </body>
</html>