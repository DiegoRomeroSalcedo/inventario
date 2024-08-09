<?php

namespace Proyecto\Controller;

use Exception;
use Views\View\View;
use Proyecto\Models\Facturas;

class FacturasController {
    protected $view;
    public $carpeta = "facturas";

    public function __construct( View $view) {
        $this->view = $view;
    }

    public function listFacturData() {
        $data = [
            'facturas' => [],
        ];

        $factura = new Facturas();
        $facturas = $factura->getFacturListData();

        $data['facturas'] = $facturas;

        $this->view->assign('data', $data);
        $this->view->render('list_facturas_data.php', $this->carpeta);
    }

    public function listDetalleFactura() {
        $data = [
            'facturas' => [],
        ];
        $this->view->addScripts('list_factur_data.js');

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_factura = $_POST['id_factur'];

            $factura = new Facturas();
            $facturas = $factura->getSearchData($id_factura);
        }

        $this->view->render('list_detalle_facturas_data.php', $this->carpeta);
    }

    public function getFacturaData() {

        if($_SERVER['REQUEST_METHOD'] == 'GET') {

            $facturaid = $_GET['id'] ?? 0;

            if(empty($facturaid)) {
                echo json_encode(['error' => 'Id de la factura no proporcionado']);
                exit();
            }

            try {
                $ventasModel = new Facturas();
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

    public function renderFactura() {
        $this->view->render('factura.php', $this->carpeta);
    }
}