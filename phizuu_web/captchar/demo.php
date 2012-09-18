<?php
session_start();	//Captcha data is stored in the session; we have to start it here to access it later on.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Captchar Demo</title>
	<style type="text/css">
		body { width: 600px; margin: 0 auto; font-family: arial, sans-serif;}
	</style>
</head>

<body>

<h1>Captchar Demo</h1>
<a href="./">Go back</a> | <a href="./demo.php.txt">Download this demo</a>

<?php
//Begin captcha validation
if($_POST) {	//check to see if a form has been submitted
	//We're making each string upper case so that the input is case-insensitive
	if(strtoupper($_POST['captcha']) == strtoupper($_SESSION['captcha'])) {	//Check the user's input
		echo "Valid input";	//Put your code for valid input here
	}
	else {
		echo "Invalid input";	//Invalid input code
	}
	unset($_SESSION['captcha']);	//Reset the captcha -- this is important
}
?>

<form action="?" method="post">
	<img src="captcha.php" alt = "Captchar" /><br />
	<input type="text" name="captcha" />
	<input type="submit" value="Submit" />
</form>

</body>

</html>