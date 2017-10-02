<?php

if (!defined('init_engine'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

class AccountActivity {
    private $db, $user;

    public function __construct() {
        global $DB, $CURUSER;

        $this->db = $DB;
        $this->user = $CURUSER;
    }

    public function getActivitiesTotal() {
        $id = $this->user->get('id');

        $res = $this->db->prepare('SELECT * FROM `account_activity` WHERE user =:user');
        $res->bindParam(':user', $id, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) {
            return $res->rowCount();
        } else {
            return false;
        }
    }

    public function addActivity($user, $activity, $addon = '', $char_name = '') {
        $id = $this->user->get('id');
        $date = date('Y-m-d H:i:s');


        $res = $this->db->prepare('INSERT INTO `account_activity` (`user`, `activity`, `char_name`, `addon`, `date`) VALUES (:user, :activity, :char_name, \''.$addon.'\', \''.$date.'\');');
        $res->bindParam(':user', $id, PDO::PARAM_INT);
        $res->bindParam(':activity', $activity, PDO::PARAM_INT);
        $res->bindParam(':char_name', $char_name, PDO::PARAM_INT);
        //$res->bindParam(':addon', $addon, PDO::PARAM_INT);
        $res->execute();

        return $res;
    }

    public function getActivitiesFrom($from) {
        $id = $this->user->get('id');

        $res = $this->db->prepare('SELECT * FROM `account_activity` WHERE user =:user ORDER BY date DESC LIMIT 5 OFFSET :from;');
        $res->bindParam(':user', $id, PDO::PARAM_INT);
        $res->bindParam(':from', $from, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) {
            $activity_list = $res->fetchAll();

            $activities = [];

            $l = count($activity_list);
            $activities["count"] = $l;

            for($i = 0; $i < $l; $i++) {
                $activities[$i]["type"]  = $this->getActivityType($activity_list[$i]["activity"]);
                $activities[$i]["char"]  = $activity_list[$i]["char_name"];
                $activities[$i]["addon"] = $activity_list[$i]["addon"];
                $activities[$i]["date"]  = $activity_list[$i]["date"];
            }

            return $activities;

            //die(var_dump($activities));
        } else {
            return false;
        }
    }

    private function getActivityType($type) {
        switch($type) {
            case 1:
                $type = "teleport";
                break;
            case 2:
                $type = 'passupdate';
                break;
            case 3:
                $type = 'codered';
                break;
            case 4:
                $type = 'emailup';
                break;
            case 5:
                $type = 'displayname';
                break;
            case 6:
                $type = 'donate';
                break;
            default:
                $type = NULL;
                break;
        }

        return $type;
    }
}