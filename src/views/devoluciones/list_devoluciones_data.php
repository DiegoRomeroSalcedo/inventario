<?php

ob_start();

$title = "Listar devoluciones";
echo "<pre>";
print_r($data['data'][0]);
echo "</pre>";

?>



<?php

//Inclusion de estilos externos
$stylesLibraries = '';

foreach ($this->getStylesLibraries() as $style) {
    $stylesLibraries .= '<link rel="stylesheet" href="' . $style . '">';
}

//inclusion de librerias
$librariesHtml = '';

foreach($this->getLibraries() as $library) {
    $librariesHtml  .= '<script src="' .$library.'"></script>';
}

//inclusion de scripts
$scriptsHtml = '';

foreach ($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="'.$script. '"></script>';
}

$content = ob_get_clean();

include __DIR__ . '/../layouts/layout.php';

?>