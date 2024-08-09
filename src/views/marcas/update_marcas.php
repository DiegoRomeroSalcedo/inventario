<?php

ob_start();

$title = "Actualizar Marca";

$id_marca = $data['decrypted']['id_marca'];
$nombre = $data['decrypted']['nombre_marca'];

?>

    <form id="update_form" action="<?= BASE_URL . '/update-form-marca'?>" method="post">
    <div class="container_inputs">
        <label for="nom_marca">Nombre Marca</label>
        <input id="nom_marca" type="text" name="nombre_marca" value="<?= $nombre ?>">
    </div>
    <div class="container_inputs">
        <input id="id_marca" type="hidden" name="id_marca" value="<?= $id_marca ?>">
    </div>
    <div class="container__button-form">
        <button class="button_form" type="submit">Actualizar</button>
    </div>
    </form>

</div>

<?php 

$content = ob_get_clean();

include __DIR__ . '/../layouts/layout.php';