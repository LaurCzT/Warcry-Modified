<?php
if (!defined('init_config'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

$config['SiteName'] = 'example';

$config['RootPath'] = dirname(__FILE__) . '/';
$config['BaseURL'] = 'http://www.example.com'; 	//(No slash at the end)

//Must be unique for each website
$config['AuthCookieName'] = 'pwg-wow';

//Minifier Settings
//StyleFolderURL rewrites the URLs for the image in the CSS files
$config['StyleFolderURL'] = 'http://www.example.com/template/style/'; //(With slash at the end)

//E-mail Address
$config['Email'] = 'no-reply@example.com';

//Time settings
$config['TimeZone'] = 'Europe/Berlin';
$config['TimeZoneOffset'] = '+1';

//example Database URL
$config['WoWDB_URL'] =  'http://www.wowhead.com';
//Complete URL to the power.js
$config['WoWDB_JS'] = '//wowjs.zamimg.com/widgets/power.js?1412273528';
