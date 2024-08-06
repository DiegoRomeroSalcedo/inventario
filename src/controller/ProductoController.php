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

        //scripts propios
        $this->view->addScripts('jsdatatables.js');

        //estilos externos
        $this->view->addStylesExternos('https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css');
        $this->view->addStylesExternos('https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css');

        //librerias
        $this->view->addLibraries('https://code.jquery.com/jquery-3.7.1.js');
        $this->view->addLibraries('https://cdn.datatables.net/2.1.3/js/dataTables.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js');
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
        $detalle = $_POST['detalle_produc'] ?? null;
        $det_min = str_replace(' ', ',', strtolower(trim($detalle)));

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
                ':detalle_product'       => $det_min,
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
        $this->view->addStyles('styles_search_products.css');
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nomProduct = $_POST['nom_product'];
            $marcaProdu = isset($_POST['marca_producto']) && $_POST['marca_producto'] != '0' ? $_POST['marca_producto'] : '%';
    
            $dataPrArr = [
                ':no_product' => $nomProduct, 
                ':id_marcapr' => $marcaProdu
            ];
    
            $productos = $this->productosModel->getProductData($dataPrArr);
    
            // Encriptar cada producto individualmente
            foreach ($productos as &$producto) {
                $datosaEncriptar = [
                    'id_product' => $producto['id_product'],
                    'no_product' => $producto['no_product'],
                    'id_marca' => $producto['id_marca'],
                ];
                $producto['encriptados'] = Encryption::encrypt($datosaEncriptar);
            }
    
            // Actualiza los datos con los productos encontrados
            $data['productos'] = $productos;
    
            // Envía la respuesta como JSON
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }
    
        // Asignamos los datos a la vista
        $this->view->assign('data', $data);
        $this->view->render('form_search_products.php');
    }
    

    public function addCantidadProducto(){

        $data = [
            'decrypted' => [],
            'insertion' => []
        ];

        $this->view->addScripts('insert_cantidad_productos.js');

        if(isset($_GET['data']) && !empty($_GET['data'])){
            $encriptedData = $_GET['data'];
            
            $decryptedData = Encryption::decrypt($encriptedData);

            $data['decrypted'] = $decryptedData;
        }

        if($_SERVER["REQUEST_METHOD"] == 'POST') {
            $username = $_SESSION['username'];
            $idProducto = $_POST['id_producto'];
            $cantidadPro = $_POST['cantidad'];
            $nom_product = $_POST['nom_product'];

            $dataInsertion = [
                ':id_producto'  => $idProducto,
                ':cantidad'     => $cantidadPro,
                ':user_insert'  => $username,
                ':user_actual'  => $username
            ];

            $datosEncriptar = [
                'id' => $idProducto,
                'nombre' => $nom_product,
                'cantidad' => $cantidadPro,
            ];

            $encriptado = Encryption::encrypt($datosEncriptar);

            $insertProduct = $this->productosModel->insertCantidadProducto($dataInsertion, $encriptado);

            $data['insertion'] = $insertProduct;

        }

        $this->view->assign('data', $data);
        $this->view->render('añadir_cantidad_producto.php');
    }
}