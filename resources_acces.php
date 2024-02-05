<?php

require_once "mysql.php";

// User Roles creation
$ROLES = array(
    'admin' => array(
        'admin',
        'cuisine',
        'scan'
    )
);

class user{
    private $id = null;
    private $role = null;

    function __construct(int $id, string $role) {
        $this -> id = $id;
        $this -> role = $role;        
    }

    public function getId() {
        return $this -> id;        
    }

    public function getUserRole() {
        return $this -> role;        
    }

}



function load_from_database ($userid){
    
}
?>