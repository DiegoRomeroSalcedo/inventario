<?php

namespace Views\View;

class View {
    protected $data = [];
    protected $scripts  = [];
    protected $styles = [];
    protected $styleslibraries = [];
    protected $libraries = [];

    public function assign($key, $value) {
        $this->data[$key] = $value;
    }

    public function render($template, $carpeta) {

        if($carpeta == "marcas") {
            extract($this->data);
            $file =  __DIR__ . '/../src/views/marcas/' . $template;

            if(file_exists($file)) {
                    extract($this->data);
                    include $file;
            } else {
                echo "Error";
            }
        }

        if($carpeta == "productos") {
            extract($this->data);
            $file =  __DIR__ . '/../src/views/productos/' . $template;

            if(file_exists($file)) {
                    extract($this->data);
                    include $file;
            } else {
                echo "Error";
            }
        }

        if($carpeta == "inventario") {
            extract($this->data);
            $file =  __DIR__ . '/../src/views/inventario/' . $template;

            if(file_exists($file)) {
                    extract($this->data);
                    include $file;
            } else {
                echo "Error";
            }
        }

        if($carpeta == "ventas") {
            extract($this->data);
            $file =  __DIR__ . '/../src/views/ventas/' . $template;

            if(file_exists($file)) {
                    extract($this->data);
                    include $file;
            } else {
                echo "Error";
            }
        }

        if($carpeta == "facturas") {
            extract($this->data);
            $file =  __DIR__ . '/../src/views/facturas/' . $template;

            if(file_exists($file)) {
                    extract($this->data);
                    include $file;
            } else {
                echo "Error";
            }
        }

        if($carpeta == "clientes") {
            extract($this->data);
            $file =  __DIR__ . '/../src/views/clientes/' . $template;

            if(file_exists($file)) {
                    extract($this->data);
                    include $file;
            } else {
                echo "Error";
            }
        }

        if($carpeta == "devoluciones") {
            extract($this->data);
            $file =  __DIR__ . '/../src/views/devoluciones/' . $template;

            if(file_exists($file)) {
                    extract($this->data);
                    include $file;
            } else {
                echo "Error";
            }
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

    public function addStyles($styles) {
        $this->styles[] = $styles;
    }

    public function getStyles() {
        $styles = array_map(function($style) {
            return BASE_URL . '/css/' . $style;
        }, $this->styles);

        return $styles;
    }

    public function addStylesExternos($style) {
        $this->styleslibraries[] = $style;
    }

    public function getStylesLibraries() {
        return $this->styleslibraries;
    }

    public function addLibraries($libraries) {
        $this->libraries[] = $libraries;
    }

    public function getLibraries() {
        return $this->libraries;
    }
}