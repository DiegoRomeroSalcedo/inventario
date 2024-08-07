<?php

ob_start();

$title = "Tabla Inventario";

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
            <th>CANTIDAD PRODUCTO</th>
            <th>COSTO PRODUCTO</th>
            <th>RETENCION PRODUCTO</th>
            <th>FLETE PRODUCTO</th>
            <th>IVA PRODUCTO</th>
            <th>COSTO FINAL PRODUCTO</th>
            <th>UTILIDAD PRODUCTO</th>
            <th>PRECIO DE VENTA</th>
            <th>DESCUENTO PRODUCTO</th>
            <th>PRECIO VENTA DESCUENTO</th>
            <th>RENTABILIDAD PRODUCTO</th>
            <th>DETALLE PRODUCTO</th>
            <th>TOTAL COSTO FINAL</th>
            <th>TOTAL PRECIO VENTA</th>
            <th>DIF COS/FINAL PRE/VENTA</th>
            <th>TOTAL PRECIO VENT/DESC</th>
            <th>DIF COS/FINAL VENT/DESC</th>
            <th>USUARIO INSERCIÓN</th>
            <th>FECHA INSERCION</th>
            <th>USUARIO ACTUALIZACION</th>
            <th>FECHA ACTUALIZACION</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['defaultData'] as $data): ?>
            <?php 

                // Elimina las comas del costo del producto y convierte a número flotante
                $precioFinProstr = (float) str_replace(',', '', $data['pre_finpro']);
                $precioVentaString = (float) str_replace(',', '', $data['pre_ventap']);
                $precioVentaDescue = (float) str_replace(',', '', $data['pre_ventades']);

                // Convierte la cantidad a número flotante, manejando posibles valores nulos o vacíos
                $cantidad = isset($data['cantidad']) ? (float) $data['cantidad'] : 0;

                if ($cantidad === 0) {
                    $totalprecioFinPro = $precioFinProstr;
                    $totalPrecioVenPro = $precioVentaString;
                    $difeCostoFinPrecioVen = $totalPrecioVenPro - $totalprecioFinPro;
                    $totalPrecioVentDescue = $precioVentaDescue;
                    $difCostFinPrecVenDesc = $totalPrecioVentDescue - $totalprecioFinPro;
                } else {
                    $totalprecioFinPro = $precioFinProstr * $cantidad;
                    $totalPrecioVenPro = $precioVentaString * $cantidad;
                    $difeCostoFinPrecioVen = $totalPrecioVenPro - $totalprecioFinPro;
                    $totalPrecioVentDescue = $precioVentaDescue * $cantidad;
                    $difCostFinPrecVenDesc = $totalPrecioVentDescue - $totalprecioFinPro;
                }

                // Formatea el total con comas y dos decimales
                $formattedTotalprecioFinPro = number_format($totalprecioFinPro, 2, '.', ',');
                $formatttedTotalPrecioVenPro = number_format($totalPrecioVenPro, 2, '.', ',');
                $formttedDifCostFinPrecVenta = number_format($difeCostoFinPrecioVen, 2, '.', ',');
                $formattedTotalPrecioVentDescue = number_format($totalPrecioVentDescue, 2, '.', ',');
                $formattedDifCostFinPrecVenDesc = number_format($difCostFinPrecVenDesc, 2, '.', ',');
            ?>
            <tr>
                <td><?= htmlspecialchars($data['id_product'])?></td>
                <td><?= htmlspecialchars($data['no_product'])?></td>
                <td><?= htmlspecialchars($data['id_marca'])?></td>
                <td><?= htmlspecialchars($data['nombre_marca'])?></td>
                <td><?= htmlspecialchars($data['cantidad'])?></td>
                <td><?= htmlspecialchars(number_format((float) str_replace(',', '', $data['cost_produ']), 2, '.', ',')) ?></td>
                <td><?= htmlspecialchars($data['rte_fuente'])?></td>
                <td><?= htmlspecialchars($data['flet_produ'])?></td>
                <td><?= htmlspecialchars($data['iva_produc'])?></td>
                <td><?= htmlspecialchars(number_format((float) str_replace(',', '', $data['pre_finpro']), 2, '.', ',')) ?></td>
                <td><?= htmlspecialchars($data['uti_produc'])?></td>
                <td><?= htmlspecialchars(number_format((float) str_replace(',', '', $data['pre_ventap']), 2, '.', ',')) ?></td>
                <td><?= htmlspecialchars($data['desc_produ'])?></td>
                <td><?= htmlspecialchars(number_format((float) str_replace(',', '', $data['pre_ventades']), 2, '.', ',')) ?></td>
                <td><?= htmlspecialchars($data['ren_product'])?></td>
                <td><?= htmlspecialchars($data['detalle_product'])?></td>
                <th><?= htmlspecialchars($formattedTotalprecioFinPro) ?></th>
                <th><?= htmlspecialchars($formatttedTotalPrecioVenPro) ?></th>
                <th><?= htmlspecialchars($formttedDifCostFinPrecVenta) ?></th>
                <th><?= htmlspecialchars($formattedTotalPrecioVentDescue) ?></th>
                <th><?= htmlspecialchars($formattedDifCostFinPrecVenDesc) ?></th>
                <td><?= htmlspecialchars($data['usuario_insercion'])?></td>
                <td><?= htmlspecialchars($data['feha_insercion'])?></td>
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