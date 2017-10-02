<?php
if (!defined('init_config'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

//Website Database Connection Info
$server_config['CORE'] = 'trinity';

//Realms configuration
$realms_config[1] = array(
	'name' 			=> 'Realmname',
	'descr' 		=> 'Blizzlike High-Rate', 	
	'Database' 		=> array(
		'host' 		=> 'localhost', 
		'name' 		=> 'wchar',
		'user' 		=> 'root', 
		'pass' 		=> 'passwordhere',
		'encoding' 	=> 'utf8'
	), 
	'address' 		=> '127.0.0.1',
	'port' 			=> '8085',
	'soap_protocol' => 'http',
	'soap_address'  => '127.0.0.1',
	'soap_port'     => '7878',
	'soap_user'     => 'server',
	'soap_pass'     => 'password',
	'UPDATE_TIME' 	=> '10 minutes',
);