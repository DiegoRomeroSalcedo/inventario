<?php

namespace Proyecto\Controller;

use Views\View\View;
use Proyecto\Models\Clientes;
use Exception;

class ClientesController {

    protected $view;
    public $carpeta = 'clientes';

    public function __construct( View $view) {
        $this->view = $view;
    }

    public function getDataCliente() {
        $data = [
            'clientes' => [],
        ];

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

        try {
            $cliente = new Clientes();
            $clientes = $cliente->getDataAll();

            $data['clientes'] = $clientes;
        }catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        $this->view->assign('data', $data);
        $this->view->render('list_data_client.php', $this->carpeta);
    }
}