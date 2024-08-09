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
        $username = $_SESSION['username'];

        try {
            $sql = "INSERT INTO facturas (total_venta, id_cliente, usuario_insercion) VALUES (:total_venta, :id_cliente, :usuario_insercion)";
            $mysql = $this->conexion->prepare($sql);
            $mysql->bindValue(':total_venta', $total);
            $mysql->bindValue(':id_cliente', $id_cliente);
            $mysql->bindValue(':usuario_insercion', $username);
            $mysql->execute();

            return $this->conexion->lastInsertID();
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function addVenta($idFactura, $idProducto, $cantidad, $preUnitario, $montoTotal) {
        
        $username = $_SESSION['username'];

        try {
            $query = "INSERT INTO ventas (id_factura, id_producto, cantidad, precio_unitario, monto_venta, usuario_insercion, fecha_insercion)
                        VALUES (:id_factura, :id_producto, :cantidad, :precio_unitario, :monto_venta, :usuario_insercion, CURRENT_TIMESTAMP)";
            $mysql = $this->conexion->prepare($query);
            $mysql->bindValue(':id_factura', $idFactura, PDO::PARAM_INT);
            $mysql->bindValue(':id_producto', $idProducto, PDO::PARAM_INT);
            $mysql->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $mysql->bindValue(':precio_unitario', $preUnitario);
            $mysql->bindValue(':monto_venta', $montoTotal);
            $mysql->bindValue(':usuario_insercion', $username);

            $results = $mysql->execute();

            return $results;
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}