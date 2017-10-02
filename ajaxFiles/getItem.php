<?PHP
if (!defined('init_ajax'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

$RealmId = $CURUSER->GetRealm();
$entry = ((isset($_GET['entry'])) ? (int)$_GET['entry'] : false);

function PullData($entry)
{
	global $CORE, $DB;

	$response = $CORE->getRemotePage(array(
		'host'	=> 'www.wowhead.com',
		'port'	=> 80,
		'page'	=> '/item='.$entry.'&xml'
	));

    $xml = simplexml_load_string($response["body"]);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);


    $query = $DB->prepare('SELECT name, subclass, InventoryType FROM `store_items` WHERE entry=:entry;');
    $query->bindParam(':entry', $entry, PDO::PARAM_INT);
    $query->execute();

    if($query->rowCount() > 0) {
        $dbarray = $query->fetch();
    }

    $data = [];
    $data["icon"] = $array["item"]["icon"];
    $data["quality"] = $array["item"]["quality"];
    $data["inventory"]  = $dbarray["InventoryType"];
    $data["name"] = $dbarray["name"];
    $data["subclass"]   = $dbarray["subclass"];
    return $data;

}

if (!($data = $CACHE->get('world/items/item_store_data_' . $RealmId . '_' . $entry)))
{
    $data = PullData($entry);
	
    //Cache server status for 30 seconds
    $CACHE->store('world/items/item_store_data_' . $RealmId . '_' . $entry, $data, strtotime('+1 month', 0));
}

header('Content-Type: application/json');
echo json_encode($data);
