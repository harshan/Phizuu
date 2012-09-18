<?php
session_start();

function get_param($ParamName) {
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;

    $ParamValue = "";
    if(isset($HTTP_POST_VARS[$ParamName]))
        $ParamValue = $HTTP_POST_VARS[$ParamName];
    else if(isset($HTTP_GET_VARS[$ParamName]))
        $ParamValue = $HTTP_GET_VARS[$ParamName];

    return $ParamValue;
}

$name=get_param("name");
$company=get_param("company");
$email=get_param("email");
$message_subject=get_param("subject");
$enquiry=get_param("enquiry");
$Submit=get_param("Submit");

if (isset($_COOKIE["r"]))
    $error = $_COOKIE["r"];
else
    $error = "";

if($Submit=='send') {

    if($name=="") {
        $error="Enter your name";
    }else if($email=="") {
        $error="Enter your email address";
    }else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
        $error="Invalid E-mail Address";
    }else if($message_subject=="") {
        $error="Enter the message subject";
    }else if(strtoupper($_POST['captcha']) != strtoupper($_SESSION['captcha'])) {
        $error="Enter text in the image correctly";
    }else {

        $message = '
			<html>
			<head>
			  <title>phizuu enquiry</title>
			</head>
			<body>  
			<table width="415">
			  <tr> 
				<td width="4">&nbsp;</td>
				<td width="127"><p><font face="Arial, Helvetica, sans-serif"><font size="2"><font size="2"></font></font></font></p></td>
				<td width="268"><p><font face="Arial, Helvetica, sans-serif"><font size="2"><font size="2"></font></font></font></p></td>
			  </tr>
			  <tr> 
				<td>&nbsp;</td>
				<td colspan="2" align="center">
			<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>:::::::: Phizuu Enquiry </strong></font><font size="2" 
			face="Arial, Helvetica, sans-serif"><strong>::::::::</strong></font></p></td>
			  </tr>
			  <tr> 
				<td>&nbsp;</td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Name</strong></font><strong>:</strong></p></td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif">'.$name.'</font></p></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Company Name</strong></font><strong>:</strong></p></td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif">'.$company.'</font></p></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif"><strong>Email</strong></font><strong>:</strong></p></td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif">'.$email.'</font></p></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><p><strong>Message Subject :</strong> </p></td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif">'.$message_subject.'</font></p></td>
			  </tr>
			  <tr> 
				<td>&nbsp;</td>
				<td valign="top"><p><strong>Enquiry:</strong></p></td>
				<td><p><font size="2" face="Arial, Helvetica, sans-serif">'.$enquiry.'</font></p></td>
			  </tr>
			</table>
			</body>
			</html>
			';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= 'To:info@phizuu.com' . "\r\n";
        //$headers .= 'To:isuru.buddhike@gmail.com' . "\r\n";
        $headers .= 'From: '.$email. "\r\n";

        $to = "info@phizuu.com";
        //$to = "idhanu@gmail.com";
        //$to = "isuru.buddhike@gmail.com";
        // Mail it
        mail($to, $message_subject, $message, $headers);

        setcookie("r", "Your Message has been received. Thank You", time()+60);
        header("location:contact.php");

    }

    unset($_SESSION['captcha']);

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Description" content="phizuu:connect is a mobile application platform targeted towards the music industry. Its main focus is to give musicians the opportunity to make an intimate connection with their fans around the globe.">
<meta name="keywords" content="phizuu, phizuu connect, phizzu:connect, iphone, iphone app, iphone apps,	 blackberry, android, apps, app(s), music, artist, musician, dj, band">
<title>Contact phizuu: iPhone apps for Musicians, Artists, Bands, DJ's, Comedians...</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/bodyContact.css" rel="stylesheet" type="text/css" />

</head>

<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="499" align="center" valign="top"><table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="top"><div id="naviBig">
          <div id="navigater"> <a href="index.html" class="naviTab" title="phizuu Home - iPhone apps for Musicians, Artists, DJs, Comedians">
                      <div>HOME</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="aboutus.html" class="naviTab" title="About phizuu - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div>ABOUT</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="tour.html" class="naviTab" title="phizuu Tour - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div>TOUR</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="faqs.html" class="naviTab" title="Frequently Asked Questions on phizuu - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div >FAQS</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="pricing.html" class="naviTab" title="phizuu Pricing - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div >PRICING</div>
                      </a>
                        <div class="naviTabDevider"></div>
                      <a href="contact.php" class="naviTab_active" title="Contact phizuu - iPhone apps for Musicians, Artists, DJs, Comedians">
                        <div >CONTACT</div>
                      </a> </div>
        </div>
		<div id="naviBig_contact">
		  <div id="logoHeader"><img src="images/phizuu.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" title="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" /></div>
		</div>
		<div id="headerLft">
			<div id="iphone"><img src="images/iphone.png" alt="Phizuu Contact - iPhone, Blackberry, Android apps" title="Phizuu Contact - iPhone, Blackberry, Android apps" /></div>
		</div>
		
		<div id="headerRght">
		  <div id="homeIcon"><img src="images/house.png" alt="phizuu - Headoffice" title="phizuu - Headoffice" /></div>
		  <div id="houseText">Phizuu<br />Silicon Valley, Ca</div>
		  <div id="phoneIcon"><img src="images/phone.png" alt="phizuu - Contact number" title="phizuu - Contact number" /></div>
		  <div id="phoneText">(408) 940-5830</div>
		  <div id="letterIcon"><img src="images/letter.png" alt="phizuu - Email address" title="phizuu - Email address" /></div>
		  <div id="letterText">info@phizuu.com</div>
		  <div id="birdIcon"><img src="images/bird.png" alt="phizuu - Twitter ID" title="phizuu - Twitter ID" /></div>
		  <div id="birdText">@phizuu</div>
		</div>
		</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#FFFFFF"><table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="middle">
			<div id="bodyContact">
	  	<div id="bodyContactLft">
		  <div id="contactGetStarted">
		  	<div id="getStartedMid"><a href="pricing.html" class="getStart" title="Get Started with phizuu">Get Started </a></div>
		  	<div id="getStartedRght"><img src="images/getStartRght.png" /></div>
			<div id="takeTourvbttnLft"><a href="tour.html" class="getStart2" title="Take the phizuu Tour">Or, take the tour to see how it works</a></div>
			<div id="takeTourvbttnRght2"></div>
		  </div>
		</div>
		<form action="contact.php" method="post">		
		<div id="bodyContactRghtContact">
			
			<div id="forum_fields_namesContact">
				<div id="name_fld">Name * </div>
				<div class="text_fld">Company</div>
				<div class="text_fld">Email * </div>
				<div class="text_fld">Message Subject * </div>
                                <div id="enquiry_fld" style="height: 140px">Enquiry</div>
                                <div id="enquiry_fld">Please enter text in the image below</div>
		  	</div>
			<!---->
			<div id="forum_fields">
		  	<div class="downloadit" id="error_field"><?php echo ($error); ?></div>
			<div class="text_field">
				<div class="hider">
					<input type="text" class="removeGlow" name="name" id="name" style="width:275px; height:29px;"/>
				</div>	
			</div>
			<div class="text_field">
				<div class="hider">
					<input type="text" class="removeGlow" name="company" id="company" style="width:275px; height:29px;"/>
				</div>
			</div>
			<div class="text_field">
				<div class="hider">
					<input type="text" class="removeGlow" name="email" id="email" style="width:275px; height:29px;"/>
				</div>
			</div>
			<div class="text_field">
				<div class="hider">
					<input type="text" class="removeGlow" name="subject" id="subject" style="width:275px; height:29px;"/>
				</div>	
			</div>
			<div id="enquity_field">
				<div class="hider2">
					<textarea class="removeGlow2" name="enquiry" id="enquiry"/></textarea>
				</div>	
			</div>
                        <div id="enquity_field">
                                <div style="margin: 10px 10px 10px 0">
                                    <img src="captchar/captchar.php" alt ="Captchar" width="270" style="border: 2px #EFEFEF solid"/>
                                </div>
				<div class="hider">
					<input type="text" class="removeGlow" name="captcha" id="captcha" style="width:275px; height:29px;"/>
				</div>	
			</div>
       	    <div id="bttns_bar">
				  <div class="button">
						<input name="Submit" type="image" value="send" src="images/submit.png" />
						<input name="Submit" type="hidden" id="Submit" value="send" />
				  </div>
			  </div>
			</div>
			 <!---->
		</div></form>
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
				  <!--<a href="http://www.hi5.com/phizuu" class="tahoma_12_darkgray" target="_blank" title="phizuu on Hi5 - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Hi5!</strong></a>--></div>
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
				<div id="learnAboutUsText4"><a href="http://www.phizuu.com/cms/apps" class="tahoma_12_darkgray" title="Login to phizuu - iPhone apps for Musicians, Artists, DJs, Comedians"><strong>Login</strong></a><br />
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
	  </div>
		</td>
      </tr>
    </table></td>
  </tr>
</table>
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
