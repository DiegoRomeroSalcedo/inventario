<?php

namespace Proyecto\Models;
use PDO;
use PDOException;

use Config\ConfigConnect;

class Marcas {
    private $conexion;

    public function __construct() {
        $this->conexion = ConfigConnect::getInstance()->getConnection();
    }
    
    public function getAll() {
        $mysql = $this->conexion->prepare("SELECT * FROM marcas ORDER BY id_marca ASC");
        $mysql->execute();
        return $mysql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdNombre() {
        $mysql = $this->conexion->prepare("SELECT id_marca, nombre_marca FROM marcas ORDER BY id_marca ASC");
        $mysql->execute();
        return $mysql->fetchAll(PDO::FETCH_ASSOC);
    }


    // Adicional jaremos uso del patron Post/Redirect/Get (PRG). para evitar el reenvio de formularios con la recarga de la pagina
    public function insertMarca($nombre) {
        try{
            $nombre = trim($nombre);
            $insusr = $_SESSION['username'];
            $actusr = $_SESSION['username'];
            $mysql = $this->conexion->prepare("INSERT INTO `marcas`(id_marca, nombre_marca, usuario_insercion, fecha_insercion, usuario_actualizacion, fecha_actualizacion) VALUES (:id_marca, :nombre, :usuario_insercion, CURRENT_TIMESTAMP, :usuario_actualizacion, CURRENT_TIMESTAMP)");
            $id_marca = "";
            $mysql->bindParam(":id_marca", $id_marca, PDO::PARAM_INT);
            $mysql->bindParam(":nombre", $nombre);
            $mysql->bindParam(":usuario_insercion", $insusr);
            $mysql->bindParam(":usuario_actualizacion", $actusr);

            $mysql->execute();

            // Obtenemos el id autoinccrement de la insercion
            $lastId = $this->conexion->lastInsertID();

            $mysql = $this->conexion->prepare("SELECT nombre_marca FROM marcas WHERE id_marca = :id_marca");
            $mysql->bindParam(":id_marca", $lastId);
            $mysql->execute();

            $nombre = $mysql->fetch(PDO::FETCH_ASSOC);

            header("Location: /inventario/public/add-marcas?id=" . $lastId . "&nombre=" . urlencode($nombre['nombre_marca']));
            exit();
        } catch(PDOException $e) {
            if($e->errorInfo[1] === 1062) {
                $_SESSION['error'] = "El registro ya existe";
                header("Location: /inventario/public/add-marcas");
                exit();
            } else {
                echo "Error de insercion: " . $e->getMessage();
                $_SESSION['error_sql'] = "Error, porfavor intente luego";
                header("Location: /inventario/public/add-marcas");
                exit();
            }
        }
    }
}