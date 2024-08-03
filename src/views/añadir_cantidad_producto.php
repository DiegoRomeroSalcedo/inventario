<?php 

ob_start();

$title = "Insertar Cantidad Producto";

?>


<?php

$content = ob_get_clean();

include __DIR__ . '/layouts/layout.php';