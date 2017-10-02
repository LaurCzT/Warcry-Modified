<?php

if (!defined('init_engine'))
{
    header('HTTP/1.0 404 not found');
    exit;
}

class Profile {
    public $profile, $data;

    public function __construct($id) {
        global $DB;

        $this->profile = $id;
        $this->db = $DB;
    }

    public function getData() {
        $data = [
            'name'  => $this->getName(),
            'rank'  => $this->getRank(),
            'last_login'    =>  $this->getLastLogin(),
            'status'    => $this->getStatus(),
            'avatar'    => $this->getAvatar(),
            'refCount'  => $this->getRefCount()
        ];

        return $data;
    }

    public function getStatus() {
        $res = $this->db->prepare('SELECT `status` FROM account_data WHERE id = :id;');
        $res->bindParam(':id', $this->profile, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) return $res->fetch() == "active" ? "active" : "inactive";
        return "inactive";
    }

    public function getRefCount() {
        $res = $this->db->prepare('SELECT COUNT(*) FROM raf_links WHERE account = :id;');
        $res->bindParam(':id', $this->profile, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) return $res->fetch()["COUNT(*)"];
        return "0";
    }

    public function getLastLogin() {
        $res = $this->db->prepare('SELECT `last_login` FROM account_data WHERE id = :id;');
        $res->bindParam(':id', $this->profile, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() < 1) return'undefined';
        return $res->fetch()["last_login"];
    }

    public function getName() {
        $res = $this->db->prepare('SELECT `displayName` FROM account_data WHERE id = :id;');
        $res->bindParam(':id', $this->profile, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) return $res->fetch()["displayName"];
        return 'undefined';
    }

    public function getRank() {
        $res = $this->db->prepare('SELECT `rank` FROM account_data WHERE id = :id;');
        $res->bindParam(':id', $this->profile, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) return $this->rankToRank($res->fetch()["rank"]);
        return 'undefined';
    }

    public function getAvatar() {
        $res = $this->db->prepare('SELECT `avatar` FROM account_data WHERE id = :id;');
        $res->bindParam(':id', $this->profile, PDO::PARAM_INT);
        $res->execute();

        if($res->rowCount() > 0) return $this->avatarToLink($res->fetch()["avatar"]);
        return 'undefined';
    }

    private function rankToRank($rank) {
        $ranks = [
        0   =>  'Rookie',
        1   =>  'Participant',
        2   =>  'Member',
        3   =>  'Veteran',
        4   =>  'Senior Member',
        5   =>  'Addict',
        6   =>  'Staff Member',
        7   =>  'Game Master',
        8   =>  'Senior Game Master',
        9   =>  'Lead Game Master',
        10  =>  'Community Manager',
        11  =>  'Senior Community Manager',
        12  =>  'Lead Community Manager',
        13  =>  'Developer',
        14  =>  'Lead Developer',
        15  =>  'Management'
        ];
        return $ranks[$rank];
    }

    private function avatarToLink($id) {
        $links = [
            0   =>  'rookie_avatar_1.jpg',
            1   =>  'rookie_avatar_2.jpg',
            2   =>  'rookie_avatar_3.jpg',
            3   =>  'rookie_avatar_4.jpg',
            4   =>  'rookie_avatar_5.jpg',
            5   =>  'rookie_avatar_6.jpg',
            6   =>  'rookie_avatar_7.jpg',
            7   =>  'rookie_avatar_8.jpg',
            8   =>  'rookie_avatar_9.jpg',
            9   =>  'rookie_avatar 9.jpg',
            100 => 'staff_1.jpg',
            102 => 'staff_2.jpg',
            103 => 'staff_3.jpg',
            104 => 'staff_4.jpg',
            105 => 'staff_5.jpg',
            106 => 'staff_6.jpg',
            107 => 'staff_7.jpg',
            108 => 'staff_8.jpg',
            109 => 'staff_9.jpg',
            110 => 'staff_10.jpg',
            111 => 'staff_11.jpg',
            112 => 'staff_12.jpg',
            113 => 'staff_13.jpg',
            114 => 'staff_14.jpg'
        ];

        if(array_key_exists($id, $links)) {
            return $links[$id];
        }

        return $links[0];
    }
}