<?php

ob_start();

$title = "Actualizar Producto";

?>

<h1>Hola</h1>

<?php

$content = ob_get_clean();

include __DIR__ . '/layouts/layout.php';