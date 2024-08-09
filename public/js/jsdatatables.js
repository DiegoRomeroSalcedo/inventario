// Función para inicializar la tabla #example
function initializeExampleTable() {
    let table = $('#example').DataTable({
        scrollX: true,
        buttons: [
            'excel', 'pdf'
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

function inicializeDataTableClientes() {
    let table = $('#clientes').DataTable({
        scrollX: true,
        buttons: [
            'excel', 'pdf',
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

    table.buttons().container().prependTo(table.table().container());
}


document.addEventListener('DOMContentLoaded', function() {
    const pageIdentifier = document.getElementById('pageIdentifier');
    const page = pageIdentifier ? pageIdentifier.getAttribute('data-page'): '';

    if(page === 'clientes' || page === 'facturas') {
        inicializeDataTableClientes();
    } else {
        initializeExampleTable();
    }
});



