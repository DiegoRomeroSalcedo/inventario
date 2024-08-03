<?php

ob_start();

$title = "Insertar Productos";

if(isset($_GET['id'])) {
    $nombre = $_GET['nombre'];
    $marcaget = $_GET['marca'];
    $id = intval($_GET['id']);
    echo '<div class="success_insertion">El producto: ' . $nombre . ' marca: ' . $marcaget . ' se registo con Nro. ' . $id . '</div>';
}

if(isset($_SESSION['error'])) {
    echo '<section class="error__duplicatedentry">' . $_SESSION['error'] . '</section>';
    unset($_SESSION['error']);
}

if(isset($_SESSION['error_sql'])) {
    echo '<section class="error_sql">' . $_SESSION['error_sql'] . '</section>';
    unset($_SESSION['error']);
}

?>

<form class="form_insert" action="<?= BASE_URL . '/add-productos'?>" method="post">
    <h1>Insertar Producto</h1>
    <div class="container_inputs">
        <label for="nom_produc">Nombre Producto: </label>
        <input id="nom_produc" type="text" name="nom_produc" placeholder="Bujía" required>
    </div>
    <div class="container_inputs">
        <label for="nom_marca">Marca Producto: </label>
        <select name="marca_producto" id="nom_marca">
            <?php foreach($data['marca'] as $marca): ?>
            <option value="<?= $marca['id_marca'] ?>">
                <?= $marca['nombre_marca']?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="container_inputs">
        <label for="precio_costo">Precio Costo: </label>
        <input id="precio_costo" type="number" min="0" step="0.01" name="cost_produ" placeholder="97860.89" required>
    </div>
    <div class="container_inputs">
        <label for="retefuente">Retención %: </label>
        <input id="retefuente" type="number" min="0" step="0.01" name="porc_rete" placeholder="2.5" required>
    </div>
    <div class="container_inputs">
        <label for="costo_flete">Flete %: </label>
        <input id="costo_flete" type="number" min="0" step="0.01" name="porc_flete" placeholder="3" required>
    </div>
    <div class="container_inputs">
        <label for="costo_iva">IVA %: </label>
        <input id="costo_iva" type="number" min="0" step="0.01" name="porc_iva" placeholder="19" required>
    </div>
    <div class="container_inputs">
        <label for="costo_final">Costo Final: </label>
        <input id="costo_final" type="number" name="pre_finpro" readonly>
    </div>
    <div class="container_inputs">
        <label for="utilidad">Utilidad: </label>
        <input id="utilidad" type="number" name="uti_product" placeholder="50" required>
    </div>
    <div class="container_inputs">
        <label for="precio_venta">Precio de Venta: </label>
        <input id="precio_venta" type="number" name="pre_ventap" readonly>
    </div>
    <div class="container_inputs">
        <label for="toggleCheckbox">Desea Aplicar descuento</label>
        <input id="toggleCheckbox" type="checkbox" value="1" name="aplica_descuento">
    </div>
    <div id="extrafileds" class="container_inputs hidden">
        <label for="descuento">Descuento %: </label>
        <input id="descuento" type="number" min="0" step="0.01" name="des_product" placeholder="10">
    </div>
    <div id="extrafileds_two" class="container_inputs hidden">
        <label for="precioventa_desc">Precio con Descuento: </label>
        <input id="precioventa_desc" type="number" name="pre_ventades" readonly>
    </div>
    <div class="container_inputs">
        <label for="rentabilidad">Rentabilidad %: </label>
        <input id="rentabilidad" type="number" min="0" step="0.01" name="rentabilidad">
    </div>
    <div class="container__button-form">
        <button class="button_form" type="submit">Insertar</button>
    </div>
</form>

<?php

$content = ob_get_clean();

$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}

include __DIR__ . '/layouts/layout.php';

?>