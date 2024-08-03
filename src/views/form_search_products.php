<?php

ob_start();

use Proyecto\Utils\Encryption;

$title = "Buscar Producto";

?>

<form id="search_product_form" class="form_insert" action="<?= BASE_URL . '/get-add-cantidades'?>" method="post">
    <h1>Buscar Productos</h1>
    <div class="container_inputs">
        <label for="nom_product">Buscar Producto: </label>
        <input id="nom_product" type="text" name="nom_product" placeholder="Bujía" required>
    </div>
    <div class="container_inputs">
        <label for="nom_marca">Marca Producto: </label>
        <select name="marca_producto" id="nom_marca">
            <option value="0">Marcas</option>
            <?php foreach($data['marca'] as $m): ?>
                <option value="<?= $m['id_marca'] ?>" <?= (isset($_POST['marca_producto']) && $_POST['marca_producto'] == $m['id_marca']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['nombre_marca'])?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="container__button-form">
        <button class="button_form" type="submit">Buscar</button>
    </div>
</form>

<div id="results">

</div>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>

        <div id="results">
            <!-- Aqui van los resultados -->
        </div>
    <!-- Pasar los datos a JavaScript -->
<?php endif; ?>

<?php 

$content = ob_get_clean();

$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}


include __DIR__ . '/layouts/layout.php';

?>