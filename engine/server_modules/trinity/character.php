<?php
if (!defined('init_engine'))
{	
	header('HTTP/1.0 404 not found');
	exit;
}

class server_Character
{
	private $realm = 0;
	private $realm_config;
	private $DB;
	//The debuff applied to a dead character
	private $deathDebuffId = '8326';
		
	//constructor
	public function __construct()
	{
		return true;
	}

    public function getTotalStatsFor($what, $realm) {
        $this->setRealm($realm);

        if(in_array($what, ["alliance", "horde"])) {
            $races = [
                "alliance"  => '1,3,4,7,11,22',
                "horde"     => '2,5,6,8,9,10'
            ];

            $res = $this->DB->prepare("SELECT COUNT(*) FROM characters WHERE race IN ($races[$what]) AND level >= 10;");
            $res->execute();

            if($res->rowCount() > 0) return $res->fetch();
            return 0;
        }

        switch($what) {
            case "humans":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '1'
                ];
                break;
            case "orcs":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '2'
                ];
                break;
            case "dwarfs":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '3'
                ];
                break;
            case "nightelfs":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '4'
                ];
                break;
            case "undeads":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '5'
                ];
                break;
            case "taurens":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '6'
                ];
                break;
            case "gnomes":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '7'
                ];
                break;
            case "trolls":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '8'
                ];
                break;
            case "goblins":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '9'
                ];
                break;
            case "bloodelfs":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '10'
                ];
                break;
            case "draeneis":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '11'
                ];
                break;
            case "worgens":
                $data = [
                    "type"      => 'race',
                    "typeValue" => '22'
                ];
                break;
            case "warriors":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '1'
                ];
                break;
            case "paladins":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '2'
                ];
                break;
            case "hunters":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '3'
                ];
                break;
            case "rogues":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '4'
                ];
                break;
            case "priests":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '5'
                ];
                break;
            case "deathknights":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '6'
                ];
                break;
            case "shamans":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '7'
                ];
                break;
            case "mages":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '8'
                ];
                break;
            case "warlocks":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '9'
                ];
                break;
            case "druids":
                $data = [
                    "type"      => 'class',
                    "typeValue" => '11'
                ];
                break;
        }

        $res = $this->DB->prepare("SELECT COUNT(*) FROM characters WHERE ".$data['type']." = ".$data['typeValue']." AND level >= 10;");
        $res->execute();


        if($res->rowCount() > 0) return $res->fetch();
        return 0;
    }
	
	//returns true if everything went successful while setting up the realm
	public function setRealm($id)
	{
		global $realms_config, $CORE;

        if(!array_key_exists($id, $realms_config)) {
            return die('Unknown realm.');
        }

		if (isset($realms_config[$id]))
		{
			//try to connect to the database
			if ($this->DB = $CORE->RealmDatabaseConnection($id))
			{
				//set some variables
				$this->realm = $id;
				$this->realm_config = $realms_config[$id];
				
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

    public function getCharactersByName($name) {
        $name = "%$name%";

        $res = $this->DB->prepare("SELECT guid, name, level, race, class, gender FROM `characters` WHERE `name` LIKE :name ORDER BY level LIMIT 16;");
        $res->bindParam(':name', $name, PDO::PARAM_INT);
        $res->execute();

        if ($res->rowCount() > 0)
        {
            return $res->fetchAll();
        }
        else
        {
            return false;
        }
    }

    public function checkCharacterByName($name) {
        $name = "$name";

        $res = $this->DB->prepare("SELECT guid, name, level, race, class, gender FROM `characters` WHERE `name` = :name ORDER BY level;");
        $res->bindParam(':name', $name, PDO::PARAM_INT);
        $res->execute();

        if ($res->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }



    public function getCharacterDataByName($name, $realm) {
        $this->setRealm($realm);

        $res = $this->DB->prepare("SELECT guid, name, online, totalKills, level, race, class, gender, health, power1, arenaPoints, totalHonorPoints FROM `characters` WHERE `name` = :name;");
        $res->bindParam(':name', $name, PDO::PARAM_INT);
        $res->execute();

        if ($res->rowCount() > 0)
        {
            $sqlData = $res->fetch();
            $power = $this->getPower($sqlData["guid"], $realm);

            return [
                "name"  => $sqlData["name"],
                "level" => $sqlData["level"],
                "guild" => $this->getGuildName($sqlData["guid"], $realm),
                "class" => $power["class"],
                "power" => $power["amount"],
                "powerType" => $power["type"],
                "health" => $sqlData["health"],
                "kills" => $sqlData["totalKills"],
                "honor" => $sqlData["totalHonorPoints"],
                "arena" => $sqlData["arenaPoints"],
                "online" => $sqlData["online"],
                "proffesions" => $this->getProffesions($sqlData["guid"], $realm),
                'classavatar' => $this->getCharAvatar($sqlData["class"], $sqlData["gender"], $sqlData["race"], $sqlData["level"]),
                'itemsIDs'  => $this->getItemsIds($sqlData["guid"], $realm),
                'raceID'    => $sqlData["race"],
                'gender'    => $sqlData["gender"],
                'achievements'    => $this->getAchievements($sqlData["guid"], $realm)
            ];
        }
        else
        {
            return false;
        }
    }

    public function getAchievements($guid, $realm) {
        $this->setRealm($realm);

        $res = $this->DB->prepare("SELECT achievement FROM `character_achievement` WHERE `guid` = :guid ORDER BY date ASC LIMIT 5;");
        $res->bindParam(':guid', $guid, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) {
            $data = $res->fetchAll();

            $achievements = [];

            for($i = 0; $i < count($data); $i++) {
                $achievements[$i]["achievement"] = $data[$i]["achievement"];
                $achievements[$i]["name"] = $this->getAchievementName($data[$i]["achievement"]);
            }


            return $achievements;
        }

        return false;
    }

    public function getAchievementName($id) {
        global $DB;
        $res = $DB->prepare("SELECT str FROM achievements_data WHERE id=:id;");
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $res->execute();

        $name = '';

        if($res->rowCount() > 0) {
            $name = $res->fetch()["str"];
        }

        return $name;
    }

    public function getItemsIds($guid, $realm) {
        $this->setRealm($realm);

        $res = $this->DB->prepare("SELECT equipmentCache FROM `characters` WHERE `guid` = :guid;");
        $res->bindParam(':guid', $guid, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) {
            $equipment = $res->fetch()["equipmentCache"];
            $data = explode(' ', $equipment);
            return $data;
        }

        return false;
    }

    public function getCharAvatar($class, $gender, $race, $level) {
        $avatar = 'default';

        if(isset($class) == true && isset($gender) == true && isset($race) == true && isset($level) == true) {
            $class_str = $this->getClassString($class);
            $gender_str = $gender = 0 ? 'm' : 'f';
            $race_str = $this->getRaceString($race);

            if($level < 10) {
                $level_str = '1';
            } else if ($level < 60) {
                $level_str = '60';
            } else if ($level > 60) {
                $level_str = '70';
            }

            $avatar = ucfirst(str_replace(' ', '', $class_str)).'-'.strtolower(str_replace(' ', '', $race_str)).'-'.$gender_str.'-'.$level_str;
        }

        return $avatar;
    }

    public function getProffesions($guid, $realm) {
        $proffesions = [
            171 => 'Alchemy',
            164 => 'Blacksmithing',
            185 => 'Cooking',
            333 => 'Enchanting',
            202 => 'Engineering',
            182 => 'Herbalism',
            773 => 'Inscription',
            755 => 'Jewelcrafting',
            165 => 'Leatherworking',
            186 => 'Mining',
            197 => 'Tailoring',
            129 => 'First Aid',
            356 => 'Fishing',
            393 => 'Skinning'
        ];

        $proffesions_str = implode(", ", array_keys($proffesions));

        $this->setRealm($realm);

        $res = $this->DB->prepare("SELECT * FROM `character_skills` WHERE `guid` = :guid AND `skill` IN (".$proffesions_str.") LIMIT 3;");
        $res->bindParam(':guid', $guid, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() < 1) {
            return false;
        } else {
            $proffesions_matches = $res->fetchAll();

            $data = [];
            $i = 0;

            foreach($proffesions_matches as $proffesion) {
                $data[$i]["name"] = $proffesions[$proffesion["skill"]];
                $data[$i]["value"] = $proffesion["value"];
                $data[$i]["max"] = $proffesion["max"];
                $i += 1;
            }

            return $data;
        }
    }

    public function getPower($guid, $realm) {
        $this->setRealm($realm);

        $res = $this->DB->prepare("SELECT class, power1, power2, power3, power4, power7 FROM `characters` WHERE `guid` = :guid;");
        $res->bindParam(':guid', $guid, PDO::PARAM_INT);
        $res->execute();

        if ($res->rowCount() > 0)
        {
            $data = $res->fetch();

            $class = str_replace(' ','',strtolower($this->getClassString($data["class"])));

            switch($class) {
                case "warrior":
                    $type = 'rage';
                    $amount = 100;
                        break;
                case "druid":
                    $type = 'mana';
                    $amount = $data['power1'];
                    break;
                case "paladin":
                    $type = 'mana';
                    $amount = $data['power1'];
                    break;
                case "shaman":
                    $type = 'mana';
                    $amount = $data['power1'];
                    break;
                case "warlock":
                    $type = 'mana';
                    $amount = $data['power1'];
                    break;
                case "rogue":
                    $type = 'energy';
                    $amount = $data['power4'];
                    break;
                case "mage":
                    $type = 'mana';
                    $amount = $data['power1'];
                    break;
                case "deathknight":
                    $type = 'rune';
                    $amount = 100;
                    break;
                case "priest":
                    $type = 'mana';
                    $amount = $data['power1'];
                    break;
                case "hunter":
                    $type = 'mana';
                    $amount = $data['power1'];
                    break;
                default:
                    $type = 'undefined';
                    $amount = 'undefined';
                    break;
            }
        }

        return [
            "type"  => $type,
            "amount" => $amount,
            "class" => $this->getClassString($data['class'])
        ];
    }

    public function getGuildName($guid, $realm) {
        $this->setRealm($realm);

        $res = $this->DB->prepare("SELECT name FROM `guild` WHERE guildid = (SELECT guildid FROM `guild_member` WHERE guid = :guid);");
        $res->bindParam(':guid', $guid, PDO::PARAM_INT);
        $res->execute();

        if ($res->rowCount() > 0)
        {
            return $res->fetch()["name"];
        }
        else
        {
            return 'no guild';
        }
    }

	public function getAccountCharacters()
	{
		global $CURUSER;

        $updateRes = $CURUSER->get('id');

		$res = $this->DB->prepare("SELECT guid, name, level, race, class, gender FROM `characters` WHERE `account` = :account ORDER BY level;");
		$res->bindParam(':account', $updateRes, PDO::PARAM_INT);
		$res->execute();
				
		if ($res->rowCount() > 0)
		{
			return $res;
		}
		else
		{
			return false;
		}
	}
	
	public function FindHightestLevelCharacter($acc)
	{
		$res = $this->DB->prepare("SELECT guid, name, level, class FROM `characters` WHERE `account` = :account ORDER BY level DESC LIMIT 1;");
		$res->bindParam(':account', $acc, PDO::PARAM_INT);
		$res->execute();
		
		if ($res->rowCount() > 0)
		{
			$return = $res->fetch();
		}
		else
		{
			$return = false;
		}
		unset($res);
		
		return $return;
	}
	
	public function isMyCharacter($guid = false, $name = false, $account = false)
    {
		global $CURUSER;
		
		if ($guid === false and $name === false)
		{
			return false;
		}
		
		if (!$account)
			$account = $CURUSER->get('id');
		
		$res = $this->DB->prepare("SELECT guid, account FROM `characters` WHERE ".($guid === false ? "`name` = :name" : "`guid` = :guid")." AND `account` = :account LIMIT 1;");
		if ($guid !== false)
		{
			$res->bindParam(':guid', $guid, PDO::PARAM_INT);
		}
		else
		{
			$res->bindParam(':name', $name, PDO::PARAM_STR);
		}
		$res->bindParam(':account', $account, PDO::PARAM_INT);
		$res->execute();
		
		if ($res->rowCount() == 0)
			return false;
		  
      	return true;
    }

	public function getCharacterName($guid)
    {
		$res = $this->DB->prepare("SELECT name FROM `characters` WHERE `guid` = :guid LIMIT 1;");
		$res->bindParam(':guid', $guid, PDO::PARAM_INT);
		$res->execute();
		
		$row = $res->fetch(PDO::FETCH_ASSOC);
		unset($res);
		
    	if (!$row)
		{
      	  	return false;
		}
		  
      return $row['name'];
    }
	
	public function getCharacterData($guid = false, $name = false, $columns)
    {
		if ($guid === false and $name === false)
		{
			return false;
		}
		
		$columnsData = CORE_COLUMNS::get('characters');
		//empty string
		$queryColumns = "";
		
		//check if we wanna get multiple columns
		if (is_array($columns))
		{
			foreach ($columns as $key)
			{
				//check if it's valid key
				if (isset($columnsData[$key]))
				{
					$queryColumns .= "`" . $columnsData[$key] . "` AS " . $key . ", ";
				}
			}
			//check if the query has any valid columns at all
			if ($queryColumns != "")
			{
				//remove the last "," symbol from the query
				$queryColumns = substr($queryColumns, 0, strlen($queryColumns) - 2);
			}
			else
				return false;
		}
		else
		{
			//check if the column is valid
			if (isset($columnsData[$columns]))
				$queryColumns = "`" . $columnsData[$columns] . "` AS " . $columns;
			else
				return false;
		}
		
		$res = $this->DB->prepare("SELECT ". $queryColumns . " FROM `characters` WHERE ".($guid === false ? "`name` = :name" : "`guid` = :guid")." LIMIT 1;");
		if ($guid !== false)
		{
			$res->bindParam(':guid', $guid, PDO::PARAM_INT);
		}
		else
		{
			$res->bindParam(':name', $name, PDO::PARAM_STR);
		}
		$res->execute();
		
		$row = $res->fetch(PDO::FETCH_ASSOC);
		unset($res);
		
    	if (!$row)
		{
      	  	return false;
		}
		
		//free memory
		unset($queryColumns);
		unset($columnsData);
		  
      return $row;
    }
	
	public function isCharacterOnline($guid)
    {		
		$res = $this->DB->prepare("SELECT guid, online FROM `characters` WHERE `guid` = :guid LIMIT 1");
		$res->bindParam(':guid', $guid, PDO::PARAM_INT);
		$res->execute();
		
		$row = $res->fetch(PDO::FETCH_ASSOC);
		unset($res);

    	if ($row['online'] == '1')
		{
      	  return true;
		}
		  
      return false;
    }
	
	public function characterHasMoney($guid, $cost)
    { 
		global $CURUSER;
		
		$account = $CURUSER->get('id');
		
		$res = $this->DB->prepare("SELECT guid, account, money FROM `characters` WHERE `guid` = :guid AND `account` = :account LIMIT 1");
		$res->bindParam(':guid', $guid, PDO::PARAM_INT);
		$res->bindParam(':account', $account, PDO::PARAM_INT);
		$res->execute();
		
		$row = $res->fetch(PDO::FETCH_ASSOC);
		unset($res);
	 
		if (!$row)
		{
	  		return false;
		}
		else if ($row['money'] < $cost)
		{
	  		return false;
		}
	 
      return true;
    }
	
	public function ResolveGuild($guid)
	{
		//get the column translation
		$GMcolumns = CORE_COLUMNS::get('guild_member');
		
		//find out if the char is a guild member
		$res = $this->DB->prepare("SELECT `".$GMcolumns['guildid']."` AS guildid, `".$GMcolumns['guid']."` AS guid FROM `".$GMcolumns['self']."` WHERE `".$GMcolumns['guid']."` = :guid LIMIT 1;");
		$res->bindParam(':guid', $guid, PDO::PARAM_INT);
		$res->execute();
		
		if ($res->rowCount() > 0)
		{
			//we are a member of a guild
			$row = $res->fetch();
			unset($res);
			
			//get the column translation
			$GuildColumns = CORE_COLUMNS::get('guild');
			
			//resolve the guild name
			$res2 = $this->DB->prepare("SELECT `".$GuildColumns['name']."` AS name FROM `".$GuildColumns['self']."` WHERE `".$GuildColumns['guildid']."` = :guild LIMIT 1;");
			$res2->bindParam(':guild', $row['guildid'], PDO::PARAM_INT);
			$res2->execute();
			
			//check if we have found it
			if ($res2->rowCount() > 0)
			{
				//fetch
				$row2 = $res2->fetch();
				unset($res2);
				
				//return both the name and guildid
				return array('guildid' => $row['guildid'], 'name' => $row2['name']);
			}
			else
			{
				return false;
			}
		}
		else
		{
			//we are not member of any guild
			return false;
		}
		unset($res);
		
		return false;
	}
	
	public function Teleport($guid, $coords)
	{
		//if the coords are passed in array
		if (is_array($coords))
		{
			$position_x = $coords['position_x'];
			$position_y = $coords['position_y'];
			$position_z = $coords['position_z'];
			$map = $coords['map'];
		}
		else
		{
			//else passed as string
			list($position_x, $position_y, $position_z, $map) = explode(',', $coords);
		}
				
		$update_res = $this->DB->prepare("UPDATE `characters` SET position_x = :x, position_y = :y, position_z = :z, map = :map WHERE `guid` = :guid LIMIT 1;");
		$update_res->bindParam(':guid', $guid, PDO::PARAM_INT);
		$update_res->bindParam(':x', $position_x, PDO::PARAM_STR);
		$update_res->bindParam(':y', $position_y, PDO::PARAM_STR);
		$update_res->bindParam(':z', $position_z, PDO::PARAM_STR);
		$update_res->bindParam(':map', $map, PDO::PARAM_INT);
		$update_res->execute();
		
		//assume successful update
		$return = true;
		
		//check if the characters as actually updated
		if ($update_res->rowCount() == 0)
		{
			$return = false;
		}
		unset($update_res);
		
      	return $return; 
	}
	
	//////// ///////////////////////////////////////////
	//// Use by name prefered, pass guid as false
	public function Unstuck($guid = false, $name = false)
	{
		global $CORE;
		
		if ($guid !== false)
		{
			//get the player name
			$res = $this->DB->prepare("SELECT name FROM `characters` WHERE `guid` = :guid LIMIT 1;");
			$res->bindParam(':guid', $guid, PDO::PARAM_INT);
			$res->execute();
		
			$row = $res->fetch(PDO::FETCH_ASSOC);
			unset($res);
	 
			if (!$row)
			{
	  			return false;
			}
			else
			{
				$name = $row['name'];
			}
		}

		//try reviving the character aswell
 		$CORE->ExecuteSoapCommand(".revive ".$name, $this->realm);
 		/* Old Style
		$revive_res = $this->DB->prepare("DELETE FROM `character_aura` WHERE `guid` = :guid AND `spell` = :spell");
		$revive_res->bindParam(':guid', $guid, PDO::PARAM_INT);
		$revive_res->bindParam(':spell', $this->deathDebuffId, PDO::PARAM_INT);
		$revive_res->execute();
		unset($revive_res);
		*/	
		
		//unstuck using the soap teleport command
		$soap = $CORE->ExecuteSoapCommand(".tele name ".$name." \$home", $this->realm);

		if (!$soap['sent'])
		{
			return false;
		}
		
	  return true;
	}
	
	public function getRaceString($id)
	{
		switch($id)
		{
			case 1:
				return 'Human';
				break;
			case 2:
				return 'Orc';
				break;
			case 3:
				return 'Dwarf';
				break;
			case 4:
				return 'Night Elf';
				break;
			case 5:
				return 'Undead';
				break;
			case 6:
				return 'Tauren';
				break;
			case 7:
				return 'Gnome';
				break;
			case 8:
				return 'Troll';
				break;
			case 9:
				return 'Goblin';
				break;
			case 10:
				return 'Blood Elf';
				break;
			case 11:
				return 'Draenei';
				break;
			case 22:
				return 'Worgen';
				break;
			default:
				return false;
				break;
		}
		
		return false;
	}
	
	public function getClassString($id)
	{
		switch($id)
		{
			case 1:
				return 'Warrior';
				break;
			case 2:
				return 'Paladin';
				break;
			case 3:
				return 'Hunter';
				break;
			case 4:
				return 'Rogue';
				break;
			case 5:
				return 'Priest';
				break;
			case 6:
				return 'Death Knight';
				break;
			case 7:
				return 'Shaman';
				break;
			case 8:
				return 'Mage';
				break;
			case 9:
				return 'Warlock';
				break;
			case 11:
				return 'Druid';
				break;
		}
		
		return false;
	}
	
	public function ResolveFaction($race)
	{
		switch($race)
		{
			case 1:
				return FACTION_ALLIANCE;
				break;
			case 2:
				return FACTION_HORDE;
				break;
			case 3:
				return FACTION_ALLIANCE;
				break;
			case 4:
				return FACTION_ALLIANCE;
				break;
			case 5:
				return FACTION_HORDE;
				break;
			case 6:
				return FACTION_HORDE;
				break;
			case 7:
				return FACTION_ALLIANCE;
				break;
			case 8:
				return FACTION_HORDE;
				break;
			case 9:
				return FACTION_HORDE;
				break;
			case 10:
				return FACTION_HORDE;
				break;
			case 11:
				return FACTION_ALLIANCE;
				break;
			case 22:
				return FACTION_ALLIANCE;
				break;
			default:
				return false;
				break;
		}
		
		return false;
	}
	
	public function __destruct()
	{
		unset($this->realm);
		unset($this->realm_config);
		$this->DB = NULL;
		unset($this->DB);		
	}
}