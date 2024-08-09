<?php

namespace Proyecto\Controller;

use Exception;
use Proyecto\Models\Marcas;
use Proyecto\Models\Productos;
use Views\View\View;
use Proyecto\Models\Ventas;
use Proyecto\Models\Clientes;

class VentasController {

    protected $view;
    protected $productos;
    protected $marcas;
    public $carpeta = 'ventas';

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
        $this->view->render('search_add_venta.php', $this->carpeta);
    }

    public function finalizarVenta() {

        $data = [
            'id_cliente' => [],
        ];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $carrito = $data['carrito'] ?? [];
            $total = (float) $data["total"];
            $cliente_id = $data['cliente_id'] ?? 0; //quitar el 1 esto por ahora es de prueba
            $clienteData = $data['clienteData'] ?? null;

            if($clienteData) {
                $cedulaCliente = (string) $data['clienteData']['cedulaCliente'];
                $nroCelular = (string) $data['clienteData']['nroCelular'];
                $nomCliente = $data['clienteData']['nomCliente'];
                $emailCliente = $data['clienteData']['emailCliente'];
                $dirCliente = $data['clienteData']['dirCliente'];
            } else {
                $cedulaCliente = '';
                $nroCelular = '';
                $nomCliente = '';
                $emailCliente = '';
                $dirCliente = '';
            }

            try{

                $clienteModel = new Clientes();

                if($clienteData) {

                    $existCliente = $clienteModel->getClienteCedula($cedulaCliente);

                    if($existCliente && is_array($existCliente)) {
                        $compra = 1;
                        $compCliente = $existCliente['cantidad_compras'];
                        $nueCompra = $compra + $compCliente;
                        $updateCompra = $clienteModel->updateCompras($nueCompra, $cedulaCliente);
                        $data['id_cliente'] = $existCliente['id_cliente'];
                    } else {
                        $insertCliente = $clienteModel->insertCliente($cedulaCliente, $nomCliente, $nroCelular, $emailCliente, $dirCliente);
                        $data['id_cliente'] = $insertCliente;
                    }
                } else {
                    $data['id_cliente'] = null;
                } 
                
                $ventasModel = new Ventas();
                $idFactura = $ventasModel->addFactura($total, $data['id_cliente']); //Recuperamos el id de la factura
                
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

}

?>