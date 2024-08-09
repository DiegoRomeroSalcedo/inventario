<?php

ob_start();

$title = "Ingresar Venta";

?>

<div id="pageIdentifier" data-page="search-add-venta">
    <form id="search-add-venta" action="<?= BASE_URL . '/search-add-venta'?>" method="post">
        <div class="container_inputs">
            <label for="nom_producto">Nombre Producto: </label>
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
        <form action="/search-add-venta" method="post" id="data-client">
            <label for="data-control">Registrar Datos Cliente: </label>
            <input id="data-control" type="checkbox" name="registrar-cliente">
            <div class="hidden" id="container-data">
                <label for="id-client">Cédula Cliente:</label>
                <input class="required" id="id-client" type="number" name="dni_client" required>
            </div>
            <div class="hidden" id="container-data">
                <label for="nombre">Nombre:</label>
                <input class="required" id="nombre" type="text" name="nom_client" required>
            </div>
            <div class="hidden" id="container-data">
                <label for="nro-celular">Nro. Celular:</label>
                <input id="nro-celular" type="number" name="celular" required>
            </div>
            <div class="hidden" id="container-data">
                <label for="email">Email:</label>
                <input id="email-cliente" type="email" name="email_client">
            </div>
            <div class="hidden" id="container-data">
                <label for="direccion">Direccion:</label>
                <input id="direccion-cliente" type="text" name="dir_client">
            </div>
        </form>
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

include __DIR__ . '/../layouts/layout.php';