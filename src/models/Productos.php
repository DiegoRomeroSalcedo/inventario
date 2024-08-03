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

    public function insertarProducto($datos) {

        try {

            $mysql = $this->conexion->prepare(
                "INSERT INTO `productos`(
                    id_product, no_product, id_marcapr, 
                    cost_produ, rte_fuente, flet_produ, 
                    iva_produc, pre_finpro, uti_produc, 
                    pre_ventap, desc_produ, pre_ventades,
                    ren_product, usuario_insercion, feha_insercion,
                    usuario_actualizacion, feha_actualizacion
                ) VALUES (
                    :id_product, :no_product, :id_marcapr, 
                    :cost_produ, :rte_fuente, :flet_produ, 
                    :iva_produc, :pre_finpro, :uti_produc, 
                    :pre_ventap, :desc_produ, :pre_ventades,
                    :ren_product, :usuario_insercion, CURRENT_TIMESTAMP,
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
                header("Location: /inventario/public/add-productos");
                exit();
            }
        }
    }

    public function getProductData($data) {

        try {

            $mysql = $this->conexion->prepare(
                "
                SELECT 
                    *, b.nombre_marca
                FROM productos a
                INNER JOIN marcas b ON a.id_marcapr = b.id_marca
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
}