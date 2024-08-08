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

                //Manejamos la respuesta de la solicitud
                let resultDiv = document.getElementById('results');
                resultDiv.innerHTML = '';

                if(data && data.data.length > 0) {
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

                        const cantidadStr = producto.cantidad?.replace(/,/g, '') || 0;
                        const cantidad = parseFloat(cantidadStr);

                        const costProducString = producto.cost_produ.replace(/,/g, '');
                        const costoProd = parseFloat(costProducString);

                        const preFinProducStr = producto.pre_finpro.replace(/,/g, '');
                        const precioFinProduc = parseFloat(preFinProducStr);

                        const preVentaStr = producto.pre_ventap.replace(/,/g, '');
                        const preVenta = parseFloat(preVentaStr);

                        const preVentaDescStr = producto.pre_ventades?.replace(/,/g, '') || 0;
                        const preVentaDesc = parseFloat(preVentaDescStr);

                        let detalleProduc;

                        if(producto.detalle_product) {
                            detalleProduc = producto.detalle_product;
                        } else {
                            detalleProduc = "Sin Detalle";
                        }

                        let fecActulizacion;

                        if(producto.fech_actual) {
                            fecActulizacion = producto.fech_actual;
                        } else {
                            fecActulizacion = "0000-00-00 00:00:00";
                        }

                        const formattedCantidad = cantidad.toLocaleString('en-US', {
                            minimumFractionDigits: 0, //No mostrar los dos decimales ultimos
                            maximumFractionDigits: 0
                        });

                        const formattedCostoProd = costoProd.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const formattedPrecioFinProduc = precioFinProduc.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const formattedPreVenta = preVenta.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const formattedPreVentaDesc = preVentaDesc.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        let row = `<tr>
                            <td><a href="${BASE_URL}/update-form-product?data=${encodeURIComponent(producto.encrypted)}">${producto.id_product}</a></td>
                            <td>${producto.no_product}</td>
                            <td>${producto.id_marca}</td>
                            <td>${producto.nombre_marca}</td>
                            <td>${formattedCantidad}</td>
                            <td>${formattedCostoProd}</td>
                            <td>${producto.rte_fuente}</td>
                            <td>${producto.flet_produ}</td>
                            <td>${producto.iva_produc}</td>
                            <td>${formattedPrecioFinProduc}</td>
                            <td>${producto.uti_produc}</td>
                            <td>${formattedPreVenta}</td>
                            <td>${producto.desc_produ}</td>
                            <td>${formattedPreVentaDesc}</td>
                            <td>${producto.ren_product}</td>
                            <td>${detalleProduc}</td>
                            <td>${fecActulizacion}</td>
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