// Función para inicializar la tabla #example
function initializeExampleTable() {
    let table = $('#example').DataTable({
        scrollX: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });

    // Mueve el contenedor de botones a la tabla
    table.buttons().container().prependTo(table.table().container());
}

// Función para inicializar la tabla #inventoryTable //Lo quitamos ya que esta carga la estamos haciedno desde el js de inventario
// function initializeInventoryTable() {
//     let table = new DataTable('#inventoryTable');
//     new DataTable.Buttons(table, {
//         buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
//     });
//     table.buttons(0, null).container().prependTo(table.table().container());
// }

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



