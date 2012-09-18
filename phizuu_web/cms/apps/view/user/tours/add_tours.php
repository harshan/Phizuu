<?php
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	echo "strt ".$starting;
	$starting=$_GET['starting'];
}else{
	$starting=0;
}?>

 <script src="src/js/jscal2.js"></script>
    <script src="src/js/lang/en.js"></script>
    <link rel="stylesheet" type="text/css" href="src/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="src/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="src/css/steel/steel.css" />
<form id="addTours" name="addTours" method="get" onSubmit="showHint(this)">
<table width="200" border="1">
  <tr>
    <td>Name</td>
    <td><input type="text" name="name" id="name" /><?php if(isset($_REQUEST['msg_error'])){echo $msg_error;}?></td>
  </tr>
  <tr>
    <td>Date</td>
    <td>

        <input type="Text" id="date" maxlength="25" size="25"><img src="../../../images/cal.gif" id="f_btn1"  onclick="calendar();" onmouseover="calendar();" />
    </td>
  </tr>
   <tr>
    <td>location</td>
    <td><textarea name="location1" rows="5" id="location1"></textarea></td>
  </tr>
  <tr>
    <td>Notes</td>
    <td><textarea name="notes" rows="5" id="notes"></textarea></td>
  </tr>
    <tr>
    <td>
      <input type="submit" name="button" id="button" value="Submit" /></td>
    <td><input type="reset" name="button2" id="button2" value="Reset" />
    <input type="hidden" name="status" id="status" value="add" />
    <input type="hidden" name="starting" id="starting" value="<?php echo $starting;?>" />
     
    </td>
  </tr>
</table>
</form>

