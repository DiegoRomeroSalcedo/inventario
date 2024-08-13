document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-seacrh-factura-devolucion');
    const BASE_URL = '/inventario/public';
    let listProductos = document.getElementById('listado-productos-factura');
    const totalFactura = document.getElementById('factura-total');
    const valorRecibidoFactura = document.getElementById('factura-valor-recibido');
    const ValorDevueltoFactura = document.getElementById('factura-valor-devuelto');
    const tipoPagoFactura = document.getElementById('factura-tipo-pago');
    const clienteFactura = document.getElementById('factura-cliente');
    const nombreClienteFactura = document.getElementById('factura-nombre');
    const telefonoClienteFactura = document.getElementById('factura-telefono');

    // Seccion tributaria
    const idFactura = document.getElementById('input-factura');
    const facturaDisplay = document.getElementById('input-factura-div');
    const totalDevolucionInput = document.getElementById('total-devolucion');

    // Finalizar la devolucion
    const finalizarDevoBtn = document.getElementById('finalizar-devolucion-btn');

    // Datos a insertarBD
    let totalDevolucionBd = 0;
    let dataProductosDevolucion = []; // Almacenaremos los distintos productos

    if (form) {
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            let response = await fetch(`${BASE_URL}/search-factura-devolucion`, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Network response was not OK');
            }

            let data = await response.json();

            // Procesamos los datos para mostrarlos en pantalla
            listProductos.innerHTML = '';

            data.forEach(data => {
                let cantidad = 1;
                let lisItem = document.createElement('li');
                let canDevoFormatted = parseInt(cantidad, 10);

                let productData = {
                    id_producto: data.id_producto,
                    precio_unitario: data.precio_unitario,
                    cantidad: data.cantidad,
                    monto_venta: data.monto_venta,
                    descuento_aplicado: data.descuento_aplicado,
                    cantidadDevolucion: canDevoFormatted
                };

                lisItem.innerHTML = `
                    Id Producto: ${data.id_producto} - Nombre: ${data.no_product} - Marca: ${data.nombre_marca} - Precio Unitario: ${data.precio_unitario} - Cantidad: ${data.cantidad} - Monto Vendido: ${data.monto_venta} - Descuento Aplicado: ${data.descuento_aplicado}
                    <div>
                        <span>Reinsertar Stock</span>
                        <label for="reinsercion-stock-${data.id_producto}-si">Sí:</label>
                        <input id="reinsercion-stock-${data.id_producto}-si" type="radio" name="reinsercion-stock-${data.id_producto}" value="1">
                        <label for="reinsercion-stock-${data.id_producto}-no">No:</label>
                        <input id="reinsercion-stock-${data.id_producto}-no" type="radio" name="reinsercion-stock-${data.id_producto}" value="2">
                    </div>
                    <div>Cantidad Devolver: <span class="cantidad">${cantidad}</span></div>
                    <button class="add-cantidad" cantidad-vendida="${data.cantidad}">+</button>
                    <button class="remove-cantidad">-</button>
                    <button class="insert-wait" data-product='${JSON.stringify(productData)}'>Añadir a Devoluciones</button>
                `;

                listProductos.appendChild(lisItem);
            });

            document.querySelectorAll('.add-cantidad').forEach(button => {
                button.addEventListener('click', function() {
                    let cantidadShow = this.previousElementSibling.querySelector('.cantidad');
                    let cantidad = parseInt(cantidadShow.textContent, 10);
                    let cantidadVendida = parseInt(this.getAttribute('cantidad-vendida'), 10);

                    cantidad += 1;

                    if (cantidad > cantidadVendida) {
                        alert('Superó el límite de la cantidad previamente vendida.');
                        cantidad -= 1;
                    }
                    cantidadShow.textContent = cantidad;
                });
            });

            document.querySelectorAll('.remove-cantidad').forEach(button => {
                button.addEventListener('click', function() {
                    let cantidadShow = this.previousElementSibling.previousElementSibling.querySelector('.cantidad');
                    let cantidad = parseInt(cantidadShow.textContent, 10);

                    cantidad -= 1;

                    if (cantidad < 1) {
                        alert('Límite mínimo para devolución de 1.');
                        cantidad += 1;
                    }

                    cantidadShow.textContent = cantidad;
                });
            });

            totalFactura.innerHTML = `Total: ${data[0].total_venta}`;
            valorRecibidoFactura.innerHTML = `Valor Recibido: ${data[0].valor_recibido}`;
            ValorDevueltoFactura.innerHTML = `Valor Devuelto: ${data[0].valor_devuelto}`;
            tipoPagoFactura.innerHTML = `Tipo de Pago: ${data[0].tipo_pago}`;
            clienteFactura.innerHTML = `Cédula: ${data[0].identificacion}`;
            nombreClienteFactura.innerHTML = `Nombre Cliente: ${data[0].Nombre}`;
            telefonoClienteFactura.innerHTML = `Teléfono: ${data[0].telefono}`;

            document.querySelectorAll('.insert-wait').forEach(button => {
                button.addEventListener('click', function() {
                    let product = JSON.parse(this.getAttribute('data-product'));
                    let cantidadShow = this.parentElement.querySelector('.cantidad');
                    let cantidad = parseInt(cantidadShow.textContent, 10);

                    let productoDevolucion = {
                        id_producto: product.id_producto,
                        cantidad: cantidad,
                        precioUnitario: product.precio_unitario,
                        montoDevolucion: product.precio_unitario * cantidad,
                        reinsercion: document.querySelector(`input[name="reinsercion-stock-${product.id_producto}"]:checked`)?.value || null
                    };

                    console.log(productoDevolucion);

                    dataProductosDevolucion.push(productoDevolucion);

                    const listDataTribute = document.getElementById('datos-tributarios');
                    let listItem = document.createElement('tr');
                    let montoDevolucion = product.precio_unitario * cantidad;

                    totalDevolucionBd += montoDevolucion;

                    listItem.innerHTML = `
                        <td>Id Producto: ${product.id_producto}</td>
                        <td>Cantidad Devolución: ${cantidad}</td>
                        <td>Total Devolución: ${montoDevolucion}</td>
                        <td>Reinserción Stock: ${productoDevolucion.reinsercion ? (productoDevolucion.reinsercion === '1' ? 'Sí' : 'No') : 'No especificado'}</td>
                    `;

                    listDataTribute.appendChild(listItem);

                    totalDevolucionInput.value = totalDevolucionBd;
                    facturaDisplay.textContent = `Id Factura: ${idFactura.value}`;
                });
            });

            finalizarDevoBtn.addEventListener('click', function() {
                const idFacturaBd = parseInt(facturaDisplay.textContent.replace(/\D/g, ''));
                const motivo = document.getElementById('motivo-devolucion').value;

                const dataDevolucion = {
                    idFactura: idFacturaBd,
                    motivo: motivo,
                    total: totalDevolucionBd,
                    productos: dataProductosDevolucion,
                };

                fetch(`${BASE_URL}/add-devolucion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataDevolucion)
                })  
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not OK');
                    }

                    return response.json(); // Convertir la respuesta a JSON
                })
                .then(data => {
                    if(data.success) {
                        alert('Devolucion realizada');
                        location.reload();
                    } else {
                        alert(`Error: ${data.message}`);
                        location.reload();
                        throw new Error(`Error en la Devolucion: ${data.message}`);
                    }
                })
                .catch(error => {
                    // Manejo de errores en caso de que ocurra una excepción
                    console.error('Error en la solicitud:', error);
                });
            });
        });
    } else {
        console.error("Formulario No encontrado");
    }
});
