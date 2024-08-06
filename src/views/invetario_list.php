<?php

ob_start();

$title = "Tabla Inventario";


// echo "<pre>";
// print_r($data['defaultData']);
// echo "</pre";
?>

<div id="pageIdentifier" data-page="invenatrio">

<form id="searchForm" action="<?= BASE_URL . '/inventario'?>" method="post">
    <div class="container_inputs">
            <label for="nom_marca">Marca Producto: </label>
            <select name="nom_marca" id="nom_marca">
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
            <label for="nom_producto">Nombre Producto: </label>
            <select name="marca_producto" id="nom_producto">
                <option value="0">Seleccionar Producto</option>
                <?php foreach($data['idYprodu'] as $marca): ?>
                <option value="<?= $marca['id_product'] ?>">
                    <?= $marca['no_product']?>
                </option>
                <?php endforeach; ?>
            </select>
    </div>
    <div class="container_inputs">
            <label for="det_product">Detalle Producto: </label>
            <textarea name="detalle_pro" id="det_product"></textarea>
        </div>
    <div>
    <div class="container_inputs">
            <label for="usr_insercion">Usuario Insercion: </label>
            <select name="usr_insercion" id="">
                <option value="0">Usuario Insercion: </option>
                <?php foreach($data['usr_insact'] as $marca): ?>
                <option value="<?= $marca['id_user'] ?>">
                    <?= $marca['nombre_usr']?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    <div>
    <div class="container_inputs">
            <label for="usr_actua">Usuario Actualizacion: </label>
            <select name="usr_actualiza" id="">
                <option value="0">Usuario Actualizacion</option>
                <?php foreach($data['usr_insact'] as $marca): ?>
                <option value="<?= $marca['id_user'] ?>">
                    <?= $marca['nombre_usr']?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    <div>
    <div>
        <p>Filtrar por Fecha:</p>
        <label for="startDate">Fecha Inicio:</label>
        <input type="text" id="startDate" name="startDate">
        <label for="endDate">Fecha Fin:</label>
        <input type="text" id="endDate" name="endDate">
        <button id="searchBtninventario" type="submit">Buscar</button>
        <p id="error-message" style="color: red; display: none;"></p>
    </div>
</form>

<table id="inventoryTable" class="display nowrap" style="width:100%">
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
            <th>Detalles Producto</th>
            <th>Total Precio Final</th>
            <th>Usuario Inserción</th>
            <th>Fecha Inserción</th>
            <th>Usuario Actualizacion</th>
            <th>Fecha Actualizacion</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['defaultData'] as $data): ?>
            <?php 
                if(!isset($data['cantidad'])) {
                    $costoFinal = $data['pre_finpro'];
                } else {
                    $costoFinal = $data['pre_finpro'] * $data['cantidad'];
                }
            ?>
            <tr>
                <td><?= htmlspecialchars($data['id_product'])?></td>
                <td><?= htmlspecialchars($data['no_product'])?></td>
                <td><?= htmlspecialchars($data['id_marca'])?></td>
                <td><?= htmlspecialchars($data['nombre_marca'])?></td>
                <td><?= htmlspecialchars($data['cantidad'])?></td>
                <td><?= htmlspecialchars($data['cost_produ'])?></td>
                <td><?= htmlspecialchars($data['rte_fuente'])?></td>
                <td><?= htmlspecialchars($data['flet_produ'])?></td>
                <td><?= htmlspecialchars($data['iva_produc'])?></td>
                <td><?= htmlspecialchars($data['pre_finpro'])?></td>
                <td><?= htmlspecialchars($data['uti_produc'])?></td>
                <td><?= htmlspecialchars($data['pre_ventap'])?></td>
                <td><?= htmlspecialchars($data['desc_produ'])?></td>
                <td><?= htmlspecialchars($data['pre_ventades'])?></td>
                <td><?= htmlspecialchars($data['ren_product'])?></td>
                <td><?= htmlspecialchars($data['detalle_product'])?></td>
                <td><?= htmlspecialchars(number_format($costoFinal, 2, '.', ','))?></td>
                <td><?= htmlspecialchars($data['user_actual'])?></td>
                <td><?= htmlspecialchars($data['fech_actual'])?></td>
                <td><?= htmlspecialchars($data['user_actual'])?></td>
                <td><?= htmlspecialchars($data['fech_actual'])?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php

//Inclusion de estilos externos
$stylesLibraries = '';

foreach ($this->getStylesLibraries() as $style) {
    $stylesLibraries .= '<link rel="stylesheet" href="' . $style . '">';
}

//inclusion de librerias externas
$librariesHtml = '';

foreach($this->getLibraries() as $libraries) {
    $librariesHtml .= '<script src="' .$libraries.'"></script>';
}

//inclusion de scripts
$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}


$content = ob_get_clean();


include __DIR__ . '/layouts/layout.php';