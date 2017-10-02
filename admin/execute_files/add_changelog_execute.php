<?PHP
if (!defined('init_executes'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

$CORE->loggedInOrReturn();

//check for permissions
$CORE->CheckPermissionsExecute(PERMISSION_CHANGELOGS);

//prepare multi errors

//bind on success
//$ERRORS->onSuccess('The changelog was successfully added.', '/index.php?page=changelog');

$revision = (isset($_POST['revision']) ? $_POST['revision'] : false);
$changelog = (isset($_POST['changelog']) ? $_POST['changelog'] : false);
$text = (isset($_POST['text']) ? $_POST['text'] : false);
$author = (isset($_POST['author']) ? $_POST['author'] : false);
$date = date('Y-m-d h:i:s');

if (!$revision)
{
    $ERRORS->Add("Please enter a changelog title.");
}
if (!$changelog)
{
    $ERRORS->Add("Please enter a changelog description.");
}
if (!$text)
{
    $ERRORS->Add("Please enter a changelog category.");
}

if (!$author)
{
    $ERRORS->Add("Please enter a changelog position.");
}

//insert the news record
$insert = $DB->prepare("INSERT INTO `changelogs` (`revision`, `changelog`, `text`, `author`, `time`) VALUES (:revision, :changelog, :text, :author, '".$date."');");
$insert->bindParam(':revision', $revision, PDO::PARAM_STR);
$insert->bindParam(':changelog', $changelog, PDO::PARAM_INT);
$insert->bindParam(':text', $text, PDO::PARAM_INT);
$insert->bindParam(':author', $author, PDO::PARAM_INT);
$insert->execute();

if ($insert->rowCount() < 1)
{
    $ERRORS->Add("The website failed to insert the changelog record.");
}
else
{
    header('Location: ./index.php?page=changelogs');
}

unset($insert);

####################################################################
exit;