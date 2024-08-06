<?php

namespace Proyecto\Models;

use PDO;
use PDOException;

use Config\ConfigConnect;

class Productos {
    private $conexion;

    public function __construct() {
        $this->conexion = ConfigConnect::getInstance()->getConnection();
    }

    public function getAll() {
        
        try {
            $mysql = $this->conexion->prepare("SELECT * FROM `productos` ORDER BY `id_product` ASC");
            $mysql->execute();
            return $mysql->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getIdNombre() {
        try{
            $mysql = $this->conexion->prepare("SELECT id_product, no_product FROM productos ORDER BY no_product ASC");
            $mysql->execute();
            return $mysql->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function insertarProducto($datos) {

        try {

            $mysql = $this->conexion->prepare(
                "INSERT INTO `productos`(
                    id_product, no_product, id_marcapr, 
                    cost_produ, rte_fuente, flet_produ, 
                    iva_produc, pre_finpro, uti_produc, 
                    pre_ventap, desc_produ, pre_ventades,
                    ren_product, detalle_product, usuario_insercion, feha_insercion,
                    usuario_actualizacion, feha_actualizacion
                ) VALUES (
                    :id_product, :no_product, :id_marcapr, 
                    :cost_produ, :rte_fuente, :flet_produ, 
                    :iva_produc, :pre_finpro, :uti_produc, 
                    :pre_ventap, :desc_produ, :pre_ventades,
                    :ren_product, :detalle_product, :usuario_insercion, CURRENT_TIMESTAMP,
                    :usuario_actualizacion, CURRENT_TIMESTAMP
                )"
            );

            foreach($datos as $key => $value) {
                //Determinamos el tipo de dato
                $type = PDO::PARAM_STR;
                if(is_int($value)) {
                    $type = PDO::PARAM_INT;
                } elseif(is_float($value)) {
                    $type = PDO::PARAM_STR; //pdo no tiene un tipo especifico
                }

                $mysql->bindValue($key, $value, $type);
            }

            $mysql->execute();

            // Obtenemos el id autoincrement
            $lastId = $this->conexion->lastInsertID();

            $mysql = $this->conexion->prepare("
                SELECT a.no_product, b.nombre_marca
                FROM productos a 
                INNER JOIN marcas b ON a.id_marcapr = b.id_marca
                WHERE a.id_product = :id_product
            ");
            $mysql->bindParam(":id_product", $lastId, PDO::PARAM_INT);
            $mysql->execute();

            $nombre = $mysql->fetch(PDO::FETCH_ASSOC);

            header("Location: /inventario/public/add-productos?id=" . $lastId . "&nombre=" . urlencode($nombre['no_product']) . "&marca=" . urlencode($nombre['nombre_marca']));
            exit();
        } catch(PDOException $e) {
            if($e->errorInfo[1] === 1062){
                $_SESSION['error'] = "El regitro ya existe";
                header("Location: /inventario/public/add-productos");
                exit();
            } else {
                $_SESSION['error_sql'] = "Error, porfavor intente luego";
                echo "Error: " . $e->getMessage();
                // header("Location: /inventario/public/add-productos");
                // exit();
            }
        }
    }

    public function getProductData($data) {

        try {

            $mysql = $this->conexion->prepare(
                "
                SELECT 
                    *, b.nombre_marca, c.cantidad
                FROM productos a
                INNER JOIN marcas b ON a.id_marcapr = b.id_marca
                LEFT JOIN cantidad_productos c ON a.id_product = c.id_producto
                WHERE no_product = :no_product AND id_marcapr LIKE :id_marcapr
                "
            );

            foreach($data as $key => $value) {
                if(is_int($value)) {
                    $type = PDO::PARAM_INT;
                } else {
                    $type = PDO::PARAM_STR;
                }

                $mysql->bindValue($key, $value, $type);
            }

            $mysql->execute();
            $lastId = $this->conexion->lastInsertID();
            $results = $mysql->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
        }
    }

    public function insertCantidadProducto($data, $encriptado) {

        try {

            $checkQuery = "SELECT COUNT(*) AS count FROM cantidad_productos WHERE id_producto = :id_producto";
            $checkQuery = $this->conexion->prepare($checkQuery);
            $checkQuery->bindValue(':id_producto', $data[':id_producto'], PDO::PARAM_INT);
            $checkQuery->execute();
            $results = $checkQuery->fetch(PDO::FETCH_ASSOC);
            $exists = isset($results['count']) && $results['count'] > 0;

            if(!$exists) {

                $mysql = $this->conexion->prepare(
                    "
                    INSERT INTO cantidad_productos (id_producto, cantidad, user_insert, fech_insert, user_actual, fech_actual) VALUES (:id_producto, :cantidad, :user_insert, CURRENT_TIMESTAMP, :user_actual, CURRENT_TIMESTAMP)
                    "
                );
    
                foreach($data as $key => $value) {
    
                    if(is_int($value)) {
                        $type = PDO::PARAM_INT;
                    } else {
                        $type = PDO::PARAM_STR;
                    }
    
                    $mysql->bindValue($key, $value, $type);
                }
    
                $mysql->execute();
                $insertado = "insertado";

                header("Location: /inventario/public/get-add-cantidades?data=" . $encriptado ."&tipo=" . urlencode($insertado));
                exit();
            } else {
                $mysql = "UPDATE cantidad_productos SET cantidad = cantidad + :cantidad, user_actual = :user_actual WHERE id_producto = :id_producto";
                $mysql = $this->conexion->prepare($mysql);
                $mysql->bindParam(':id_producto', $data[':id_producto'], PDO::PARAM_INT);
                $mysql->bindParam(':cantidad', $data[':cantidad'], PDO::PARAM_INT);
                $mysql->bindParam(':user_actual', $data[':user_actual'], PDO::PARAM_STR);
                $mysql->execute();

                $mysql = "SELECT cantidad FROM cantidad_productos WHERE id_producto = :id_producto";
                $mysql = $this->conexion->prepare($mysql);
                $mysql->bindParam(':id_producto', $data[':id_producto'], PDO::PARAM_INT);
                $mysql->execute();
                $result = $mysql->fetch();

                $actualizado = "actualizado";
                header("Location: /inventario/public/get-add-cantidades?data=" . $encriptado . "&tipo=" . $actualizado . '&cantidad=' . $result['cantidad']);
                exit();
            }
        } catch(PDOException $e) {
            if($e->errorInfo[1] === 1062){
                $_SESSION['error'] = "El regitro ya existe";
                header("Location: /inventario/public/get-add-cantidades");
                exit();
            } else {
                $_SESSION['error_sql'] = "Error, porfavor intente luego";
                header("Location: /inventario/public/get-add-cantidades");
                exit();
            }
        }
    }
}