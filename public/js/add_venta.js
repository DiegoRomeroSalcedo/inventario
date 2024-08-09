document.addEventListener('DOMContentLoaded', function() {
    let form = document.getElementById('search-add-venta');
    let carritoList = document.getElementById('carrito-list');
    let carrito = [];
    let ced_cliente = document.getElementById('ced_cliente');
    const BASE_URL = '/inventario/public';
    const productStockMap = {};

    if (form) {
        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Evitamos el envío del formulario de manera normal
            let formData = new FormData(this);

            try {
                let response = await fetch(`${BASE_URL}/search-add-venta`, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }

                let data = await response.json();
                let resultsDiv = document.getElementById('results');

                resultsDiv.innerHTML = '';

                if (data && data.length > 0) {
                    data.forEach(producto => {

                        let cantidad = 1;
                        let cantidadStock;

                        if (productStockMap[producto.id_product] !== undefined) {
                            cantidadStock = productStockMap[producto.id_product];
                        } else {
                            cantidadStock = producto.cantidad ?? 0;
                        }

                        let preVentaStr = producto.pre_ventap.replace(/,/g, '');
                        let preVenta = parseFloat(preVentaStr);
                        let preventaFinStr = producto.pre_ventades.replace(/,/g, '');
                        let preVentaFin = parseFloat(preventaFinStr);

                        const formattedPreVenta = preVenta.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const formattedPreVentaFin = preVentaFin.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        producto.pre_ventades = formattedPreVentaFin;

                        let productDiv = document.createElement('div');
                        productDiv.classList.add('product-item'); // Añadimos la clase
                        productDiv.innerHTML = `
                            <div>Nombre: ${producto.no_product}</div>
                            <div>Marca: ${producto.nombre_marca}</div>
                            <div>Precio: ${formattedPreVenta}</div>
                            <div>Descuento: ${producto.desc_produ}<span>%</span></div>
                            <div>Precio Final: ${formattedPreVentaFin}</div>
                            <div>Detalle: ${producto.detalle_product}</div>
                            <div>Cantidad Stock: <span class="stock">${cantidadStock}</span></div>
                            <div>Cantidad: <span class="cantidad">${cantidad}</span></div>
                            <button class="add-cantidad" data-stock="${cantidadStock}">+</button>
                            <button class="remove-cantidad">-</button>
                            <button class="add-to-cart" data-product='${JSON.stringify(producto)}'>Añadir al carrito</button>
                        `;
                        console.log
                        resultsDiv.appendChild(productDiv);
                    });

                    document.querySelectorAll('.add-cantidad').forEach(button => {
                        button.addEventListener('click', function() {
                            let cantidadElem = this.previousElementSibling.querySelector('.cantidad');
                            let cantidad = parseInt(cantidadElem.textContent, 10);
                            let cantidadStock = parseInt(this.getAttribute('data-stock'), 10);

                            cantidad += 1;

                            if (cantidad > cantidadStock) {
                                alert("Se supero la cantidad en Stock");
                                cantidad -= 1;
                            }
                            cantidadElem.textContent = cantidad;
                        });
                    });

                    document.querySelectorAll('.remove-cantidad').forEach(button => {
                        button.addEventListener('click', function() {
                            let cantidadElem = this.previousElementSibling.previousElementSibling.querySelector('.cantidad');
                            let cantidad = parseInt(cantidadElem.textContent, 10);

                            cantidad -= 1;

                            if (cantidad < 1) {
                                alert('Limite minimo 1');
                                cantidad += 1;
                            }

                            cantidadElem.textContent = cantidad;
                        });
                    });

                    document.querySelectorAll('.add-to-cart').forEach(button => {
                        button.addEventListener('click', function() {
                            let product = JSON.parse(this.getAttribute('data-product'));
                            let cantidadElem = this.previousElementSibling.previousElementSibling.previousElementSibling.querySelector('.cantidad');
                            let cantidad = parseInt(cantidadElem.textContent, 10);

                            if (productStockMap[product.id_product] == 0) {
                                alert("El producto está agotado y no se puede añadir al carrito");
                                return;
                            }

                            if (cantidad > product.cantidad) {
                                alert("El producto está agotado y no se puede añadir al carrito");
                                return; //No añadimos el producto al carrito
                            }

                            // Actualizar la cantidad del producto antes de añadirlo al carrito
                            product.cantidad = cantidad;
                            product.totalIndCarrito = parseFloat(product.pre_ventades.replace(/,/g, '') * product.cantidad);

                            let existingProduct = carrito.find(p => p.id_product === product.id_product);

                            if (existingProduct) {
                                existingProduct.cantidad += cantidad;
                            } else {
                                carrito.push(product);
                            }

                            updateStock(product.id_product, cantidad);
                            actualizarCarrito();

                            let productDiv = this.closest('.product-item');
                            if (productDiv) {
                                productDiv.remove();
                            }
                        });
                    });
                } else {
                    resultsDiv.innerHTML = "No se encontró el producto";
                }
            } catch (error) {
                console.error("Error: ", error);
            }
        });

        document.getElementById('finalizar-venta').addEventListener('click', async function() {
            if (carrito.length === 0) {
                alert('El carrito está vacío.');
                return;
            }

            const clienteID = ced_cliente.value;
            const total = carrito.reduce((sum, product) => sum + product.totalIndCarrito, 0);
            try {
                let response = await fetch(`${BASE_URL}/finalizar-venta`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ carrito, cliente_id: clienteID, total })
                });

                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }

                let data = await response.json();

                if (data.succes) {
                    const invoiceId = data.invoiceId;

                    window.location.href = `/inventario/src/views/factura.html?id=${invoiceId}`;
                } else {
                    alert("Error al insertar Venta intente nuevamente");
                }

            } catch (error) {
                console.error("Error: ", error);
            }
        });

        function actualizarCarrito() {
            carritoList.innerHTML = '';
            carrito.forEach(product => {
                let listItem = document.createElement('li');
                let totalIndividual = parseFloat(product.totalIndCarrito);

                let formatTotalIndividual = totalIndividual.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                listItem.innerHTML = `
                    Producto: ${product.no_product} - Marca: ${product.nombre_marca} - ${product.pre_ventades} - Cantidad: ${product.cantidad} - Total: ${formatTotalIndividual}
                    <button class="remove-from-cart" data-product='${JSON.stringify(product)}'>Quitar</button>
                `;
                carritoList.appendChild(listItem);
            });

            document.querySelectorAll('.remove-from-cart').forEach(button => {
                button.addEventListener('click', function() {
                    let product = JSON.parse(this.getAttribute('data-product'));
                    carrito = carrito.filter(p => p.id_product !== product.id_product);

                    restoreStock(product.id_product, product.cantidad);
                    actualizarCarrito();
                });
            });
        }

        function updateStock(productId, quantity) {
            let productDivs = document.querySelectorAll(`.product-item button.add-to-cart[data-product*="${productId}"]`);
            productDivs.forEach(button => {
                let productDiv = button.closest('.product-item');
                if (productDiv) {
                    let stockElem = productDiv.querySelector('.stock');
                    let currentStock = parseInt(stockElem.textContent, 10);
                    let newStock = currentStock - quantity;
                    stockElem.textContent = newStock;
        
                    let addCantidadButton = productDiv.querySelector('.add-cantidad');
                    addCantidadButton.setAttribute('data-stock', newStock);

                    //Actualiar el stcok en el mapa global
                    productStockMap[productId] = newStock

                    //Desabilitamos el botn de añadir al carrito
                    if(newStock <= 0 ) {
                        button.disabled = true;
                        addCantidadButton.disabled = true;
                    }
                }
            });
        }
        
        function restoreStock(productId, quantity) {
            // Seleccionamos todos los elementos que podrían contener el producto
            let productDivs = document.querySelectorAll(`.product-item button.add-to-cart[data-product*="${productId}"]`);
        
            productDivs.forEach(button => {
                let productDiv = button.closest('.product-item');
                if (productDiv) {
                    let stockElem = productDiv.querySelector('.stock');
                    let currentStock = parseInt(stockElem.textContent, 10);
                    stockElem.textContent = currentStock + quantity;
        
                    let addCantidadButton = productDiv.querySelector('.add-cantidad');
                    addCantidadButton.setAttribute('data-stock', currentStock + quantity);
                }
            });

            // Eliminar el producto del mapa global productStockMap
            productStockMap[productId] = productStockMap[productId] + quantity;
        }
    } else {
        console.error("Formulario no encontrado");
    }
});
