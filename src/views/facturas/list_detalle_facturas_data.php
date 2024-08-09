<?php 

ob_start();

$title = "Listar Facturas";

?>

<div id="pageIdentifier" data-page="search-facturs-data-list">
    <form class="form__search-data" action="<?= BASE_URL . '/facturas' ?>" id="form-list-data" method="post">
        <div class="container-inputs-data" id="container-inputs">
            <label class="labels__inputs" for="num-factur">Numero de factura: </label>
            <input class="form__input-number" type="number" name="id_factur" min="0" required>
        </div>
    </form>
    <div id="results">
        <!-- Resultados de la busqueda -->
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