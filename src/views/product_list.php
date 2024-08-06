<?php

ob_start();
$title = "Listado Productos";
?>

<!-- Contenido HTML -->

<div id="pageIdentifier" data-page="productos">

<main>
    <h1>Listado de productos</h1>
    <table id="example" class="display" style="width:100%">
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

</div>

<?php

//Inclusion de estilos externos
$stylesLibraries = '';

foreach ($this->getStylesLibraries() as $style) {
    $stylesLibraries .= '<link rel="stylesheet" href="' . $style . '">';
}

//inclusion de librerias
$librariesHtml = '';

foreach($this->getLibraries() as $library) {
    $librariesHtml  .= '<script src="' .$library.'"></script>';
}

//inclusion de scripts
$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}


$content = ob_get_clean();

include __DIR__ . '/layouts/layout.php';

?>