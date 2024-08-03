document.addEventListener('DOMContentLoaded', function() {
    let form = document.getElementById('search_product_form');
    if (form) { // Verifica que el formulario existe
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío normal del formulario

            const BASE_URL = '/inventario/public';
            var formData = new FormData(this);

            fetch('/inventario/public/get-add-cantidades', { // La ruta debe ser la misma que en tu configuración de rutas
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Asegúrate de que tu servidor responda en formato JSON
            .then(data => {
                // Maneja la respuesta aquí, por ejemplo, actualizando la vista con los resultados
                let resultsDiv = document.getElementById('results');
                resultsDiv.innerHTML = ''; // Limpia el contenido anterior
                
                if (data.productos && data.productos.length > 0) {
                    let tableHtml = `<table>
                        <thead>
                            <tr>
                                <th>Id_Producto</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Costo Producto</th>
                                <th>Costo Final</th>
                                <th>Precio Venta</th>
                                <th>Precio con Descuento</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    data.productos.forEach(producto => {
                        let row = `<tr>
                            <td><a href="${BASE_URL}/add-cantidad-product?data=${encodeURIComponent(data.encriptados)}">${producto.id_product}</a></td>
                            <td>${producto.no_product}</td>
                            <td>${data.marca.find(marca => marca.id_marca === producto.id_marcapr)?.nombre_marca || 'Desconocida'}</td>
                            <td>${producto.cost_produ}</td>
                            <td>${producto.pre_finpro}</td>
                            <td>${producto.pre_ventap}</td>
                            <td>${producto.pre_ventades}</td>
                        </tr>`;
                        tableHtml += row; // Agrega cada fila de producto
                    });
                    
                    tableHtml += `</tbody></table>`;
                    resultsDiv.innerHTML = tableHtml; // Agrega la tabla al div de resultados
                } else {
                    resultsDiv.innerHTML = '<p>No se encontraron productos.</p>';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    } else {
        console.error('Formulario no encontrado');
    }
});
