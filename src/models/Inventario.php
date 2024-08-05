<?php

namespace Proyecto\Models;

USE PDO;
use PDOException;

use Config\ConfigConnect;

class Inventario {

    private $conexion;

    public function __construct() {
        $this->conexion = ConfigConnect::getInstance()->getConnection();
    }

    public function defaultData() {
        try {
            $mysql = "SELECT
                        b.id_product,b.no_product,a.id_marca, 
                        a.nombre_marca, c.cantidad, b.cost_produ, 
                        b.rte_fuente,b.flet_produ, b.iva_produc, 
                        b.pre_finpro,b.uti_produc, b.pre_ventap, 
                        b.desc_produ,b.pre_ventades, b.ren_product,
                        c.fech_actual
                    FROM marcas a
                    INNER JOIN productos b ON a.id_marca = b.id_marcapr
                    LEFT JOIN cantidad_productos c ON b.id_product = id_producto
                    ORDER BY c.fech_actual DESC LIMIT 100";
            $mysql = $this->conexion->prepare($mysql);
            $mysql->execute();
            return $mysql->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error al obtener los datos; " . $e->getMessage();
        }
    }
}