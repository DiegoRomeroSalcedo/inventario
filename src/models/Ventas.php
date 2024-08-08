<?php

namespace Proyecto\Models;

use PDO;
use PDOException;

use Config\ConfigConnect;

class Ventas {

    private $conexion;

    public function __construct() {
        $this->conexion = ConfigConnect::getInstance()->getConnection();
    }

    public function addFactura($total, $id_cliente) {
        try {
            $sql = "INSERT INTO facturas (total_venta, id_cliente) VALUES (:total_venta, :id_cliente)";
            $mysql = $this->conexion->prepare($sql);
            $mysql->bindValue(':total_venta', $total);
            $mysql->bindValue(':id_cliente', $id_cliente);
            $mysql->execute();

            return $this->conexion->lastInsertID();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function addVenta() {
        
    }
}