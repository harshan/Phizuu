<?php
// Make sure that your callback URL is properly configured.


// Set your API Key.

$api_key = $_ENV['box_key'];
$_SESSION['api_key']=$api_key;
 
// Configure your auth_token retrieval

$auth_token = '';
include 'boxlibphp5.php';

$box =& new boxclient($api_key, $auth_token);


// Get Ticket to Proceed

$ticket_return = $box->getTicket ();

if ($box->isError()) {
 $box->getErrorMsg();
} else {

$ticket = $ticket_return['ticket'];

}		
// If no auth_token has been previously stored, user must login.

if ($ticket && ($auth_token == '') && (isset($_REQUEST['auth_token']))) {

$auth_token = $_REQUEST['auth_token'];
$_SESSION['auth_token']=$auth_token;
$_SESSION['api_key']=$api_key;

}elseif ($ticket && ($auth_token == '')) {


$box->getAuthToken($ticket);


}else{
}


$box =& new boxclient($api_key, $auth_token);
 ?>	 