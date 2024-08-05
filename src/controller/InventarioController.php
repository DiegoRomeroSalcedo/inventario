<?php

namespace Proyecto\Controller;

use Views\View\View;
use Proyecto\Models\Inventario;
use Proyecto\Models\Marcas;
use Proyecto\Models\Productos;

class InventarioController {

    protected $view;
    protected $inventario;
    protected $marcasModel;
    protected $productosModel;

    public function __construct(View $view, Inventario $inventario, Marcas $marcasModel, Productos $productosModel) {
        $this->view = $view;
        $this->inventario = $inventario;
        $this->marcasModel = $marcasModel;
        $this->productosModel = $productosModel;
    }

    public function list() {

        $data = [
            'idYmarca' => [],
            'idYprodu' => [],
            'defaultData' => [],
        ];

        $this->view->addScripts('datapickers.js');
        $this->view->addScripts('jsdatatables.js');
        $this->view->addStylesExternos('https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css');
        $this->view->addStylesExternos('https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js');

        //Intanciamos la clase de marcas mediante la inyeccion de dependendencias para el selector de marca
        $idYmarca = $this->marcasModel->getIdNombre();
        $idYprodu = $this->productosModel->getIdNombre();
        $datadefault = $this->inventario->defaultData();
        $data['idYmarca'] = $idYmarca;
        $data['idYprodu'] = $idYprodu;
        $data['defaultData'] = $datadefault;

        $this->view->assign('data', $data);
        $this->view->render('invetario_list.php');
    }
}