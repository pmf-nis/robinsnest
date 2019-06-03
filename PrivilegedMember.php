<?php

require_once 'Member.php';

class PrivilegedMember extends Member
{
    private $roles;
    
    public function __construct($i, $u, $p)
    {
        parent::__construct($i, $u, $p);
        $this->initRoles($i);
    }
    
    protected function initRoles($member_id)
    {
        $this->roles = array();
        $result = queryMysql("SELECT t1.role_id, t2.role_name
            FROM member_role AS t1 JOIN roles AS t2
            ON t1.role_id = t2.id
            WHERE t1.member_id = $member_id");
        while($row = $result->fetch_assoc()) {
            $role_name = $row['role_name'];
            $this->$role_name = Role::getRolePerms($row['role_id']);
            // Posto ne postoji polje $role_name, poziva se 
            // magicna metoda 
            // __set($role_name, Role::getRolePerms($row['role_id']));
        }
    }
    
    public function hasPrivilege($perm)
    {
        foreach ($this->roles as $role) {
            if($role->hasPermission($perm)) {
                return true;
            }
        }
        return false;
    }
    
    public static function getByUsername($username)
    {
        $result = 
            queryMysql("SELECT * FROM members WHERE user = '$username'");
        $row = $result->fetch_assoc();
        if(!empty($row)) {
            $privMember = new PrivilegedMember($row['id'], $username, $row['pass']);
            //echo $privMember;
            return $privMember;
        }
        else {
            return false;
        }
    }
    
    public function __set($polje, $vrednost) {
        //echo "Dodajemo rolu $polje.\n";
        $this->roles[$polje] = $vrednost;
    }
    
    public function __toString() {
        $result = "Privileged Member: [" . $this->user . 
            "] [" . $this->pass . "]";
        foreach ($this->roles as $role) {
            $result .= "Ima role: ...";
        }
        return $result;
    }
    
}

