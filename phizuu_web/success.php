<?php
session_start();

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

<style type="text/css">
.successfulText {
	font-family: Tahoma;
	font-size: 16px;
	color:#333333;
	width: 600px;
	}
</style>
</head>

<body>
<form method="post" name="frmSign_up">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" height="388" align="center" valign="top">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="top"><div id="naviBig">
            <div id="navigater"> <a href="index.html" class="naviTab">
              <div>HOME</div>
              </a>
                <div class="naviTabDevider"></div>
              <a href="aboutus.html" class="naviTab">
                <div>ABOUT</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="tour.html" class="naviTab">
                <div>TOUR</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="faqs.html" class="naviTab">
                <div >FAQ'S</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="pricing.php" class="naviTab_active">
                <div >PRICING</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="contact.php" class="naviTab">
                <div >CONTACT</div>
              </a> </div>
        </div>
            <div id="naviBigPrizing">
				<div id="logoHeader"><img src="images/phizuu.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" width="205" height="71" /></div>
                                <div id="logoHeader2"></div>
				<div id="signUpBody">
			  <div class="signUpHead1"><?php echo ($_SESSION['payment_error']=='yes')?'Payment Error!':'Signup Successful' ?></div>
              <div class="successfulText">
              
              <?php 
if($_SESSION['payment_error']=='yes') {
        echo '<span style="color: red">Error Occured: ' . $_SESSION['reshash']['L_LONGMESSAGE0'] . "</span><br/><br/>";

	echo "Your payment has not been approved. Unfortunately, we can't register you in phizuu without a payment.<br/><br/>Please check the details you entered and try again. Thank you for the interest in phizuu!";
} elseif($_SESSION['payment_error']=='no') {
	echo "<b>Your payment was successful!</b><br/><br/>You have signed up for the {$_SESSION['package_name']} package and will be receiving an email shortly with you account information. Please follow the link in the email to start building your iPhone Application at anytime.";

        if($_SESSION['recurrent']) {
            if($_SESSION['recurrent_failed']=='yes') {
                echo '<br/><br/><span style="color: red">But error occured while setting recurrent payments: ' . $_SESSION['reshash']['L_LONGMESSAGE0'] . ".</span><br/><br/>";

                echo "Since setting up recurrent payments has failed, you have to log in to CMS and setup recurrent payments within next month (after completing your application).";
            } else {
                echo "<br/><br/>Your recurrent payment has also been setup.";
            }
        }
}else {
    echo "You have signed up for the GarageBand package and will be receiving an email shortly with you account information. Please follow the link in the email to start building your iPhone Application at anytime.<br/><br/><b>Please remember you can upgrade your package anytime.</b><br/>";
}

session_destroy();
?>


             

              </div>
                </div>
            </div>


            </td>

      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#efefef">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
</td>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td height="51" align="center" valign="top" bgcolor="#808080">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
			<div id="signUpBody">
			  <div id="whatwouldULike"></div>
			</div></td>
          </tr>
        </table>	</td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#FFFFFF">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
			<div id="signButtonContainer"></div>
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
