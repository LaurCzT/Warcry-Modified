<?PHP
if (!defined('init_executes'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

$CORE->loggedInOrReturn();

//check for permissions
$CORE->CheckPermissionsExecute(PERMISSION_FORUMS);

//prepare multi errors

//bind on success
//$ERRORS->onSuccess('The forum was successfully added.', '/index.php?page=forum');

$name = (isset($_POST['name']) ? $_POST['name'] : false);
$description = (isset($_POST['description']) ? $_POST['description'] : false);
$category = (isset($_POST['category']) ? $_POST['category'] : false);
$position = (isset($_POST['position']) ? $_POST['position'] : false);

if (!$name)
{
    $ERRORS->Add("Please enter a forum title.");
}
if (!$description)
{
    $ERRORS->Add("Please enter a forum description.");
}
if (!$category)
{
    $ERRORS->Add("Please enter a forum category.");
}

if (!$position)
{
    $ERRORS->Add("Please enter a forum position.");
}

//insert the news record
$insert = $DB->prepare("INSERT INTO `wcf_forums` (`name`, `description`, `category`, `position`) VALUES (:name, :description, :category, :position);");
$insert->bindParam(':name', $name, PDO::PARAM_STR);
$insert->bindParam(':description', $description, PDO::PARAM_INT);
$insert->bindParam(':category', $category, PDO::PARAM_INT);
$insert->bindParam(':position', $position, PDO::PARAM_INT);
$insert->execute();

if ($insert->rowCount() < 1)
{
    $ERRORS->Add("The website failed to insert the forum record.");
}
else
{
    header('Location: ./index.php?page=forums');
}

unset($insert);

####################################################################
exit;