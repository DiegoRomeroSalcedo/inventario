<?php

ob_start();

use Proyecto\Utils\Encryption;

$title = "Buscar Producto";

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    if(isset($_GET['data']) && !empty($_GET['data'])) {
        $encriptedData = $_GET['data'];
        $desencrytedData = Encryption::decrypt($encriptedData);

        if($_GET['tipo'] == 'actualizado') {
            $id = $desencrytedData['id'];
            $nombre = $desencrytedData['nombre'];
            $cantidad = $_GET['cantidad'];
            echo '<div class="success_insertion"> Se actualizo la cantidad del producto' . $nombre . ' con ID: ' . $id . ' a ' . $cantidad . '</div>';
        } elseif($_GET['tipo'] == 'insertado') {
            $id = $desencrytedData['id'];
            $nombre = $desencrytedData['nombre'];
            $cantidadinsertada = $desencrytedData['cantidad'];
            echo '<div class="success_insertion">Se inserto la cantidad de: ' . $cantidadinsertada . ' para el producto ' . $nombre . ' con ID: ' . $id . '</div>';
        }
    }

    if(isset($_SESSION['error'])) {
        echo '<section class="error__duplicatedentry">' . $_SESSION['error'] . '</section>';
        unset($_SESSION['error']);
    }
    
    if(isset($_SESSION['error_sql'])) {
        echo '<section class="error_sql">' . $_SESSION['error_sql'] . '</section>';
        unset($_SESSION['error_sql']);
    }
}


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

<?php 

$content = ob_get_clean();

$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}

$stylesCss = '';

foreach ($this->getStyles() as $style) {
    $stylesCss .= '<link rel="stylesheet" href="' . $style . '">';
}


include __DIR__ . '/../layouts/layout.php';

?>