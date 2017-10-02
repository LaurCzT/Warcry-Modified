<?PHP
if (!defined('init_executes'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

if (!$CURUSER->isOnline())
{
    echo 'Must be logged in.';
    die;
}

//check for permissions
if (!$CURUSER->getPermissions()->isAllowed(PERMISSION_CHANGELOGS))
{
    echo 'You do not have the required permissions.';
    die;
}

$id = (isset($_POST['id']) ? (int)$_POST['id'] : false);
$text = (isset($_POST['text']) ? $_POST['text'] : false);



if (!$id)
{
    echo 'Category id is missing.';
    die;
}

if (!$text)
{
    echo 'Please enter text.';
    die;
}


//check if the news record exists
$res = $DB->prepare("SELECT `id` FROM `changelogs` WHERE `id` = :id LIMIT 1;");
$res->bindParam(':id', $id, PDO::PARAM_INT);
$res->execute();

if ($res->rowCount() == 0)
{
    echo 'The changelog record is missing.';
    die;
}
else
{
    $row = $res->fetch();
}
unset($res);

//insert the news record
$update = $DB->prepare("UPDATE `changelogs` SET `text` = :text WHERE `id` = :id LIMIT 1;");
$update->bindParam(':id', $id, PDO::PARAM_INT);
$update->bindParam(':text', $text, PDO::PARAM_INT);
$update->execute();

if ($update->rowCount() < 1)
{
    echo 'The website failed to update the changelog record.';
    die;
}
else
{
    echo 'OK';
}
unset($update);

####################################################################

exit;