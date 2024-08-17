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
                                <th class="th_results">Id Producto</th>
                                <th class="th_results">Nombre Producto</th>
                                <th class="th_results">Id Marca</th>
                                <th class="th_results">Nombre Marca</th>
                                <th class="th_results">Cantidad</th>
                                <th class="th_results">Costo Producto</th>
                                <th class="th_results">Retencion Producto</th>
                                <th class="th_results">Flete Producto</th>
                                <th class="th_results">Iva Producto</th>
                                <th class="th_results">Costo Fin/Pro</th>
                                <th class="th_results">Utilidad Producto</th>
                                <th class="th_results">Precio Venta</th>
                                <th class="th_results">Descuento Producto</th>
                                <th class="th_results">Precio Vent/Desc</th>
                                <th class="th_results">Rentabilida Producto</th>
                                <th class="th_results">Detalle Producto</th>
                                <th class="th_results">Fecha Acutlizacion</th>
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
                            <td class="td_results"><a href="${BASE_URL}/update-form-product?data=${encodeURIComponent(producto.encrypted)}">${producto.id_product}</a></td>
                            <td class="td_results">${producto.no_product}</td>
                            <td class="td_results">${producto.id_marca}</td>
                            <td class="td_results">${producto.nombre_marca}</td>
                            <td class="td_results">${formattedCantidad}</td>
                            <td class="td_results">${formattedCostoProd}</td>
                            <td class="td_results">${producto.rte_fuente}</td>
                            <td class="td_results">${producto.flet_produ}</td>
                            <td class="td_results">${producto.iva_produc}</td>
                            <td class="td_results">${formattedPrecioFinProduc}</td>
                            <td class="td_results">${producto.uti_produc}</td>
                            <td class="td_results">${formattedPreVenta}</td>
                            <td class="td_results">${producto.desc_produ}</td>
                            <td class="td_results">${formattedPreVentaDesc}</td>
                            <td class="td_results">${producto.ren_product}</td>
                            <td class="td_results">${detalleProduc}</td>
                            <td class="td_results">${fecActulizacion}</td>
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