<?php

ob_start();

$title = "Ingresar Venta";

?>

<div id="pageIdentifier" data-page="search-add-venta">
    <form id="search-add-venta" action="<?= BASE_URL . '/search-add-venta'?>" method="post">
        <div class="container_inputs">
            <label for="ced_cliente">Identificacion Cliente:</label>
            <input id="ced_cliente" type="number" min="0" name="cedula">
        </div>
        <div class="container_inputs">
            <label for="idProduct">Nombre Producto: </label>
            <select name="nombre_producto" id="nom_producto" style="background-color: #ededed;" required>
                <option value="0" style="background-color: #ddd;">Productos</option>
                <?php foreach($data['productos'] as $p): ?>
                    <option value="<?= $p['id_product'] ?>">
                        <?= htmlspecialchars($p['no_product'])?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="container__button-form">
            <button class="button_form" type="submit">Buscar Producto</button>
        </div>
    </form>
    <div id="results"></div>
    <div id="carrito">
        <h3>Carrito</h3>
        <ul id="carrito-list"></ul>
        <button id="finalizar-venta">Finalizar Venta</button>
    </div>
</div>

<?php


//inclusion de scripts
$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}

$content = ob_get_clean();

include __DIR__ . '/layouts/layout.php';