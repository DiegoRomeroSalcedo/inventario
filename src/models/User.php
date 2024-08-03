<?php

namespace Proyecto\Models;
use PDO;
use PDOException;

use Config\ConfigConnect;

class User {
    private $id;
    private $role_user;
    private $username;
    private $password;
    private $conexion;

    public function __construct() {
        $this->conexion = ConfigConnect::getInstance()->getConnection();
    }

    public function validateCredentials($username, $password) {
        // Realizamos la validacion del usuario contra la BD
        // Retorna tru si son válidas, false de lo contrario.
        try {
            $mysql = $this->conexion->prepare("SELECT `id_user`, `password`, `role_id` FROM users WHERE username = :username");
            $mysql->bindParam(":username", $username);

            if($mysql->execute()) {

                $user = $mysql->fetch(PDO::FETCH_ASSOC);
                
                if(password_verify($password, $user['password'])) {
                    $this->id = $user['id_user'];
                    $this->role_user = $user['role_id'];
                    return true;
                }
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getRole() {
        return $this->role_user;
    }
}