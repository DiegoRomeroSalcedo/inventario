// Función para inicializar la tabla #example
function initializeExampleTable() {
    let table = new DataTable('#example');
    new DataTable.Buttons(table, {
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
    table.buttons(0, null).container().prependTo(table.table().container());
}

// Función para inicializar la tabla #inventoryTable
function initializeInventoryTable() {
    let table = new DataTable('#inventoryTable');
    new DataTable.Buttons(table, {
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
    table.buttons(0, null).container().prependTo(table.table().container());
}

// Inicializa las tablas cuando el documento está listo
document.addEventListener('DOMContentLoaded', function() {
    const pageIdentifier = document.getElementById('pageIdentifier');
    const page = pageIdentifier ? pageIdentifier.getAttribute('data-page'): '';

    if(page === 'invenatrio') {
        // initializeInventoryTable();
    } else {
        initializeExampleTable();
    }
});



