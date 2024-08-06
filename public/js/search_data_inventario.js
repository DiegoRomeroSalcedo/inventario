document.addEventListener('DOMContentLoaded', () => {
    // Inicializa DataTable solo si no está inicializado
    if (!$.fn.DataTable.isDataTable('#inventoryTable')) {
        let table = $('#inventoryTable').DataTable({
            scrollX: true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Mueve el contenedor de botones a la tabla
        table.buttons().container().prependTo(table.table().container());
    }
    
    // Manejar el formulario de búsqueda
    document.querySelector('#searchForm').addEventListener('submit', event => {
        event.preventDefault();
        fetchInventory();
    });
});

async function fetchInventory() {
    const formData = new FormData(document.querySelector('#searchForm'));
    const params = new URLSearchParams(formData);

    const BASE_URL = '/inventario/public';

    const response = await fetch(`${BASE_URL}/inventario`, {
        method: 'POST',
        body: params
    });

    if (response.ok) {
        const data = await response.json();
        console.log(data);
        updateTable(data);
    } else {
        console.log('Error Fetching inventory Data');
    }
}

function updateTable(data) {
    // Usa DataTables API para actualizar la tabla
    const table = $('#inventoryTable').DataTable();

    // Limpia los datos existentes
    table.clear();

    // Añadir nuevos datos
    table.rows.add(data.array.map(item => [
        item.id_product,
        item.no_product,
        item.id_marca,
        item.nombre_marca,
        item.cantidad,
        item.cost_produ,
        item.rte_fuente,
        item.flet_produ,
        item.iva_produc,
        item.pre_finpro,
        item.uti_produc,
        item.pre_ventap,
        item.desc_produ,
        item.pre_ventades,
        item.ren_product,
        item.fech_actual
    ])).draw();
}
