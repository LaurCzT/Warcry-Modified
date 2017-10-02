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
if (!$CURUSER->getPermissions()->isAllowed(PERMISSION_MEDIA_MOVIES))
{
    echo 'You do not have the required permissions.';
    die;
}

$id = (isset($_POST['id']) ? (int)$_POST['id'] : false);
$descr = (isset($_POST['descr']) ? $_POST['descr'] : false);
$image = (isset($_POST['image']) ? $_POST['image'] : false);
$short_text = (isset($_POST['short_text']) ? $_POST['short_text'] : false);
$name = (isset($_POST['name']) ? $_POST['name'] : false);



if (!$id)
{
    echo 'Id is missing.';
    die;
}

if (!$descr)
{
    echo 'Please enter description.';
    die;
}

if (!$image)
{
    echo 'Image is missing.';
    die;
}

if (!$short_text)
{
    echo 'Text is missing.';
    die;
}

if (!$name)
{
    echo 'Name is missing.';
    die;
}


//check if the news record exists
$res = $DB->prepare("SELECT `id` FROM `movies` WHERE `id` = :id LIMIT 1;");
$res->bindParam(':id', $id, PDO::PARAM_INT);
$res->execute();

if ($res->rowCount() == 0)
{
    echo 'The media record is missing.';
    die;
}
else
{
    $row = $res->fetch();
}
unset($res);

//insert the news record
$update = $DB->prepare("UPDATE `movies` SET `descr` = :descr, `short_text`=:short_text, `name`=:name, `image`=:image WHERE `id` = :id LIMIT 1;");
$update->bindParam(':id', $id, PDO::PARAM_INT);
$update->bindParam(':descr', $descr, PDO::PARAM_STR);
$update->bindParam(':name', $name, PDO::PARAM_STR);
$update->bindParam(':image', $image, PDO::PARAM_STR);
$update->bindParam(':short_text', $short_text, PDO::PARAM_STR);
$update->execute();

if ($update->rowCount() < 1)
{
    echo 'The website failed to update the forum record.';
    die;
}
else
{
    header('Location: index.php?page=media');
}
unset($update);

####################################################################

exit;