<?php

if (!defined('init_engine'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

include_once __DIR__.'\character.php';

class Armory  {
    public $db, $authDB, $char, $charData;
    private $realm, $name;

    public function __construct() {
        global $DB, $AUTH_DB, $CHAR_DB;

        $this->authDB = $AUTH_DB;
        $this->charDB = $CHAR_DB;
        $this->char = new server_Character();
    }

    public function getRealms() {
        $res = $this->authDB->prepare("SELECT id, name FROM `realmlist`;");
        $res->execute();

        return $res->rowCount() > 0 ? $res->fetchAll() : false;
    }

    public function getData($name, $realm) {
        $this->charData = $this->char->getCharacterDataByName($name, $realm);

        $data = [
            "level" => $this->charData["level"],
            "name"  => $this->charData['name'],
            "guild" => $this->charData["guild"],
            "health" => $this->charData["health"],
            "powerType" => $this->charData["powerType"],
            "class" => $this->charData["class"],
            "power" => $this->charData["power"],
            "kills" => $this->charData["kills"],
            "honor" => $this->charData["honor"],
            "arena" => $this->charData["arena"],
            "proffesions" => $this->charData["proffesions"],
            "online"    => $this->charData["online"],
            "avatar"    => $this->charData["classavatar"],
            "itemsIDs" => $this->charData["itemsIDs"],
            'raceID'    => $this->charData["raceID"],
            'gender'    => $this->charData["gender"],
            "achievements" => $this->charData["achievements"]
        ];

        return $data;
    }

    public function getItemData($id) {
        $response = file_get_contents('http://wotlk.wowhead.com/item='.$id.'&xml');
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        $img = '<img src="http://wow.zamimg.com/images/wow/icons/large/'.$array["item"]["icon"].'.jpg" />';
        $html = '<a href="http://wotlk.wowhead.com/item='.$id.'" class="q4">'.$img.'</a>';
        return $html;
    }

    public function checkValidity($name, $realm) {
        $this->char->setRealm($realm);
        if(!$this->char->checkCharacterByName($name)) {
            return false;
        }
        return true;
    }

    public function getItemDisplay($id) {
        global $DB;

        $res = $DB->prepare('SELECT * FROM `armory_data` WHERE entry=:entry;');
        $res->bindParam(':entry', $id, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) {
            $a = $res->fetch();
            $data = [
                "slot"  => $a["InventoryType"],
                "display" => $a["display"]
            ];
        }

        return $data;
    }

    private function urlIsValid($URL){
        $headers = @get_headers($URL);
        preg_match("/ [45][0-9]{2} /", (string)$headers[0] , $match);
        return count($match) === 0;
    }

    public function generateModel($items, $race, $gender) {
        $race = str_replace(' ', '', ($this->char->getRaceString($race) == 'Undead' ? 'Scourge' : $this->char->getRaceString($race))).($gender =0 ? 'male' : 'female');

        $equip = '';
        $i = 0;
        foreach($items as $item) {
            if($item > 0) {
                $itemInfo = $this->getItemDisplay($item);
                if($itemInfo["slot"] != 17 && $itemInfo["slot"] != 21 && $itemInfo["slot"] != 14 && $itemInfo["slot"] != 13) {
                    $url = 'http://wow.zamimg.com/modelviewer/meta/armor/' . $itemInfo["slot"] . '/' . $itemInfo["display"] . '.json';
                    if ($this->urlIsValid($url)) {
                        $equip .= ',' . $itemInfo["slot"] . ',' . $itemInfo["display"];
                    }
                }
                 else {
                     $equip .= ','.$itemInfo["slot"].','.$itemInfo["display"];
                 }
                }
            }


        $string = "model=".$race."&amp;modelType=16&amp;contentPath=http://wow.zamimg.com/modelviewer/&amp;equipList=".ltrim($equip, ',');
        return $string;
    }

    public function searchCharacters($name, $realm) {
        if(!$name || !$realm) {
            return false;
        }

        $this->realm = $realm;
        $this->name = $name;

        $this->char->setRealm($this->realm);

        $response = [];
        $i = 0;

        if($characters = $this->char->getCharactersByName($this->name)) {
            foreach($characters as $character) {
                $response[$i]["name"]   = $character["name"];
                $response[$i]["class"]  = str_replace(' ', '', strtolower($this->char->getClassString($character["class"])));
                $response[$i]["level"]  = $character["level"];
                $response[$i]["guid"]   = $character["guid"];
                $response[$i]["gender"] = $character["gender"] = 0 ? 'Male' : 'Female';
                $response[$i]["faction"] = $this->char->ResolveFaction($character["race"]);
                $response[$i]["race"]   = $character["race"];
                $response[$i]["realm"] = $realm;
                $i += 1;
            }
            return $response;
        } else {
            return false;
        }
    }
}