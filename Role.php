<?php

class Role
{
    private $permissions;
    
    public function __construct()
    {
        $this->permissions = array();
    }
    
    public static function getRolePerms($role_id)
    {
        $role = new Role();
        $result = queryMysql("SELECT t2.perm_name FROM
            role_perm AS t1 LEFT JOIN permissions AS t2
            ON t1.perm_id = t2.id
            WHERE t1.role_id = $role_id");
        while($row = $result->fetch_assoc())
        {
            $role->permissions[$row['perm_name']] = true;
        }
        return $role;
    }
    
    public function hasPermission($perm)
    {
        return isset($this->permissions[$perm]);
    }
}

