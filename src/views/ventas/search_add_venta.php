<?php

ob_start();

$title = "Ingresar Venta";

?>

<div id="pageIdentifier" data-page="search-add-venta">
    <form id="search-add-venta" action="<?= BASE_URL . '/search-add-venta'?>" method="post">
        <h1>Insertar Venta</h1>
        <div class="container__major">
            <div class="container_inputs">
                <label for="nom_producto">Nombre Producto: </label>
                <select class="form__inputs" name="nombre_producto" id="nom_producto" style="background-color: #ededed;" required>
                    <option value="0" style="background-color: #ddd;">Productos</option>
                    <?php foreach($data['productos'] as $p): ?>
                        <option value="<?= $p['id_product'] ?>">
                            <?= htmlspecialchars($p['no_product'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="container__major">
            <div class="container__button-form">
                <button class="button_form" type="submit">Buscar Producto</button>
            </div>
        </div>
    </form>
    <div class="container__major">
        <div id="results"></div>
        <div id="carrito"></div>
    </div>
        <h3>Carrito</h3>
        <ul class="data-product-carrito" id="carrito-list"></ul>
        <form action="<?= BASE_URL . '/search-add-venta'?>" method="post" id="data-client">
            <div class="container__major">
                <div class="container_inputs">
                    <label for="data-control">Registrar Datos Cliente: </label>
                    <input class="aplica_descuento_input" id="data-control" type="checkbox" name="registrar-cliente">
                </div>
            </div>
            <div class="container__major">
                <div class="hidden container_inputs" id="container-data">
                    <label for="id-client">Cédula Cliente:</label>
                    <input class="form__inputs" class="required" id="id-client" type="number" name="dni_client" required>
                </div>
                <div class="hidden container_inputs" id="container-data">
                    <label for="nombre">Nombre:</label>
                    <input class="form__inputs" class="required" id="nombre" type="text" name="nom_client" required>
                </div>
                <div class="hidden container_inputs" id="container-data">
                    <label for="nro-celular">Nro. Celular:</label>
                    <input class="form__inputs" id="nro-celular" type="number" name="celular" required>
                </div>
            </div>
            <div class="container__major">
                <div class="hidden container_inputs" id="container-data">
                    <label for="email">Email:</label>
                    <input class="form__inputs" id="email-cliente" type="email" name="email_client">
                </div>
                <div class="hidden container_inputs" id="container-data">
                    <label for="direccion">Direccion:</label>
                    <input class="form__inputs" id="direccion-cliente" type="text" name="dir_client">
                </div>
            </div>
        </form>
        <div class="container__major">
            <div class="container_inputs">
                <label for="total-input-value">Total: </label>
                <input class="form__inputs" id="total-input-value" type="text" min="0" readonly>
            </div>
        </div>
        <form action="<?= BASE_URL . '/search-add-venta'?>" method="post" id="form-valor-recibido-devuelto">
            <div class="container__major">
                <div class="container_inputs">
                    <label for="valor-recibido">Valor recibido: </label>
                    <input class="form__inputs" id="valor-recibido" type="text" name="valor_recibido">
                </div>
                <div class="container_inputs">
                    <label for="valor-devuelto">Valor devuelto: </label>
                    <input class="form__inputs" id="valor-devuelto" type="text" name="valor-devuelto" readonly>
                </div>
                <div class="container_inputs">
                    <label for="tipo-pago">Selcceionar Tipo de Pago: </label>
                    <select class="form__inputs" name="tipo_pago" id="tipo-pago">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta-débito">Targeta Débito</option>
                        <option value="tarjeta-crédito">Targeta Crédito</option>
                        <option value="cheque">Cheque</option>
                        <option value="transferencia-electrónica">Transferencia Electrónica</option>
                    </select>
                </div>
            </div>
        </form>
        <div class="container__major">
            <div class="container__major-button">
                <button class="search__btn" type="submit" id="finalizar-venta">Finalizar Venta</button>
            </div>
        </div>
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