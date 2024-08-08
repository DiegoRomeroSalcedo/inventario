<?php

namespace Proyecto\Controller;

use Exception;
use Proyecto\Models\Marcas;
use Proyecto\Models\Productos;
use Views\View\View;
use Proyecto\Models\Ventas;

class VentasController {

    protected $view;
    protected $productos;
    protected $marcas;

    public function __construct( View $view, Productos $productosModel, Marcas $marcasModel) {
        $this->view = $view;
        $this->productos = $productosModel;
        $this->marcas = $marcasModel;
    }

    public function searchAddVenta() {

        $data = [
            'productos' => [],
            'dataVenta' => [],
        ];

        $this->view->addScripts('add_venta.js');

        $idProducto = $this->productos->getIdNombre();

        $data['productos'] = $idProducto;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idProducto = $_POST['nombre_producto'];

            $searchPro = $this->productos->serchVentaProducto($idProducto);

            $data['dataVenta'] = $searchPro;

            header('Content-Type: application/json');
            echo json_encode($data['dataVenta']);
            exit();
        }

        $this->view->assign('data', $data);
        $this->view->render('search_add_venta.php');
    }

    public function finalizarVenta() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            print_r($data);
            die("Aqui");
            $carrito = $data['carrito'] ?? [];
            $cliente_id = $data['cliente_id'] ?? 1; //quitar el 1 esto por ahora es de prueba
            $total = array_sum(array_column($carrito, 'precio_total'));

        
            try{
                $ventasModel = new Ventas();
                $idFactura = $ventasModel->addFactura($cliente_id, $total); //Recuperamos el id de la factura
                
                foreach($carrito as $producto) {
                    $ventasModel->addVenta(
                        $idFactura,
                        $producto['id_product'],
                        $producto['cantidad'],
                        $producto['precio_unitario']
                    );//Metodo para insertar la venta de cada producto
                }

                echo json_encode(['succes' => true]);
            }catch(Exception $e) {
                echo json_encode(['Error' => $e->getMessage()]);
            }
            exit();
        }
    }
}

?>