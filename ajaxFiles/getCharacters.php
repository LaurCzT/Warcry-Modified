<?php

if (!defined('init_ajax'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

$ARMORY = $CORE->load_ServerModule('armory');
$armory = new Armory();

if(isset($_GET['name']) == true && isset($_GET['realm']) == true)
{
    $name = htmlspecialchars($_GET['name']);
    $realm = htmlspecialchars($_GET['realm']);

    if($response = $armory->searchCharacters($name, $realm)) {
        if($response == 'false') return print(json_encode('false'));
        return print(json_encode($response));
    }
}