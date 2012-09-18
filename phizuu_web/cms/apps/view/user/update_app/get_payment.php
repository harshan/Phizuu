<?php
$menu_item = "payments";

require_once("../../../controller/session_controller.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


</script>

<style type="text/css">
.bodyRow {
    width: 100%;
    float: left;
    min-height: 20px;
    overflow: hidden;
    font-family: Tahoma;
    color: #1e1f1f;
    font-size: 12px;
    padding-bottom: 4px;
}

.fldName {
    width: 150px;
    float: left;
    height: 25px;
    padding-left: 5px;
    padding-right: 5px;
    
}

.fldValue {
    width: 750px;
    float: left;
    height: 25px;
}

.textField {
    width: 300px;
    height: 12px;
    padding: 4px;
}

</style>

</head>


<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png')">
 <div id="header">
        <div id="headerContent">
            <?php include("../../../view/user/common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
  <div id="mainWideDiv">
  <div id="middleDiv2">
      <form action="../../../controller/modules/update_app/UpdateAppController.php?action=send_request" method="POST">
           
            <?php include("../../../view/user/common/navigator.php");?>
          <div class="bodyRow">&nbsp;</div>

          <div class="bodyRow">
              <div id="lightBlueHeader" style="width: 100%">
                  
                  <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 930px">Setup Recurrent Payments</div>
                  
              </div>
          </div>
          
          <?php
          if(isset($msg) && $msg!='') {
          ?>
          <div class="bodyRow" style="padding-left: 5px; color: red; font-weight: bold"><?php echo $msg; ?></div>
          <?php
          }
          ?>
          <?php
          if(isset($msgSuccess) && $msgSuccess!='') {
          ?>
          <div class="bodyRow" style="padding-left: 5px; font-weight: bold"><?php echo $msgSuccess; ?></div>
          <div class="bodyRow" style="padding-left: 5px; font-weight: bold"><br/>Thank you for the request! We will get back to you soon..</div>
          <?php
          }
          ?>
          <?php if(!isset($msgSuccess)) { ?>
          <div class="bodyRow">
              <div class="fldName">First Name: </div>
              <div class="fldValue">
                  <input name="firstName" class="textFeildBoarder textField" value="<?php echo isset($_POST['firstName'])?$_POST['firstName']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName" >Last Name: </div>
              <div class="fldValue">
                  <input name="lastName" class="textFeildBoarder textField" value="<?php echo isset($_POST['lastName'])?$_POST['lastName']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName">Credit Card Type: </div>
              <div class="fldValue">
                  <select name="cardType" style="width: 130px; height: 16px">
                      <?php
                      if(!isset ($_POST['cardType']))
                          $cardType = '';
                      else
                          $cardType = $_POST['cardType'];
                      ?>
                      <option value="Visa" <?php echo $cardType=='Visa'?'selected':'' ?>>Visa</option>
                      <option value="MasterCard" <?php echo $cardType=='MasterCard'?'selected':'' ?>>MasterCard</option>
                      <option value="American Express" <?php echo $cardType=='American Express'?'selected':'' ?>>American Express</option>
                      <option value="Discover" <?php echo $cardType=='Discover'?'selected':'' ?>>Discover</option>
                  </select>
              </div>
          </div>
          <div class="bodyRow" style="height: 45px">
              <div class="fldName"></div>
              <div class="fldValue">
                  <img src="../../../images/credit_card.png"/>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName">Credit Card No: </div>
              <div class="fldValue" >
                  <input name="cardNo" class="textFeildBoarder textField" style="width: 214px" value="<?php echo isset($_POST['cardNo'])?$_POST['cardNo']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName">Credit Card Expiry Date:</div>
              <div class="fldValue">
                    <select name="cardExpM" class="TxtBorder">
                    <?php
                    $k = 0;
                    for($i=1; $i<=12; $i++) {
                        $y = date("y")+$k;
                        $sel = "";

                        if(isset($_POST['cardExpM']) && ($i == $_POST['cardExpM'])) {
                            $sel = 'selected="selected"';
                        }

                        echo('<option value="'.$i.'" '.$sel.'>'.str_pad($i, 2, '0', STR_PAD_LEFT).'</option>');
                    }
                    ?>
                    </select>
                    <select name="cardExpY" class="TxtBorder">
                        <?php
                        $k=0;
                        $lastyear = date("Y");
                        for($i=$lastyear; $i<$lastyear+11; $i++) {
                            $y = date("y")+$k;
                            $sel = "";

                            if(isset($_POST['cardExpY']) && ($i == $_POST['cardExpY'])) {
                                $sel = 'selected="selected"';
                            }

                            if(strlen($y)==1) {
                                $y = "0".$y;
                            }
                            echo('<option value="'.$i.'" '.$sel.'>'.$i.'</option>');
                            $k++;
                        }
                        ?>
                    </select>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName" >Check Number (CVC): </div>
              <div class="fldValue" >
                  <input name="cardCVV" class="textFeildBoarder textField" style="width: 50px" value="<?php echo isset($_POST['cardCVV'])?$_POST['cardCVV']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName" >Address: </div>
              <div class="fldValue" >
                  <input name="address" class="textFeildBoarder textField" style="width: 500px" value="<?php echo isset($_POST['address'])?$_POST['address']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName" >City: </div>
              <div class="fldValue" >
                  <input name="city" class="textFeildBoarder textField" style="width: 200px" value="<?php echo isset($_POST['city'])?$_POST['city']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName" >State: </div>
              <div class="fldValue" >
                  <input name="state" class="textFeildBoarder textField" style="width: 200px" value="<?php echo isset($_POST['state'])?$_POST['state']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName" >Zip: </div>
              <div class="fldValue" >
                  <input name="zip" class="textFeildBoarder textField" style="width: 100px" value="<?php echo isset($_POST['zip'])?$_POST['zip']:''; ?>"></input>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName" >Country: </div>
              <div class="fldValue" >
                  <select name="country">
                      <?php
                      if (isset ($_POST['country']) && $_POST['country']!='') {
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
                          echo('<option value="'.$countryCode.'" '.$sel.'>'.$countryName.'</option>');
                      }
                      ?>
                  </select>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName">Amount: </div>
              <div class="fldValue" >
                  <b>$49 USD</b>
              </div>
          </div>
          <div class="bodyRow">
              <div class="fldName"></div>
              <div class="fldValue" >
                  <input type="image" src="../../../images/payAndSendRequest.png"/>
              </div>
          </div>
          <?php }?>
          <div class="bodyRow"></div>
      </form>
  </div>
</div>

     <br class="clear"/> 
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
    

</body>
</html>