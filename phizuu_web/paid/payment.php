<?php
session_start();
require_once '../cms/apps/config/config.php';
require_once '../cms/apps/database/Dao.php';
require_once '../cms/apps/model/app_builder/AppWizard.php';
require_once '../cms/apps/model/app_builder/Navigator.php';
require_once '../cms/apps/model/paypal/PayPal.php';
require_once '../cms/apps/common/Country.php';
require_once '../cms/apps/common/phpcreditcard.php';
require_once '../constants_new.php';
require_once '../CallerService.php';
require_once '../cms/apps/common/Country.php';
require_once ("../admin/common/common.php");

$dao = new Dao();
$sql = "SELECT * FROM `user` WHERE `username` = '{$_SESSION['sign_up_username']}'";
$res = $dao->query($sql);
$userArr = $dao->getArray($res);
if (count($userArr) > 0) {
    header("Location: pricing.html");
}

check_HTTPS();
//deleteAbandonedMembers();
$err_msg = "";
$email = $_SESSION['sign_up_email'];
$package_id = $_SESSION['sign_up_package_id'];
$package = $_SESSION['sign_up_package_name'];
$price = $_SESSION['sign_up_package_price'];
$password = $_SESSION['sign_up_password'];
$recurrentPrice = $_SESSION['sign_up_package_recurrent_price']; //Edit to enable recurrent payment setup

$countries = Country::getCountryArray();

if (isset($_POST['action']) && $_POST['action'] == 'take_payment') {
    $freePackageId = 1;

    $username = trim($_SESSION['sign_up_username']);
    $fname = trim($_REQUEST["firstName"]);
    $lname = trim($_REQUEST["lastName"]);
    $address = trim($_REQUEST["address"]);
    $city = trim($_REQUEST["city"]);
    $state = trim($_REQUEST["state"]);
    $zip = trim($_REQUEST["zip"]);
    $country = $_REQUEST["country"];
    $cc_owner = trim($_REQUEST["cc_owner"]);
    $cc = trim($_REQUEST["cardType"]);
    $cc_number = trim($_REQUEST["cardNo"]);
    $expiry_m = $_REQUEST["cardExpM"];
    $expiry_y = trim($_REQUEST["cardExpY"]);
    $cvvNumber = trim($_REQUEST["cardCVV"]);
    $setRecurrent = isset($_REQUEST['setupRecurrent']) ? true : false;
    /*
      $_SESSION['cc'] = $ccType;
      $_SESSION['cc_number'] = $cc_number;
      $_SESSION['expiry_m'] = $expiry_m;
      $_SESSION['expiry_y'] = $expiry_y;
      $_SESSION['cvvNumber'] = $cvvNumber;
      $_SESSION['recurrent']= isset($_POST['setupRecurrent']);
      $_SESSION['recurrent_amt']= $recurrentPrice;

      header("Location:../set_recurrent.php?a=1");
     */
    $paypal = new PayPal();
    try {
        $paypal->takePayment($price, $_POST, "Payment for content update request for $username", $email, "Sandbox", true);

        include '../set_recurrent.php';
        exit;
    } catch (Exception $e) {
        $err_msg = $e->getMessage();
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="Description" content="phizuu:connect is a mobile application platform targeted towards the music industry. Its main focus is to give musicians the opportunity to make an intimate connection with their fans around the globe."/>
        <meta name="keywords" content="phizuu, phizuu connect, phizzu:connect, iphone, iphone app, iphone apps,	 blackberry, android, apps, app(s), music, artist, musician, dj, band"/>
        <title>Sign up for phizuu: iPhone apps for Musicians, Artists, Bands, DJ's, Comedians...</title>
        <link href="../css/styles.css" rel="stylesheet" type="text/css" />
        <link href="../css/bodyAboutUs.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript">
        function disableSubmit(elem) {
            elem.disbaled=true;
            elem.style['display'] = 'none';
            document.getElementById('disabledButton').style['display'] = 'block';
        }
        </script>
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
                                                    <div class="text_fld2" style="width: 256px; padding-left: 0px;">Setup recurrent payments ($<?php echo $recurrentPrice; ?> p/m) * </div>
                                                </div>
                                                <!---->
                                                <div id="forum_fieldsSignUp">
                                                    <div id="error_field" style="padding-bottom: 18px; font-size: 15px"><?php echo $err_msg ?></div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="firstName" type="text" class="removeGlow" id="firstName" style="width:275px;" value="<?php echo isset($fname) ? $fname : '' ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="lastName" type="text" class="removeGlow" id="lastName" style="width:275px; " value="<?php echo isset($lname) ? $lname : '' ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="address" type="text" class="removeGlow" id="address" style="width:275px; " value="<?php echo isset($address) ? $address : '' ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="city" type="text" class="removeGlow" id="city" style="width:275px; " value="<?php echo isset($city) ? $city : '' ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="state" type="text" class="removeGlow" id="state" style="width:275px; " value="<?php echo isset($state) ? $state : '' ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="zip" type="text" class="removeGlow" id="zip" style="width:275px; " value="<?php echo isset($zip) ? $zip : '' ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <select name="country" class="removeGlow">
                                                                <?php
                                                                if (isset($_POST['country']) && $_POST['country'] != '') {
                                                                    $countryCodePrev = $_POST['country'];
                                                                } else {
                                                                    $countryCodePrev = '';
                                                                    echo('<option value="" selected="selected">Please Select</option>');
                                                                }

                                                                foreach ($countries as $countryCode => $countryName) {
                                                                    if ($countryCodePrev == $countryCode) {
                                                                        $sel = 'selected="selected"';
                                                                    } else {
                                                                        $sel = '';
                                                                    }
                                                                    echo('<option value="' . $countryCode . '" ' . $sel . '>' . $countryName . '</option>');
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="cc_owner" type="text" class="removeGlow" id="cc_owner" style="width:275px;" value="<?php echo isset($cc_owner) ? $cc_owner : ''; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <select name="cardType">
                                                                <?php
                                                                if (!isset($_POST['cardType']))
                                                                    $cardType = '';
                                                                else
                                                                    $cardType = $_POST['cardType'];
                                                                ?>
                                                                <option value="Visa" <?php echo $cardType == 'Visa' ? 'selected' : '' ?>>Visa</option>
                                                                <option value="MasterCard" <?php echo $cardType == 'MasterCard' ? 'selected' : '' ?>>MasterCard</option>
                                                                <option value="American Express" <?php echo $cardType == 'American Express' ? 'selected' : '' ?>>American Express</option>
                                                                <option value="Discover" <?php echo $cardType == 'Discover' ? 'selected' : '' ?>>Discover</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="text_field2">
                                                        <img src="../images/credit_card.png" alt="We accept Visa, Mastercard, American Express & Discover" title="We accept Visa, Mastercard, American Express & Discover" />					</div>

                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="cardNo" type="text" class="removeGlow" id="cc_number" style="width:275px; " value="<?php echo isset($cc_number) ? $cc_number : ''; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <select name="cardExpM" class="TxtBorder">
                                                                <?php
                                                                $k = 0;
                                                                for ($i = 1; $i <= 12; $i++) {
                                                                    $y = date("y") + $k;
                                                                    $sel = "";

                                                                    if (isset($expiry_m) && $i == $expiry_m) {
                                                                        $sel = 'selected="selected"';
                                                                    }

                                                                    echo('<option value="' . $i . '" ' . $sel . '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>');
                                                                }
                                                                ?>

                                                            </select>
                                                            <select name="cardExpY" class="TxtBorder">
                                                                <?php
                                                                $k = 0;
                                                                $lastyear = date("Y");
                                                                for ($i = $lastyear; $i < $lastyear + 11; $i++) {
                                                                    $y = date("y") + $k;
                                                                    $sel = "";

                                                                    if (isset($expiry_y) && $i == $expiry_y) {
                                                                        $sel = 'selected="selected"';
                                                                    }

                                                                    if (strlen($y) == 1) {
                                                                        $y = "0" . $y;
                                                                    }
                                                                    echo('<option value="' . $i . '" ' . $sel . '>' . $i . '</option>');
                                                                    $k++;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="text_field">
                                                        <div class="hider">
                                                            <input name="cardCVV" type="text" class="removeGlow" id="cvvNumber" style="width:275px;" value="<?php echo isset($cvvNumber) ? $cvvNumber : ''; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="text_field">

                                                        <input name="setupRecurrent" type="checkbox"  id="setupRecurrent" style="height:20px;width: 20px; margin: 0; padding: 0" value="recurrent" <?php echo isset($setRecurrent) && $setRecurrent == 1 ? 'checked="true"' : 'checked="false"' ?>/>

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
                                        <div class="button"> <input type="image" src="../images/submit.png" onclick="javascript: return disableSubmit(this);"/><img id="disabledButton" style="display:none" src="../images/submit_dis.png"/><input type="hidden" name="action" value="take_payment" /> </div>
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