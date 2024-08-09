<?php

namespace Proyecto\Controller;

use Proyecto\Models\Devolucion;
use Views\View\View;

class DevolucionController {

    protected $view;

    public function __construct(View $view) {
        $this->view = $view;
    }

    public function searchFactura() {

        $data = [];

        $factura = new Devolucion();
        $facturaData = $factura->getFacturaData();


        $this->view->render('search_factura_data.php');
    }
}