<?php
//.... Site Path....
$site_main_folder_path="phizuu_web/cms";
//online- phizuu
//local - PHIZZU1

//.... End Site Path....


//.... Database ......

// Database configuratios

/*
define ('CONF_DATABASE_USERNAME', 'phizuu_analyty'); // Database user
define ('CONF_DATABASE_PASSWORD', 'analyty'); // Database password
define ('CONF_DATABASE_HOST', 'localhost'); // Database host
define ('CONF_DATABASE_NAME', 'phizuu_analytics'); // Database name
// Log file configuration
define ('CONF_LOG_FILE_PATH','/home/phizuu/RELEASE/logs/connect/access.log'); //Please enter full path to the log file
*/
//Test server details
define ('CONF_DATABASE_USERNAME', 'phizuu_cms'); // Database user
define ('CONF_DATABASE_PASSWORD', 'phizuu'); // Database password
define ('CONF_DATABASE_HOST', 'localhost'); // Database host
define ('CONF_DATABASE_NAME', 'phizuu_analytics'); // Database name
//Localhost server details
//define ('CONF_DATABASE_USERNAME', 'root'); // Database user
//define ('CONF_DATABASE_PASSWORD', 'root'); // Database password
//define ('CONF_DATABASE_HOST', 'localhost'); // Database host
//define ('CONF_DATABASE_NAME', 'phizuu_analytics'); // Database name

// Log file configuration
define ('CONF_LOG_FILE_PATH','configure/access.log'); //Please enter full path to the log file
define ('STATIC_API_PATH','../../../static-api/');


define ('SOUNDCOULD_CONSUMER_KEY', 'r7Ldd4KYA5KnzbGlbRLg');
define ('SOUNDCOULD_CONSUMER_SECRET', 'gfuRZwAbD4b9ervUje9G7TMSSkw7dVtsm1EY3lTXqU');

define ('SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN', '6O0QdDQgSHvOVmzggF0A');
define ('SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN_SECRET', 'CbHFDTce7WxUAm8QFpl86uoePptR8Aiy8MSjkfpz9Y');

$host=CONF_DATABASE_HOST;
$usna=CONF_DATABASE_USERNAME;
$pwd=CONF_DATABASE_PASSWORD;
$db=CONF_DATABASE_NAME;

//local host - 'localhost', 'root', '', 'phizuu_music'
//test server - 'phizuu.pyxle.info', 'phizuu', 'phizuu123', 'phizuudb'

//..... End Database ......


//...... Settings......

//settings types

$_ENV['setting_youtube']='1';//youtube
$_ENV['setting_rssfeed']='2';//rss feed
$_ENV['setting_flickr']='3';//Flickr
$_ENV['setting_twiter']='4';//twiter
$_ENV['myspace_url']='6';//twiter

//...... End Settings......

//...... Keys for API's.....
//Flickr

$_ENV['flickr_key'] = '8ac1a5c76dc896636d00dac5210f1b00';//'c23765bd9ed09cd4cba783370cbffed9';
$_ENV['shared_secret'] = '94541ae811f95c53';//'3458b185036ae247';


//Box.net
$_ENV['box_key']='5o5xoy1fkyp59263jd1ap30zf2xuvn9k';

//...... End Keys for API's.....


//...... Emails......

//forgot password
$forgot_pwd_email_from="info@phizzu.com";

//...... End Emails......

/*
This file holds the configurations of the system
*/

// Database configuratios
/*
define ('CONF_DATABASE_USERNAME', 'phizuu_analyty'); // Database user
define ('CONF_DATABASE_PASSWORD', 'analyty'); // Database password
define ('CONF_DATABASE_HOST', 'localhost'); // Database host
define ('CONF_DATABASE_NAME', 'phizuu_analytics'); // Database name
// Log file configuration
define ('CONF_LOG_FILE_PATH','/home/phizuu/RELEASE/logs/connect/access.log'); //Please enter full path to the log file
*/


?>