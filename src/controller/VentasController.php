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
            $carrito = $data['carrito'] ?? [];
            $total = (float) $data["total"];
            $cliente_id = $data['cliente_id'] ?? 0; //quitar el 1 esto por ahora es de prueba

            try{
                $ventasModel = new Ventas();
                $idFactura = $ventasModel->addFactura($total, $cliente_id); //Recuperamos el id de la factura
                
                foreach($carrito as $producto) {
                    $preUnitario = (float) str_replace(',', '', $producto['pre_ventades']);
                    $ventasModel->addVenta(
                        $idFactura,
                        $producto['id_product'],
                        $producto['cantidad'],
                        $preUnitario,
                        $producto['totalIndCarrito'],
                    );//Metodo para insertar la venta de cada producto

                    $cantidadActual = $this->productos->getCantidadStock($producto['id_product'],);

                    $cantidadStock = $cantidadActual['cantidad'];
                    $cantidad = $producto['cantidad'];

                    $totalStock = $cantidadStock - $cantidad;

                    $updateCantidad = $this->productos->updateCantidadVenta($totalStock, $producto['id_product'],);
                }

                if($updateCantidad) {
                    echo json_encode(['succes' => true, 'invoiceId' => $idFactura]);
                }
            }catch(Exception $e) {
                echo json_encode(['Error' => $e->getMessage()]);
            }
            exit();
        }
    }

    public function getFacturaData() {

        if($_SERVER['REQUEST_METHOD'] == 'GET') {

            $facturaid = $_GET['id'] ?? 0;

            if(empty($facturaid)) {
                echo json_encode(['error' => 'Id de la factura no proporcionado']);
                exit();
            }

            try {
                $ventasModel = new Ventas();
                $detallesFactura = $ventasModel->getDetallesFactura($facturaid);

                if($detallesFactura) {
                    header('Content-Type: application/json');
                    echo json_encode($detallesFactura);
                } else {
                    echo json_encode(['error' => 'Factura no Encontrada']);
                }
            } catch(Exception $e) {
                echo json_encode(['Error' => $e->getMessage()]);
            }

            exit();
        }

    }
}

?>