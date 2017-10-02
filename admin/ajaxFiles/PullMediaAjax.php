<?php
if (!defined('init_ajax'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

if (!$CURUSER->isOnline())
{
    echo json_encode(array('error' => 'You must be logged in.'));
    die;
}

//check for permissions
if (!$CURUSER->getPermissions()->isAllowed(PERMISSION_MEDIA_MOVIES))
{
    echo json_encode(array('error' => 'You dont have the required permissions.'));
    die;
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */

$id = (isset($_GET['id']) ? $_GET['id'] : false);

if(!$id) {
    echo json_encode(array('error' => 'Id is not set.'));
    die;
}

global $DB;
$res = $DB->prepare('SELECT * FROM `movies` WHERE id=:id;');
$res->bindParam(':id', $id, PDO::PARAM_INT);
$res->execute();

if($res->rowCount() > 0) {
    $output = $res->fetch();
} else {
    $output = ['error' => 'Could not find the movie with that id.'];
}


echo json_encode( $output );
?>