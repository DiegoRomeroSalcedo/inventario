document.addEventListener('DOMContentLoaded', function() {

    let form = document.getElementById('search-update-product');

    if(form) {
        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Evitamos el envio normal del form

            const BASE_URL = '/inventario/public';

            let formData = new FormData(this);

            try {
                let response = await fetch(`${BASE_URL}/search-update-productos`, {
                    method: 'POST',
                    body: formData
                });

                if(!response.ok) {
                    throw new Error('Network reponse was not OK');
                }

                let data = await response.json();

                console.log(data);

                //Manejamos la respuesta de la solicitud
                let resultDiv = document.getElementById('results');
                resultDiv.innerHTML = '';

                if(data && data.data.length > 0) {
                    console.log("Hola");
                    //Formateamos el template string
                    let tableHTML = `<table class="tabla_resultados">
                        <thead>
                            <tr>
                                <th>Id Producto</th>
                                <th>Nombre Producto</th>
                                <th>Id Marca</th>
                                <th>Nombre Marca</th>
                                <th>Cantidad</th>
                                <th>Costo Producto</th>
                                <th>Retencion Producto</th>
                                <th>Flete Producto</th>
                                <th>Iva Producto</th>
                                <th>Costo Fin/Pro</th>
                                <th>Utilidad Producto</th>
                                <th>Precio Venta</th>
                                <th>Descuento Producto</th>
                                <th>Precio Vent/Desc</th>
                                <th>Rentabilida Producto</th>
                                <th>Detalle Producto</th>
                                <th>Fecha Acutlizacion</th>
                            <tr>
                        <thead>
                        <tbody>`;

                    data.data.forEach(producto => {
                        let row = `<tr>
                            <td><a href="${BASE_URL}/update-form-product?data=${encodeURIComponent(producto.encrypted)}">${producto.id_product}</a></td>
                            <td>${producto.no_product}</td>
                            <td>${producto.id_marca}</td>
                            <td>${producto.nombre_marca}</td>
                            <td>${producto.cantidad}</td>
                            <td>${producto.cost_produ}</td>
                            <td>${producto.rte_fuente}</td>
                            <td>${producto.flet_produ}</td>
                            <td>${producto.iva_produc}</td>
                            <td>${producto.pre_finpro}</td>
                            <td>${producto.uti_produc}</td>
                            <td>${producto.pre_ventap}</td>
                            <td>${producto.desc_produ}</td>
                            <td>${producto.pre_ventades}</td>
                            <td>${producto.ren_product}</td>
                            <td>${producto.detalle_product}</td>
                            <td>${producto.fech_actual}</td>
                        </tr>`;
                        tableHTML += row; // concatenamos las filas
                    });

                    tableHTML += `</tbody></table>`;
                    resultDiv.innerHTML = tableHTML; // Agregamos la tabla
                } else {
                    resultDiv.innerHTML = '<P>No se hallarón resultados</p>';
                }
            } catch (error) {
                console.error("Error: ", error);
            }
        });
    } else {
        console.error('Fornulario no encontrado')
    }
});