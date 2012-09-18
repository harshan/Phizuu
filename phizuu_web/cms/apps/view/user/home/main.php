<?php
$menu_item = "home";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Line Up - phizuu CMS</title>

        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
	<link href="../../../view/user/home/styles_main.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../../../view/user/home/main.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../common/jquery.Jcrop.min.js"></script>
        <script type="text/javascript" src="../../../common/jquery.imagechooser.js"></script>
        
    </head>


    <body>
        <div id="mainWideDiv">
            <div id="middleDiv" class="tahoma_12_blue">
                <?php include("../../../view/user/common/header.php"); ?>
                <?php include("../../../view/user/common/navigator.php"); ?>
                <div id="bodyLineUp">

                    <div id="lineUpLeft">
			<div id="lightBlueHeader">
			    <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
			    <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 439px">Images</div>
			    <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
			</div>
			<ul id="list_1"></ul>
                    </div>

                    <div id="lineUpRight">
			<div id="lightBlueHeader">
			    <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
			    <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 439px">Preview</div>
			    <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
			</div>
			xx
                    </div>

                </div>


            </div>
        </div>
        <br/><br/><br/>
        
        
        <div id="footerMain">
            <div id="footer2" class="tahoma_11_blue">&copy; 2012 phizuu. All Rights Reserved.</div>

        </div>

        <!-- Dialogs 
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
	
	-->

        <script type="text/javascript">

        </script>
    </body>
</html>