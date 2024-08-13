<?php

ob_start();

$title = "Insertar Devolucion";

?>

<div class="input-container">
    <form id="form-seacrh-factura-devolucion" action="<?= BASE_URL . '/search-factura-devolucion'?>" method="post">
        <label for="input-factura">Nro. Factura</label>
        <input id="input-factura" type="number" name="id_factura" required>
        <button id="searhc-factur-btn" type="submit">Buscar</button>
    </form>
</div>
<div id="results">
    <!-- Resultados de la busqueda -->
    <h1>Detalles Factura</h1>
    <ul id="listado-productos-factura"></ul>
    <div class="detalles-factura">
        <div id="factura-total"></div>
        <div id="factura-valor-recibido"></div>
        <div id="factura-valor-devuelto"></div>
        <div id="factura-tipo-pago"></div>
        <div id="factura-cliente"></div>
        <div id="factura-nombre"></div>
        <div id="factura-telefono"></div>
    </div>
    
    <h2>Insertar Devolucion</h2>
    <div id="input-factura-div"></div>
    <table id="datos-tributarios" cellspacing="2px"></table>
    <label for="total-devolucion">Total Devolucion:</label>
    <input type="text" name="total_devolucion" id="total-devolucion">
    <label for="motivo-devolucion">Motivo Devolucion: </label>
    <textarea name="motivo_devolucion" id="motivo-devolucion"></textarea>
    <button id="finalizar-devolucion-btn" type="submit">Finalizar Devolucion</button>
</div>

<?php

$scriptsHtml = '';

foreach($this->getScripts() as $script) {
    $scriptsHtml .= '<script src="' .$script.'"></script>';
}

$content = ob_get_clean();

include __DIR__ . '/../layouts/layout.php';

