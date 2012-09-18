<?php
$packageId = $popArray['packageInfo']['package_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript">
function validate() {
    if (document.getElementById('aboutText').value=='') {
        alert("Please enter something about your application in About box!");
        return false;
    } else if (document.getElementById('aboutText').value.length<100) {
        alert("Plese enter a description with at least 100 charactors in About Box!");
        return false;
    } else if (document.getElementById('bioText').value=='') {
        alert("Please enter something about your self in Bio box!");
        return false;
    } else if (document.getElementById('bioText').value.length<100) {
        alert("Plese enter a description with at least 100 charactors in Bio Box!");
        return false;
    } else if (document.getElementById('keywordText').value=='') {
        alert("Please enter few keywords in Keywords box!");
        return false;
    } else if (document.getElementById('fanWallYes') && document.getElementById('fanWallYes').checked && document.getElementById('fanWallPost').value=='') {
        alert("Please enter some text in FanWall initial post text!");
        return false;
    } else if(!validateLink($('#twitterUsername').val())){
//    } else if (document.getElementById('twitterYes') && document.getElementById('twitterYes').checked && document.getElementById('twitterUsername').value=='') {
//        alert("Please enter your twitter username!");
//        return false;
//    } else if (document.getElementById('twitterYes') && document.getElementById('twitterYes').checked && twitterValidating) {
//        alert("Twitter username is being validated. Please wait a second!");
//        return false;
//    } else if (document.getElementById('twitterYes') && document.getElementById('twitterYes').checked && !twitterValid) {
//        alert("Twitter username is invalid!");
//        return false;
         alert('Error! Invalid link format. Please prefix http:// or relavant scheme to the link.');
        
         return false;
    } else {
       
        return true;
    }
}
function validateLink(url) {
        var regex = /(\w+)\:\/\/(\w+)\.(\w+)/;

        return url.match(regex);
    }
function textCounter() {
    var divCount = document.getElementById('keywordCharCounter');
    var charCount = 100 - document.getElementById('keywordText').value.length;
    if (document.getElementById('keywordText').value.length<=100) {
        divCount.innerHTML =  "Charactors Left: " + charCount;
        return true;
    } else {
        document.getElementById('keywordText').value = document.getElementById('keywordText').value.substring(0, 100);
        return false;
    }
}

function takeAction(action) {
    if (action=='skip') {
         window.location = "AppWizardControllerNew.php?action=information_module_skip";
    } else if (validate()) {
        if (action=='save') {
            document.getElementById('mainForm').submit();
        } else if (action=='save_info') {
            document.getElementById('mainForm').action = "AppWizardControllerNew.php?action=information_module_save";
            document.getElementById('mainForm').submit();
        }
    }
}

function hideFanWallPost(show) {
    if (show)
        $('#fanWallPostTD').show();
    else
        $('#fanWallPostTD').hide();
}

function hideTwitterUsername(show) {
    if (show)
        $('#twitterModuleTD').show();
    else
        $('#twitterModuleTD').hide();
}

var twitterValidating = false;
var twitterValid = false;

function twitterUsernameChanged(item) {
    if (item.value=='')
        return;
    
    $('#twitterUserNameDesc').html('Checking...');
    twitterValidating = true;
    $.post('AppWizardControllerNew.php?action=validate_twitter', {'username':item.value}, function(data) {
        if (data=='RSS') {
            $('#twitterUserNameDesc').css('color','#07738A');
            $(item).css('background-color','#AFD43D');
            $('#twitterUserNameDesc').html('Twitter Username: (Verified)');
            twitterValid = true;
        } else {
            $('#twitterUserNameDesc').css('color','#FF0000');
            $(item).css('background-color','#FF0000');
            $('#twitterUserNameDesc').html('Twitter Username: (The twitter username is invalid. Please double check!)');
            twitterValid = false;
        }
        twitterValidating = false;
    });
}

function twitterUsernameReset(item) {
    $('#twitterUserNameDesc').css('color','#1e1f1f');
    $(item).css('background-color','#FFFFFF');
    $('#twitterUserNameDesc').html('Twitter Username:');
}
        </script>

    </head>


    <body>
        <div id="mainWideDiv">
            <div id="header">
        <div style="width: 800px;height: 90px;margin: auto">
                        <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logoInner.png" width="350" height="35" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="99" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
        </div>
                </div>
            <div id="middleDiv2">
              
                <form id="mainForm" action="AppWizardControllerNew.php?action=write_xml" method="post" onsubmit="javascript: return validate()">
                <div id="body">                        <br/>
                        <?php if(isset($_SESSION['update_contents']) && $_SESSION['update_contents']=='yes') {
                            include '../../../view/user/app_wizard/supporting/contect_update_inc.php';
                        }?>

                                                <div class="wizardTitle" >
                            
                            <div class="middle" style="width: 910px">Provide information about your application</div>
                           
                        </div>
                    <br/>&nbsp;
      <table width="533" border="0" cellspacing="4" cellpadding="4" class="tahoma_12_blue" style="font-size: 14px">
          <?php if ($packageId!=1 && !isset($_SESSION['update_contents'])) { ?>
        <tr>
          <td width="60" valign="top"><strong>FanWall</strong></td>
          <td width="445">
              "Fan Wall" is a place where your fans can write anything they like. Definitely, it would be
              a cool feature in your App. Would you like to add "Fan Wall"
              to your App?
              <br/><br/>
              <input onclick="javascript: hideFanWallPost(true);" type="radio" name="fanWall" value="Yes" id="fanWallYes" checked/>Yes
              <input onclick="javascript: hideFanWallPost(false);" type="radio" name="fanWall" value="No" id="fanWallNo"/>No
          </td>
        </tr>
        <tr id="fanWallPostTD">
            <td width="60" valign="top">&nbsp;</td>
            <td width="445" >
                <span style="font-size: 10px">Following text will be posted in your app as the first post</span>
                <textarea name="fanWallPost" id="fanWallPost" style="width: 400px; height: 100px">Welcome to the official '<?php echo $popArray['userInfo']['app_name'] ?>' iPhone application, presented by phizuu</textarea>
            </td>
        </tr>
          <?php } ?>
          <?php if (!isset($_SESSION['update_contents'])) { ?>
        <tr>
          <td width="60" valign="top"><strong>Facebook</strong></td>
          <td width="445">
              Enter your facebook link
             
              <input onclick="javascript: hideTwitterUsername(true);" type="radio" name="twitter" value="Yes" id="twitterYes" checked style="display: none"/>
              
          </td>
        </tr>
        <tr id="twitterModuleTD">
            <td width="60" valign="top">&nbsp;</td>
            <td width="445" >
             
                <input type="text" name="twitterUsername" id="twitterUsername" style="width: 400px; height: 20px"></input>
            </td>
        </tr>
          <?php } ?>
        <tr>
            <td width="60" valign="top"><strong>Note:</strong></td>
            <td width="445"><strong>Please note that after submitting the application About/Bio or Keywords can never be changed</strong></td>
        </tr>
        <tr>
          <td width="60" valign="top"><strong>About</strong></td>
          <td width="445"><textarea name="aboutText" id="aboutText" style="width: 400px; height: 100px"></textarea></td>
        </tr>
        <tr>
          <td valign="top"><strong>Bio</strong></td>
          <td><textarea name="bioText" id="bioText" style="width: 400px; height: 100px"></textarea></td>
        </tr>
        <tr>
          <td valign="top"><strong>Keywords</strong></td>
          <td>
              <textarea name="keywordText" id="keywordText" style="width: 400px; height: 100px" onkeydown="return textCounter()"
onkeyup="return textCounter()"></textarea>
              <div style="font-size: 12px; text-align: right" id="keywordCharCounter"></div>
          </td>
        </tr>
      </table>

                    <div id="bodyLeftWizard">

                                <?php
                                if(isset($_SESSION['update_contents'])) { ?>
                                    <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="33" />
                                    <img class="wizardButton" src="../../../images/btn_next.png" width="89" height="33"  onclick="javascript: takeAction('save_info');" />
                                <?php } else { ?>
                                    <img class="wizardButton" src="../../../images/wizard_btn_complete_application.png"  width="194" height="33" onclick="javascript: takeAction('save');" />
                                <?php } ?>
                    </div>
                </div>

                </form>
                <br />

                <!--<div id="indexBodyRight"></div>-->
            </div>
            <div id="buttonContainer">&nbsp;</div>
        </div>

        <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
    </body>
</html>

