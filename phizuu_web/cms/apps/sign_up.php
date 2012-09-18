<?php
if(!isset ($_POST["package_id"])){
    header("Location: pricing.html");
}

$freePackageId = '1';
$package_id = $_POST["package_id"];
@session_start();


require_once '../cms/apps/config/config.php';
require_once '../cms/apps/database/Dao.php';
require_once '../cms/apps/model/app_builder/Navigator.php';
require_once '../cms/apps/model/login_model.php';

if(isset ($_SESSION['upgrade'])) {
    $dao = new Dao();
    $sql = "UPDATE user SET package_id=$package_id WHERE id={$_SESSION['user_id']}";
    $dao->query($sql);
    header("Location: payment.php");
}


$password = '';
$username = '';
$email = '';

$err_msg = '';
$package_id = $_POST["package_id"];

if (isset($_POST['action'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];
    $agree = isset($_POST["agree"]);
    $email = $_POST["email"];

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
    }else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
            $err_msg =  "Invalid email";
    }

    if ($err_msg=='') {
        $emailCode = '';
        for ($i=0; $i<64; $i++) {
            $emailCode .= chr(rand(97,122));
        }

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
            `box_id`,
            `paid`,
            `email_code`
        ) VALUES (
            '$username',
            md5('$password'),
            0,
            '',
            '$email',
            NULL,
            '$package_id',
            0,
            1,
            1,
            0,
            '$emailCode'
        )";
        

        $dao = new Dao();
        $res = $dao->query($sql);
        $userId = mysql_insert_id();
        
        $_SESSION['user_id'] = $userId;

        $navigator = new Navigator($_SESSION['user_id']);
        $navigator->isFirstTime();
        $navigator->setCurrentStep(1);       
        

        $link = "http://phizuu.com/confirm_email.php?id=$userId&code=$emailCode";
        //echo $link;
        sendEmail($username, $email, $password, $link);

        if ($freePackageId != $package_id) {
            header("Location: payment.php");
        } else {
            $_SESSION['payment_error'] = 'free';
            header("Location: ../success.php");
        }
    }
}

function sendEmail($username, $email, $password, $link)
{
	require_once '../admin/common/XPertMailer.php';

	$subject = "Welcome to phizuu";
	$mail = new XPertMailer;
	$mail->from($email, 'phizuu');

	$text = '';

	$msg = "<html>\n";
	$msg .= "<body>\n";
	$msg .= "<span style='font-family:Arial, Helvetica, sans-serif;font-size:12px'><br/>\n";
        $msg .= "Hey,<br><br>";
        $msg .= "Please confirm that you own this email address by clicking the link below and you're ready to go!<br><br>";
	$msg .= "<a href='$link'>here</a><br/><br/>";
	$msg .= "Username: $username<br/>";
	$msg .= "Password: $password<br><br>";
        $msg .= "Thank you for joining phizuu!!<br><br>";
        $msg .= "- Team phizuu<br><br>";
	$msg .= "</span></body>\n";
	$msg .= "</html>\n";

	$send = $mail->send($email, $subject, $text, $msg);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Description" content="phizuu:connect is a mobile application platform targeted towards the music industry. Its main focus is to give musicians the opportunity to make an intimate connection with their fans around the globe.">
<meta name="keywords" content="phizuu, phizuu connect, phizzu:connect, iphone, iphone app, iphone apps,	 blackberry, android, apps, app(s), music, artist, musician, dj, band">
<title>Sign up for phizuu: iPhone apps for Musicians, Artists, Bands, DJ's, Comedians...</title>
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bodyAboutUs.css" rel="stylesheet" type="text/css" />


</head>

<body>
<form method="post" name="frmSign_up">
    <input type="hidden" name="package_id" value="<?php echo $package_id; ?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" height="388" align="center" valign="top">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="top"><div id="naviBig">
            <div id="navigater"> <a href="index.html" class="naviTab">
              <div>home</div>
              </a>
                <div class="naviTabDevider"></div>
              <a href="../aboutus.html" class="naviTab">
                <div>about</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../tour.html" class="naviTab">
                <div>tour</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../faqs.html" class="naviTab">
                <div >faq's</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../pricing.html" class="naviTab_active">
                <div >pricing</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../contact.php" class="naviTab">
                <div >contact</div>
              </a> </div>
        </div>
            <div id="naviBigPrizing">
				<div id="logoHeader"><img src="../images/phizuu.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" width="205" height="71" /></div>
                                <div id="logoHeader2"><img src="../images/sxsw_125.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" width="83" height="125" /></div>
				<div id="signUpBody">
			  <div class="signUpHead1">Quick Signup</div>
                          <div id="bodyContactRght" style="left: 30px; padding-left: 20px">
					<div id="forum_fields_names">
                                                <div class="text_fld3"></div>
						<div class="text_fld3">Username   * </div>
						<div class="text_fld3">Password   * </div>
                                                <div class="text_fld3">Confirm Password   * </div>
                                                <div class="text_fld3">Email   * </div>
                                                <div class="text_fld3"></div>
					</div>
					<!---->
                                        <div id="forum_fieldsSignUp">
                                            <div id="error_field_review" style="font-size: 18px"><?php echo $err_msg ?></div>
                                            <div class="text_field" style="width:275px; height:40px">

                                                <div class="text_fld3" >
                                                    <div class="hider" style="height:36px; width:350px;">
                                                        <input name="username" type="text" class="removeGlow3" id="username" style="width:350px; height:28px" value="<?php echo $username?>" />
                                                    </div>
                                                </div>
                                                <div class="text_fld3" >
                                                    <div class="hider" style="height:36px; width:350px;">
                                                        <input name="password" type="password" class="removeGlow3" id="password" style="width:350px; height:28px" value="<?php echo $password?>" />
                                                    </div>
                                                </div>
                                                <div class="text_fld3" >
                                                    <div class="hider" style="height:36px; width:350px;">
                                                        <input name="conf_password" type="password" class="removeGlow3" id="password" style="width:350px; height:28px" value="" />
                                                    </div>
                                                </div>
                                                <div class="text_fld3" >
                                                    <div class="hider" style="height:36px; width:350px;">
                                                        <input name="email" type="text" class="removeGlow3" id="password" style="width:350px; height:28px" value="<?php echo $email?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
			  <div class="button"> <input type="image" src="../images/submit.png" /><input type="hidden" name="action" value="submit" /> </div>
			</div>
			<div id="footer">
		<div class="footerUpper"><img src="../images/footerUpper.png" /></div>
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
			<div id="logo"><img src="../images/logo.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" title="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" /></div>
			<div id="logoText">
<p>&copy; 2012 phizuu. All Rights Reserved.</p>
			  <p><br />
			      <br />
			        <span style="font-size:10px;"><a href="http://www.eight25media.com" class="tahoma_10_link" title="Eight25Media">design by eight25media</a></span></p>
			</div>
		  </div>
		</div>
		<div class="footerUpper"><img src="../images/footerDown.png" /></div>
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
