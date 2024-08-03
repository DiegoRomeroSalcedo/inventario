<?php

ob_start();
$title = "Listado Productos";
?>

<!-- Contenido HTML -->

<main>
    <h1>Listado de productos</h1>
    <table id="example">
        <thead>
            <tr>
                <th>ID_PRODUCTO</th>
                <th>NOMBRE</th>
                <th>MARCA</th>
                <th>PRECIO_VENTA</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($productos as $producto) : ?>
                <tr>
                    <td><?= htmlspecialchars($producto['id_product']) ?></td>
                    <td><?= htmlspecialchars($producto['no_product']) ?></td>
                    <td><?= htmlspecialchars($producto['id_marcapr']) ?></td>
                    <td><?= htmlspecialchars($producto['pre_ventap']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php

$content = ob_get_clean();

include __DIR__ . '/layouts/layout.php';

?>