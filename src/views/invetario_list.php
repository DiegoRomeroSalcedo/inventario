<?php

ob_start();

$title = "Inventario";

?>
<div class="container_inputs">
        <label for="nom_marca">Marca Producto: </label>
        <select name="marca_producto" id="nom_marca">
            <option value="0">Seleccionar Marca</option>
            <?php foreach($data['idYmarca'] as $marca): ?>
            <option value="<?= $marca['id_marca'] ?>">
                <?= $marca['nombre_marca']?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
<div>
<div class="container_inputs">
        <label for="nom_marca">Marca Producto: </label>
        <select name="marca_producto" id="">
            <option value="0">Seleccionar Producto</option>
            <?php foreach($data['idYprodu'] as $marca): ?>
            <option value="<?= $marca['id_product'] ?>">
                <?= $marca['no_product']?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
<div>
    <label for="startDate">Fecha Inicio:</label>
    <input type="text" id="startDate">
    <label for="endDate">Fecha Fin:</label>
    <input type="text" id="endDate">
    <button id="searchBtninventario">Buscar</button>
    <p id="error-message" style="color: red; display: none;"></p>
</div>

<table id="example" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th>ID PRODUCTO</th>
            <th>NOMBRE PRODUCTO</th>
            <th>ID MARCA</th>
            <th>NOMBRE MARCA</th>
            <th>CANTIDAD</th>
            <th>COSTO PRODUCTO</th>
            <th>RETENCION</th>
            <th>FLETE</th>
            <th>IVA</th>
            <th>PRECIO PRODUCTO</th>
            <th>UTILIDAD</th>
            <th>PRECIO VENTA</th>
            <th>DESCUENTO</th>
            <th>PRECIO VENTA DESCUENTO</th>
            <th>RENTABILIDAD</th>
            <th>TOTAL COSTO</th>
            <th>TOTAL PRECIO VENTA</th>
        </tr>
    </thead>
</table>

<?php

$libraries = '';

foreach($this->getLibraries() as $libraries) {
    $libraries .= '<script src="' .$libraries.'"></script>';
}

$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}


$content = ob_get_clean();


include __DIR__ . '/layouts/layout.php';