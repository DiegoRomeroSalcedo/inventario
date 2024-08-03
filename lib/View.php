<?php

namespace Views\View;

class View {
    protected $data = [];
    protected $scripts  = [];

    public function assign($key, $value) {
        $this->data[$key] = $value;
    }

    public function render($template) {
        extract($this->data);
        $file =  __DIR__ . '/../src/views/' . $template;

        if(file_exists($file)) {
                extract($this->data);
                include $file;
        } else {
            echo "Error";
        }
    }

    public function addScripts($script) {
        $this->scripts[] = $script;
    }

    public function getScripts() {
        $scripts = array_map(function($script) {
            return BASE_URL . '/js/' . $script;
        }, $this->scripts);

        return $scripts;
    }

    public function renderMarcasList() {
        $this->render('marca_list.php');
    }

    public function renderProductoList() {
        $this->render('product_list.php');
    }
}