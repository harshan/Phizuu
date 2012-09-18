<?php
session_start();
require_once '../cms/apps/config/config.php';
require_once '../cms/apps/database/Dao.php';
require_once '../cms/apps/model/app_builder/AppWizard.php';
require_once '../cms/apps/model/app_builder/Navigator.php';

error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
include("../admin/common/common.php");
include("../cc_validation.php");


$dao = new Dao();
$sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
$res = $dao->query($sql);
$userArr = $dao->getArray($res);
$userArr = $userArr[0];

//check_HTTPS();
//deleteAbandonedMembers();

$freePackageId = 1;

$username = $userArr['username'];

$fname = trim($_REQUEST["fname"]);
$lname = trim($_REQUEST["lname"]);
$address = trim($_REQUEST["address"]);
$city = trim($_REQUEST["city"]);
$state = trim($_REQUEST["state"]);
$zip = trim($_REQUEST["zip"]);
$country = $_REQUEST["country"];
$cc_owner = trim($_REQUEST["cc_owner"]);
$cc = trim($_REQUEST["cc"]);
$cc_number = trim($_REQUEST["cc_number"]);
$expiry_m = $_REQUEST["expiry_m"];
$expiry_y = trim($_REQUEST["expiry_y"]);
$cvvNumber = trim($_REQUEST["cvvNumber"]);

$action = $_REQUEST["action"];

$err_msg = "";

if (isset ($_REQUEST["push"])) {
    $push = 1;
} else {
    $push = 0;
}

$email = $userArr['email'];
$package_id = $userArr['package_id'];

if( $package_id == "1"){
    $price = "0";
    $recurrentPrice = "0";
    $package = "garage band";
}else if($package_id == "2"){
    $price = "199";
    $package = "idol";
    $recurrentPrice = "9.99";
}else if($package_id == "3"){
    $price = "499";
    $package = "rockstar";
    $recurrentPrice = "29.99";
} else if( $package_id == "4"){
    $price = "99";
    $package = "android+bb: garage band";
    $recurrentPrice = "4.99";
}else if($package_id == "5"){
    $price = "149";
    $recurrentPrice = "6.99";
    $package = "android+bb: idol";
}else if($package_id == "6"){
    $price = "199";
    $package = "android+bb: rockstar";
    $recurrentPrice = "9.99";
}


switch($action){
	case "submit":
                if($fname == ""){
			$err_msg = "Enter First Name";
		}else if($lname == ""){
			$err_msg = "Enter Last Name";
		}else if($address == ""){
			$err_msg = "Enter Address";
		}else if($city == ""){
			$err_msg = "Enter City";
		}else if($state == ""){
			$err_msg = "Enter State";
		}else if($zip == "" ){
			$err_msg = "Enter Zip";
		}else if($cc_owner ==""){
			$err_msg = "Enter Credit card owner.";
		}else if($cc == "0"){
			$err_msg = "Select Credit Card.";
		}else if((!is_numeric($cc_number) || strlen($cc_number) < 5 ))
		{
			$err_msg = "Enter Credit Number.";
		}else if((!is_numeric($cvvNumber) || strlen($cvvNumber) < 3 ))
		{
			$err_msg = "Enter Credit Card Check Number.";
		}else {

			$cc_validation = new cc_validation();
			$result = $cc_validation->validate($cc_number, $expiry_m, $expiry_y,$cc_owner);

			switch ($result) {
				case 1:

					if (ereg('^4[0-9]{12}([0-9]{3})?$', $cc_number)) {
						$cc_s = 1;
					} elseif (ereg('^5[1-5][0-9]{14}$', $cc_number)) {
						$cc_s = 2;
					} elseif (ereg('^3[47][0-9]{13}$', $cc_number)) {
						$cc_s = 3;
					} elseif (ereg('^6011[0-9]{12}$', $cc_number)) {
						$cc_s = 4;
					}

					if($cc_s == $cc){
								$date = Now();

								$sql = "INSERT INTO ".$tblMembers." (".
								"email".
								",username".
								",package".
								",price".
								",date".
								",fname".
								",lname".
								",address".
								",city".
								",state".
								",zip".
								",country".
								",status".
								") VALUES (".
								" \"$email\"".
								", \"$username\"".
								", \"$package\"".
								", \"$price\"".
								", \"$date\"".
								", \"$fname\"".
								", \"$lname\"".
								", \"$address\"".
								", \"$city\"".
								", \"$state\"".
								", \"$zip\"".
								", \"$country\"".
								", \"false\"".
								")";

								mysql_query($sql) or die(mysql_error());
								$pid = mysql_insert_id();

                                                                if ($userArr['app_id']==0) {
                                                                    $appWizard = new AppWizard($userArr['id']);
                                                                    $userArr['app_id'] = $appWizard->generateAppId($push);
                                                                }

                                                                $sql = "DELETE FROM user_info WHERE user_id={$userArr['id']}";
                                                                $res = $dao->query($sql);

                                                                $sql = "INSERT INTO `user_info` (`user_id`,`artist_name`,
                                                                    `price`,
                                                                    `date`,
                                                                    `first_name`,
                                                                    `last_name`,
                                                                    `address`,`city`,
                                                                    `state`,
                                                                    `county`
                                                                ) VALUES ({$userArr['id']},'','$price','$date','$fname','$lname','$address','$city','$state','$country');";


                                                                $res = $dao->query($sql);

								$_SESSION["pid"] = $pid;

                                                                $ccType = '';
                                                                switch ($cc) {
                                                                    case 1:
                                                                        $ccType = 'Visa';
                                                                        break;
                                                                    case 2:
                                                                        $ccType = 'Master';
                                                                        break;
                                                                    case 3:
                                                                        $ccType = 'American';
                                                                        break;
                                                                    case 4:
                                                                        $ccType = 'Discover';
                                                                        break;
                                                                }

								$_SESSION['cc'] = $ccType;
								$_SESSION['cc_number'] = $cc_number;
								$_SESSION['expiry_m'] = $expiry_m;
								$_SESSION['expiry_y'] = $expiry_y;
								$_SESSION['cvvNumber'] = $cvvNumber;
                                                                $_SESSION['recurrent']= isset($_POST['setupRecurrent']);
                                                                $_SESSION['recurrent_amt']= $recurrentPrice;


								header("Location:../set_recurrent.php?a=1");




					} else {
						$err_msg = "Invalid Credit Card";
					}

				break;

				case -1:
					$err_msg = "This credit card transaction has been declined.";
				break;
				case -2:
				case -3:
				case -4:
					$err_msg = "The credit card has expired.";
				break;
				case -10:
					$err_msg = "Invalid Credit card owner.";
				break;

				case -11:
					$err_msg = "Invalid Credit Card Check Number";
				break;

				case false:
					$err_msg = "Invalid Credit Card Check Number";
				break;
			  }

		}

	break;
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
    <td width="50%" height="764" align="center" valign="top">
		<table width="972" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="top"><div id="naviBig">
            <div id="navigater"> <a href="../index.html" class="naviTab">
              <div>HOME</div>
              </a>
                <div class="naviTabDevider"></div>
              <a href="../aboutus.html" class="naviTab">
                <div>ABOUT</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../tour.html" class="naviTab">
                <div>TOUR</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../faqs.html" class="naviTab">
                <div >FAQ'S</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../pricing.php" class="naviTab_active">
                <div >PRICING</div>
                </a>
                <div class="naviTabDevider"></div>
              <a href="../contact.php" class="naviTab">
                <div >CONTACT</div>
              </a> </div>
        </div>
            <div id="naviBigPrizing">
				<div id="logoHeader"><img src="../images/phizuu.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" width="205" height="71" /></div>
                                <!--<div id="logoHeader2"><img src="../images/sxsw_125.png" alt="Phizuu Logo - connecting your fans to you -  Mobile apps for iPhone, Blackberry & Android" width="83" height="125" /></div> -->
				<div id="signUpBody">
			  <div class="signUpHead1">Quick Signup</div>
              
              <div id="bodyContactRght" style="padding-left:100px; width: 900px">
					<div id="forum_fields_names">
						<div id="name_fld2" style="padding-top: 50px">First Name * </div>
						<div class="text_fld2">Last Name * </div>
                        <div class="text_fld2">Address * </div>
                        <div class="text_fld2">City * </div>
                        <div class="text_fld2">State(valid 2 letter state for U.S.) * </div>
                        <div class="text_fld2">Zip * </div>
                        <div class="text_fld2">Country&nbsp;&nbsp;</div>

                        <div class="text_fld2">Credit Card Owner * </div>
						<div class="text_fld2">Credit Card * </div>
                        <div class="text_field2" style="height: 45px"></div>
						<div class="text_fld2">Credit Card Number * </div>
						<div class="text_fld2">Credit Card Expiry Date * </div>
						<div class="text_fld2">Credit Card Check Number (CVC) * </div>
                                                <div class="text_fld2" style="width: 256px; padding-left: 0px;">Setup recurrent payments ($<?php echo $recurrentPrice;?> p/m) * </div>
					</div>
					<!---->
					<div id="forum_fieldsSignUp">
					<div id="error_field" style="padding-bottom: 18px; font-size: 15px"><?php echo $err_msg ?></div>
                    <div class="text_field">
                    <div class="hider">
					  <input name="fname" type="text" class="removeGlow" id="fname" style="width:275px;" value="<?php echo $fname ?>" />
					</div>
					</div>
					<div class="text_field">
					<div class="hider">
					  <input name="lname" type="text" class="removeGlow" id="lname" style="width:275px; " value="<?php echo $lname?>" />
					</div>
					</div>
                    <div class="text_field">
					<div class="hider">
					  <input name="address" type="text" class="removeGlow" id="address" style="width:275px; " value="<?php echo $address?>" />
					</div>
					</div>
                    <div class="text_field">
					<div class="hider">
					  <input name="city" type="text" class="removeGlow" id="city" style="width:275px; " value="<?php echo $city?>" />
					</div>
					</div>
                    <div class="text_field">
					<div class="hider">
					  <input name="state" type="text" class="removeGlow" id="state" style="width:275px; " value="<?php echo $state?>" />
					</div>
					</div>
                    <div class="text_field">
					<div class="hider">
					  <input name="zip" type="text" class="removeGlow" id="zip" style="width:275px; " value="<?php echo $zip?>" />
					</div>
					</div>
                    <div class="text_field">
					<div class="hider">
					  <select name="country" class="removeGlow">
                      <?php echo get_options("select country_code,countries_name from ".$tblCountries,false,true,$country) ?>
                      </select>
					</div>
					</div>

					<div class="text_field">
					<div class="hider">
					  <input name="cc_owner" type="text" class="removeGlow" id="cc_owner" style="width:275px;" value="<?php echo $cc_owner ?>" />
					</div>
					</div>
					<div class="text_field">
					<div class="hider">
                                            <select name="cc">
                              <?php echo get_options("select autoid,credit_card from ".$tblCreditCards." where status='true' order by autoid Asc",false,true,$cc)?>
                        </select>
					</div>
					</div>

                    <div class="text_field2">
					  <img src="../images/credit_card.png" alt="We accept Visa, Mastercard, American Express & Discover" title="We accept Visa, Mastercard, American Express & Discover" />					</div>

					<div class="text_field">
					<div class="hider">
					  <input name="cc_number" type="text" class="removeGlow" id="cc_number" style="width:275px; " value="<?php echo $cc_number?>" />
					</div>
					</div>
					<div class="text_field">
					  <div class="hider">
					  <select name="expiry_m" class="TxtBorder">
                      	<?php
							for($i=1; $i<=12; $i++)
							{
								$y = date("y")+$k;
								$sel = "";

								if($i == $expiry_m){
									$sel = 'selected="selected"';
								}

								echo('<option value="'.$i.'" '.$sel.'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>');
							}
						?>

                        </select>
                              <select name="expiry_y" class="TxtBorder">
                                <?php
								$k=0;
								$lastyear = date("Y");
								for($i=$lastyear; $i<$lastyear+11; $i++)
								{
									$y = date("y")+$k;
									$sel = "";

									if($i == $expiry_y){
										$sel = 'selected="selected"';
									}

									if(strlen($y)==1)
									{
										$y = "0".$y;
									}
									echo('<option value="'.$i.'" '.$sel.'>'.$i.'</option>');
									$k++;
								}
					?>
                            </select>
					</div>
					</div>
					<div class="text_field">
					<div class="hider">
					  <input name="cvvNumber" type="text" class="removeGlow" id="cvvNumber" style="width:275px;" value="<?php echo $cvvNumber?>" />
					</div>
					</div>
                                        <div class="text_field">
					
					  <input name="setupRecurrent" type="checkbox"  id="setupRecurrent" style="height:20px;width: 20px; margin: 0; padding: 0" value="<?php echo $cvvNumber?>" checked="true"/>
					
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
	</td>
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