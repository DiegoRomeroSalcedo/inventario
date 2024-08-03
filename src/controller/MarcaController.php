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
