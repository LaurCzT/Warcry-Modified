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
if (!$CURUSER->getPermissions()->isAllowed(PERMISSION_FORUMS))
{
    echo 'You do not have the required permissions.';
    die;
}

$id = (isset($_POST['id']) ? (int)$_POST['id'] : false);
$description = (isset($_POST['description']) ? $_POST['description'] : false);



if (!$id)
{
    echo 'Category id is missing.';
    die;
}

if (!$description)
{
    echo 'Please enter description name.';
    die;
}


//check if the news record exists
$res = $DB->prepare("SELECT `id`, `name`, `description` FROM `wcf_forums` WHERE `id` = :id LIMIT 1;");
$res->bindParam(':id', $id, PDO::PARAM_INT);
$res->execute();

if ($res->rowCount() == 0)
{
    echo 'The forum record is missing.';
    die;
}
else
{
    $row = $res->fetch();
}
unset($res);

//insert the news record
$update = $DB->prepare("UPDATE `wcf_forums` SET `description` = :description WHERE `id` = :id LIMIT 1;");
$update->bindParam(':id', $id, PDO::PARAM_INT);
$update->bindParam(':description', $description, PDO::PARAM_INT);
$update->execute();

if ($update->rowCount() < 1)
{
    echo 'The website failed to update the forum record.';
    die;
}
else
{
    echo 'OK';
}
unset($update);

####################################################################

exit;