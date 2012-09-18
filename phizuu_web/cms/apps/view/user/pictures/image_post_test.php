<?php
echo "<pre>";
require_once ('../../../common/facebook.php');

$facebook = new Facebook(array(
  'appId'  => '301675082439',
  'secret' => '4d4a7d398663bf166488071d46e71642',
  'cookie' => true,
));

$me = $facebook->api('/me');
print_r($me);
$accessToken = $facebook->getAccessToken();
echo($accessToken);


$test = $facebook->api('me/albums','POST',array('name'=>'Test Album 3','message'=>'Uploaded by phizuu'));
$url = "https://graph.facebook.com/{$test['id']}/photos";

$lineFeed = "\r\n";
$headers = array("Content-type: multipart/form-data; boundary=---------------daAKdfkfsdkKdf8s");

//First Section
$data = $lineFeed . "-----------------daAKdfkfsdkKdf8s" . $lineFeed;
$data .= "Content-Disposition: form-data; name=\"message\"" . $lineFeed . $lineFeed;
$data .= 'My File' . $lineFeed; //Data
$data .= "-----------------daAKdfkfsdkKdf8s". $lineFeed;

//Second Section
$data .= "Content-Disposition: form-data; name=\"access_token\"" . $lineFeed . $lineFeed;
$data .= $accessToken . $lineFeed; //Data
$data .= "-----------------daAKdfkfsdkKdf8s". $lineFeed;

//Third Section
$data .= "Content-Disposition: form-data; name=\"source\"; filename=\"fanphoto.jpg\"" . $lineFeed;
$data .= "Content-Type: application/octet-stream" . $lineFeed . $lineFeed;
$fileContent = file_get_contents("http://localhost/phizuu_web/static_files/13/images/album_cover/12.jpg");
$data .= $fileContent . $lineFeed; //Data
$data .= "-----------------daAKdfkfsdkKdf8s". $lineFeed;

//Sending Data
$ch = curl_init(); // initialize curl handle

curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0); // set url to post to
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2); // set url to post to
curl_setopt($ch, CURLOPT_FAILONERROR, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 20s
curl_setopt($ch, CURLOPT_POST, 1); // set POST method
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($ch); // run the whole process
echo "<pre>";
echo $result;
echo "</pre>";
curl_close($ch);

echo "</pre>";

?>
