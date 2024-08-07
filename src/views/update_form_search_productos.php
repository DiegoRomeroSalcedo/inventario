<?php

ob_start();

$title = "Buscar Producto";

?>

<div id="pageIdentifier" data-page="search-update-producto">
    <form id="search-update-product" action="<?= BASE_URL . '/search-update-productos'?>" method="post">
        <div class="container_inputs">
            <label for="idProduct">Id Producto: </label>
            <input id="idProduct" type="number" name="id_producto" required>
        </div>
        <div class="container_inputs">
            <label for="nom_marca">Marca Producto: </label>
            <select name="marca_producto" id="nom_marca">
                <option value="0">Marcas</option>
                <?php foreach($data['marcas'] as $m): ?>
                    <option value="<?= $m['id_marca'] ?>">
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
            <!-- Contenido del resultado -->
    </div>
</div>

<?php  

// inclusion de scripts
$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}

$content = ob_get_clean();

include __DIR__ . '/layouts/layout.php';

?>