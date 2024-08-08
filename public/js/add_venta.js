document.addEventListener('DOMContentLoaded', function() {
    let form = document.getElementById('search-add-venta');
    let carritoList = document.getElementById('carrito-list');
    let carrito = [];
    let ced_cliente = document.getElementById('ced_cliente');
    const BASE_URL = '/inventario/public';

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
                        let cantidadStock = producto.cantidad;
                        let productDiv = document.createElement('div');
                        productDiv.classList.add('product-item'); // Añadimos la clase
                        productDiv.innerHTML = `
                            <div>Nombre: ${producto.no_product}</div>
                            <div>Marca: ${producto.nombre_marca}</div>
                            <div>Precio: ${producto.pre_ventap}</div>
                            <div>Descuento: ${producto.desc_produ}</div>
                            <div>Precio Final: ${producto.pre_ventades}</div>
                            <div>Detalle: ${producto.detalle_product}</div>
                            <div>Cantidad: <span class="cantidad">${cantidad}</span></div>
                            <button class="add-cantidad" data-stock="${cantidadStock}">+</button>
                            <button class="remove-cantidad">-</button>
                            <button class="add-to-cart" data-product='${JSON.stringify(producto)}'>Añadir al carrito</button>
                        `;
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

                    // Añadimos los eventos a los botones de "Añadir al carrito"
                    document.querySelectorAll('.add-to-cart').forEach(button => {
                        button.addEventListener('click', function() {
                            let product = JSON.parse(this.getAttribute('data-product'));
                            let cantidadElem = this.previousElementSibling.previousElementSibling.previousElementSibling.querySelector('.cantidad');
                            let cantidad = parseInt(cantidadElem.textContent, 10);

                            // Actualizar la cantidad del producto antes de añadirlo al carrito
                            product.cantidad = cantidad;

                            // Verificar si el producto ya existe en el carrito
                            let existingProduct = carrito.find(p => p.id_product === product.id_product);

                            if (existingProduct) {
                                //Si el producto ya está en el carrito, actualizar la cantidad
                                existingProduct.cantidad += cantidad;
                            } else {
                                // Si el prodcuto no esta en el carrito, se añade
                                carrito.push(product);
                            }

                            actualizarCarrito();

                            // Eliminamos el producto de los resultados de búsqueda
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
            const total = carrito.reduce((sum, product) => {
            const precio = parseFloat(product.pre_ventades.replace(/,/g, ''));
            return sum + (precio * parseInt(product.cantidad));
            }, 0);

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

                alert('Venta realizada con Éxito');
                carrito = [];
                actualizarCarrito();
            } catch (error) {
                console.error("Error: ", error);
            }
        });

        function actualizarCarrito() {
            carritoList.innerHTML = '';
            carrito.forEach(product => {
                let listItem = document.createElement('li');
                listItem.innerHTML = `
                    ${product.no_product} - ${product.pre_ventap} - Cantidad: ${product.cantidad}
                    <button class="remove-from-cart" data-product='${JSON.stringify(product)}'>Quitar</button>
                `;
                carritoList.appendChild(listItem);
            });

            // Añadir eventos a los botones de "Quitar del carrito"
            document.querySelectorAll('.remove-from-cart').forEach(button => {
                button.addEventListener('click', function() {
                    let product = JSON.parse(this.getAttribute('data-product'));
                    carrito = carrito.filter(p => p.id_product !== product.id_product);
                    actualizarCarrito();
                });
            });
        }
    } else {
        console.error("Formulario no encontrado");
    }
});
