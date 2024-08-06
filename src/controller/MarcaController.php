<?php

namespace Proyecto\Controller;

use Proyecto\Models\Marcas;
use Views\View\View;

class MarcaController {

    protected $view;

    public function __construct(View $view) {
        $this->view = $view;
    } 

    public function list() {
        // Obtener la lista de productos del modelo
        $marcasModel = new Marcas();
        $marcas = $marcasModel->getAll();

        //Js propios
        $this->view->addScripts('jsdatatables.js');

        //estilos externos
        $this->view->addStylesExternos('https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css');
        $this->view->addStylesExternos('https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css');
        
        //librerias externas
        $this->view->addLibraries('https://code.jquery.com/jquery-3.7.1.js');
        $this->view->addLibraries('https://cdn.datatables.net/2.1.3/js/dataTables.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js');
        $this->view->addLibraries('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js');
        $this->view->addLibraries('https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js');

        $this->view->assign('marcas', $marcas); // No fue necesario el dat, ya que solo pase una variable en concreto.
        $this->view->renderMarcasList();
    }

    public function addMarca() {

        $data = [
            'marca' => []
        ];

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $nombreMarca = $_POST['nombre_marca'];
            $marcasModel = new Marcas();
            $marca = $marcasModel->insertMarca($nombreMarca);

            // Preparamos los datos para la vista
            $data['marca'] = $marca;

        }
            $this->view->assign('marca', $data);
            $this->view->render('marca_form.php');
    }

}