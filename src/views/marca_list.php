<?php
ob_start();

$title = "Listado de marcas";
?>
<main>
    <h1>Listado de Marcas</h1>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>usuarioInsercion</th>
                <th>FechaInsercion</th>
                <th>UsuarioActualizacion</th>
                <th>FechaActualizacion</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($marcas as $marca): ?>
                <tr>
                    <td><?= htmlspecialchars($marca['id_marca']) ?></td>
                    <td><?= htmlspecialchars($marca['nombre_marca']) ?></td>
                    <td><?= htmlspecialchars($marca['usuario_insercion']) ?></td>
                    <td><?= htmlspecialchars($marca['fecha_insercion']) ?></td>
                    <td><?= htmlspecialchars($marca['usuario_actualizacion']) ?></td>
                    <td><?= htmlspecialchars($marca['fecha_actualizacion']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php

$stylesLibraries = '';

foreach ($this->getStylesLibraries() as $style) {
    $stylesLibraries .= '<link rel="stylesheet" href="' . $style . '"><br>';
}

$librariesHtml = '';

foreach($this->getLibraries() as $library) {
    $librariesHtml  .= '<script src="' .$library.'"></script><br>';
}

$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script><br>';
}

$content = ob_get_clean();

include __DIR__ . '/layouts/layout.php';

?>