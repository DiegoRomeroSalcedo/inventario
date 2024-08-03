<?php

namespace Proyecto\Controller;

use Proyecto\Models\Productos;
use Proyecto\Models\Marcas;
use Views\View\View;
use Proyecto\Utils\Encryption;

class ProductoController {

    protected $view;
    protected $productosModel;
    protected $marcasModel;

    // Aplicamos la inyeccion de dependencias para evitar instanciar clases y metdodos a cada rato
    public function __construct(View $view, Productos $productosModel, Marcas $marcasModel) {
        $this->view = $view;
        $this->productosModel = $productosModel;
        $this->marcasModel = $marcasModel;
    }

    public function list() {
        // Obtenemos la lista de los productos, para listar en datatable
        $productos = $this->productosModel->getAll(); // de esta manera nos ahorramos el tener que instanciar con new

        $this->view->assign('productos', $productos); // No fue necesario el dat, ya que solo pase una variable en concreto.
        $this->view->renderProductoList();
    }

    public function addProducto() {

        $marca = $this->marcasModel->getIdNombre();

        $data = [
            'marca' => $marca, 
            'producto' => []
        ];

        // Siempre agregamos el script, para que sea en tiempo real
        $this->view->addScripts('insert_product.js');

        $descuento = isset($_POST['des_product']) ? $_POST['des_product'] : 0;
        $pre_ventades = isset($_POST['pre_ventades']) ? $_POST['pre_ventades'] : 0;


        if($_SERVER["REQUEST_METHOD"] == 'POST') {

            $datos = [
                ':id_product'            => "",
                ':no_product'            => $_POST['nom_produc'] ?? null,
                ':id_marcapr'            => $_POST['marca_producto'] ?? null, 
                ':cost_produ'            => $_POST['cost_produ'] ?? null,
                ':rte_fuente'            => $_POST['porc_rete'] ?? null,
                ':flet_produ'            => $_POST['porc_flete'] ?? null,
                ':iva_produc'            => $_POST['porc_iva'] ?? null,
                ':pre_finpro'            => $_POST['pre_finpro'] ?? null,
                ':uti_produc'            => $_POST['uti_product'] ?? null,
                ':pre_ventap'            => $_POST['pre_ventap'] ?? null,
                ':desc_produ'            => $descuento ?? null,
                ':pre_ventades'          => $pre_ventades ?? null,
                ':ren_product'           => $_POST['rentabilidad'] ?? null,
                ':usuario_insercion'     => $_SESSION['username'] ?? null,
                ':usuario_actualizacion' => $_SESSION['username'] ?? null,
            ];

            $producto = $this->productosModel->insertarProducto($datos);
            
            $data['producto'] = $producto;
        } 

        // Asignamos los datos a la vista
        $this->view->assign('data', $data);
        $this->view->render('product_form.php');
    }

    public function search() {

        $marca = $this->marcasModel->getIdNombre();

        // preparamos los datos por defecto para la vista

        $data = [
            'marca' => $marca,
            'productos' => []
        ];

        $this->view->addScripts('asincronous_search_products.js');

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nomProduct = $_POST['nom_product'];
            $marcaProdu = isset($_POST['marca_producto']) &&  $_POST['marca_producto'] != '0' ? $_POST['marca_producto'] : '%';

            $dataPrArr = [
                ':no_product' => $nomProduct, 
                ':id_marcapr' => $marcaProdu
            ];

            $productos = $this->productosModel->getProductData($dataPrArr);

            
            $idProducto = $productos[0]['id_product'];
            $nomProduct = $productos[0]['no_product'];
            $nomMaraca = $productos[0]['id_marca'];

            $datosaEncriptar = [
                'id_product' => $idProducto,
                'no_product' => $nomProduct,
                'id_marca' => $nomMaraca,
            ];

            $encriptedData = Encryption::encrypt($datosaEncriptar);

            //Actuliza los datos con los productos enontrados
            $data['productos'] = $productos;
            $data['encriptados'] = $encriptedData;

            //Envia la respuesta como JSON
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        } 

        //Asignamos los datos a la vista
        $this->view->assign('data', $data);
        $this->view->render('form_search_products.php');
    }

    public function addCantidadProducto(){

        $data = ['data' => []];

        if(isset($_GET['data']) && !empty($_GET['data'])){
            $encriptedData = $_GET['data'];
            
            $decryptedData = Encryption::decrypt($encriptedData);

            
        }

        $this->view->assign('data', $data);
        $this->view->render('añadir_cantidad_producto.php');
    }
}