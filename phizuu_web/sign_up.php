<?php
@session_start();

require_once 'cms/apps/config/config.php';
require_once 'cms/apps/database/Dao.php';
require_once 'cms/apps/model/app_builder/Navigator.php';
require_once 'cms/apps/model/login_model.php';

$password = '';
$username = '';

$err_msg = '';
$package_id = $_POST["package_id"];


if (isset($_POST['action'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];
    $agree = isset($_POST["agree"]);

    $invalidUsername = false;
    if ($username != '') {
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $dao = new Dao();
        $res = $dao->query($sql);
        if (mysql_num_rows($res)>0) {
            $invalidUsername = true;
        }
    }

    if ($username=='') {
        $err_msg = 'Please enter username!';
    } elseif ($invalidUsername) {
        $err_msg = 'Username already exists!';
    }elseif ($password=='') {
        $err_msg = 'Please enter password!';
    }elseif (strlen($password) < 6) {
        $err_msg = 'Length of the password should be more than 6 charactors!';
    }elseif ($password != $_POST['conf_password']) {
        $err_msg = 'Password and Confirm Password are not matching!';
    }elseif(!$agree){
	$err_msg = "Please acknowledge that you have read & accept, our terms of service and privacy policy";
    }

    if ($err_msg=='') {
        $sql =
        "INSERT INTO user (
            `username`,
            `password`,
            `app_id`,
            `app_name`,
            `email`,
            `parent`,
            `package_id`,
            `status`,
            `is_suspended`,
            `box_id`
        ) VALUES (
            '$username',
            md5('$password'),
            0,
            '',
            '',
            NULL,
            '$package_id',
            0,
            0,
            1
        )";

        $dao = new Dao();
        $res = $dao->query($sql);
        $userId = mysql_insert_id();

        $navigator = new Navigator($userId);
        $navigator->isFirstTime();

        $login = new LoginModel();
        $login->checkUser($username, md5($password));

        header("Location: cms/apps/controller/modules/app_wizard/AppWizardController.php");
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Description" content="phizuu:connect is a mobile application platform targeted towards the music industry. Its main focus is to give musicians the opportunity to make an intimate connection with their fans around the globe.">
<meta name="keywords" content="phizuu, phizuu connect, phizzu:connect, iphone, iphone app, iphone apps,	 blackberry, android, apps, app(s), music, artist, musician, dj, band">
<title>Sign up for phizuu: iPhone apps for Musicians, Artists, Bands, DJ's, Comedians...</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/bodyAboutUs.css" rel="stylesheet" type="text/css" />


</head>

<body>
<form method="post" name="frmSign_up" >
    <input type="hidden" name="package_id" value="<?php echo $package_id; ?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" height="388" align="center" valign="top">
		<table width="972" height="499" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top">
		<div id="aboutUsMiddleMainDiv">
			<div id="naviBig">
            <div id="navigater"> <a href="index.html" class="naviTab" title="phizuu Home - iPhone apps for Musicians, Artists, DJs, Comedians">
                      <div>home</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="aboutus.html" class="naviTab" title="About phizuu - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div>about</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="tour.html" class="naviTab" title="phizuu Tour - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div>tour</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="faqs.html" class="naviTab" title="Frequently Asked Questions on phizuu - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div >faq's</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="pricing.html" class="naviTab" title="phizuu Pricing - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div >pricing</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="contact.php" class="naviTab" title="Contact phizuu - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div >contact</div>
                      </a> </div>
        </div>
			<div id="naviBig2"></div>
			<div id="abotUsMainImg"><img src="images/aboutUsImage.png" /></div>
			<div id="getMoreBoxIncluder">
				<div id="aboutUsGetStarted">
					<div id="getStartedMid"><a href="pricing.html" class="getStart" title="Get Started with phizuu">Get Started</a> </div>
					<div id="getStartedRght"><img src="images/getStartRght.png" /></div>
					<div id="takeTourvbttnLft"><a href="tour.html" class="getStart2" title="Take the phizuu Tour">Or, take the tour to see how it works</a></div>
					<div id="takeTourvbttnRght2"></div>
		  		</div>
			</div>
			<div id="whatIsPhizuuBoxFaqs">
			  <div id="phizuuTitle">sign up </div>
			  <div id="phozzuText">Great! Now that you have chosen your application type, we can get started on your account info. Once you are done signing up, the phizuu staff will review your account and application details and get back to you within 24 hours with acceptance of your account. At that point, you can get started on creating your application!</div>
	  </div>
		</div></td>
  </tr>
</table>	</td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#efefef">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
			<div id="signUpBody">
			  <div class="signUpHead">Quick Signup</div>
			  	<div id="bodyContactRght">
					<div id="forum_fields_names">
                                                <div class="text_fld2"></div>
						<div class="text_fld2">Username   * </div>
						<div class="text_fld2">Password   * </div>
                                                <div class="text_fld2">Confirm Password   * </div>
                                                <div class="text_fld2">Email   * </div>
                                                <div class="text_fld2"></div>
					</div>
					<!---->
                                        <div id="forum_fieldsSignUp">
                                            <div id="error_field_review"><?php echo $err_msg ?></div>
                                            <div class="text_field">

                                                <div class="text_field">
                                                    <div class="hider">
                                                        <input name="username" type="text" class="removeGlow" id="username" style="width:275px; height:29px" value="<?php echo $username?>" />
                                                    </div>
                                                </div>
                                                <div class="text_field">
                                                    <div class="hider">
                                                        <input name="password" type="password" class="removeGlow" id="password" style="width:275px; height:29px" value="<?php echo $password?>" />
                                                    </div>
                                                </div>
                                                <div class="text_field">
                                                    <div class="hider">
                                                        <input name="conf_password" type="password" class="removeGlow" id="password" style="width:275px; height:29px" value="" />
                                                    </div>
                                                </div>
                                                <div class="text_field">
                                                    <div class="hider">
                                                        <input name="email" type="text" class="removeGlow" id="password" style="width:275px; height:29px" value="" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
			</div>
            
			  	
            </div></td>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#808080">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
			<div id="signUpBody">
				<div class="signUpHead">Review &amp; accept our terms</div>
				<div id="whatwouldULike">
				  <div id="review">
				    <input name="agree" id="agree" value="1" type="checkbox" />
				    I have read and accept the <a class="review_a" href="terms_of_use.html">terms of use</a>, and <a class="review_a" href="privacy_policy.html">privacy policy.</a></div>
				</div>
			</div></td>
          </tr>
        </table>	</td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#FFFFFF">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
			<div id="signButtonContainer">
			  <div class="button"> <input type="image" src="images/submit.png" /><input type="hidden" name="action" value="submit" /> </div>
			</div>
			<div id="footer">
		<div class="footerUpper"><img src="images/footerUpper.png" /></div>
		<div id="footerMid">
		  <div id="subMenuBox1">
			<div id="learnAboutUs"><strong>Learn About Us</strong></div>
			<div id="learnAboutUsText1"><a href="contact.html" class="tahoma_12_darkgray" title="Contact phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Contact</strong></a><br />
			  <a href="aboutus.html" class="tahoma_12_darkgray" title="About phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>About Us</strong></a><br />
			  <a href="jobs.html" class="tahoma_12_darkgray" title="Jobs at phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Jobs</strong></a><br />
			  <a href="blog/" target="_blank" class="tahoma_12_darkgray" title="phizuu Blog - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Team Blog</strong></a><br />
			</div>
		  </div>
		  <div id="subMenuBox2">
		  	<div id="submenuTextContainer1">
				<div id="learnAboutUs2"><strong>Friend Us</strong></div>
				<div id="learnAboutUsText2"><strong><a href="http://www.facebook.com/phizuu" class="tahoma_12_darkgray" target="_blank" title="phizuu Facebook Page - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Facebook</strong></a></strong><br />
				  <a href="http://www.myspace.com/phizuu" class="tahoma_12_darkgray" target="_blank" title="phizuu on MySpace- iPhone apps for Musicians, Artists, DJs, Comedians"><strong>MySpace</strong></a><br />
				  <strong><a href="http://www.twitter.com/phizuu" class="tahoma_12_darkgray" target="_blank" title="phizuu on Twitter - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Twitter</strong></a></strong><br />
				  <a href="http://www.hi5.com/phizuu" class="tahoma_12_darkgray" target="_blank" title="phizuu on Hi5 - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Hi5!</strong></a></div>
			</div>
		  </div>
		  <div id="subMenuBox3">
		  	<div id="submenuTextContainer2">
				<div id="learnAboutUs3"><strong>Help</strong></div>
				<div id="learnAboutUsText3"><a href="faqs.html" class="tahoma_12_darkgray" title="Frequently Asked Questions on phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>FAQ</strong></a><br />
				<a href="dmca_notice.html" class="tahoma_12_darkgray" title="DMCA Notice - phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>DMCA Notice</strong></a><br />
				  <a href="terms_of_service.html" class="tahoma_12_darkgray" title="Terms of Service - phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Terms of Service </strong></a><br />
				  <strong><a href="privacy_policy.html" class="tahoma_12_darkgray" title="Privacy Policy - phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Privacy Policy</strong></a></strong><br />
			  </div>
			</div>
		  </div>
		  <div id="subMenuBox3">
		  	<div id="submenuTextContainer3">
				<div id="learnAboutUs4"><strong>Connect</strong></div>
				<div id="learnAboutUsText4"><a href="http://www.phizuu.com/connect" class="tahoma_12_darkgray" title="Login to phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Login</strong></a><br />
				  <a href="pricing.html" class="tahoma_12_darkgray" title="Sign up for phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Sign Up</strong></a></div>
			</div>
		  </div>
		  <div id="pizzuLogo">
			<div id="logo"><img src="images/logo.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" title="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" /></div>
			<div id="logoText">
<p>&copy; 2012 phizuu. All Rights Reserved.</p>
			  <p><br />
			      <br />
			        <span style="font-size:10px;"><a href="http://www.eight25media.com" class="tahoma_10_link" title="Eight25Media">design by eight25media</a></span></p>			
			</div>
		  </div>
		</div>
		<div class="footerUpper"><img src="images/footerDown.png" /></div>
	  </div>		</td>
      </tr>
    </table>	</td>
  </tr>
</table>
</form>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10421740-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
