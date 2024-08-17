<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Venta</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #444;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh; /* Ocupa toda la altura de la ventana */
            overflow: auto; /* Permitir scroll si el contenido es mayor que la altura de la ventana */
        }

        .container {
            text-align: center;
            width: 100%; /* Asegura que la container use todo el ancho disponible */
        }

        .factura {
            background-color: #fff;
            width: 80mm; /* Ancho de la factura */
            padding: 10px;
            margin: 0 auto; /* Centrado horizontal */
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 20mm; /* Reducción del tamaño máximo de la imagen */
            height: auto;
        }

        .header h1 {
            font-size: 16px;
            margin: 5px 0;
        }

        .factura h2 {
            font-size: 14px;
            text-align: center;
            margin: 10px 0;
        }

        .factura h3 {
            font-size: 14px;
            text-align: left;
            margin: 5px 0;
        }

        .cliente, .productos, .total {
            font-size: 12px;
            margin: 5px 0;
        }

        .data__empresa {
            margin: 6px 0 6px 0;
            font-size: 11px;
        }

        .productos .producto {
            display: flex;
            justify-content: space-between;
            padding: 5px 0; /* Espaciado vertical interno */
            border-bottom: 1px solid #ccc; /* Línea de separación horizontal */
            margin-bottom: 5px; /* Separación adicional entre productos */
        }

        .productos .producto p {
            margin: 2px 0;
        }

        .producto .descripcion {
            flex: 3;
            text-align: left;
        }

        .producto .cantidad, .producto .monto {
            width: 20%;
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px; /* Separación del contenido superior */
        }

        .footer #container-qrcode {
            margin-bottom: 10px;
        }

        .footer p {
            font-size: 12px;
            margin: 3px 0;
        }

        #print-button, #back-button {
            margin-top: 20px; /* Separación de 20px entre la factura y el botón de impresión */
            padding: 10px 20px;
            font-size: 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #print-button {
            margin-right: 60px;
            margin-bottom: 80px;
        }

        #print-button:hover {
            background-color: #0056b3;
        }

        @media print {
            @page {
                size: 80mm auto; /* Establece el ancho de la página y permite altura automática */
                margin: 0; /* Sin márgenes para aprovechar el espacio */
            }
            body {
                background-color: #fff;
                margin: 0; /* Eliminar márgenes del body para impresión */
                padding: 0; /* Eliminar padding del body para impresión */
            }
            .container {
                width: 80mm; /* Asegura que el ancho de la factura se mantenga igual */
                height: auto; /* Ajusta la altura según el contenido */
                margin: 0 auto; /* Centrado horizontal */
                padding: 0;
                overflow: visible; /* Asegura que no haya overflow en la impresión */
            }
            .factura {
                margin: 0;
                padding: 0;
                width: 80mm;
                position: relative; /* Permite que el footer esté en el flujo normal */
            }
            #print-button, #back-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="factura">
            <div class="header">
                <img src="/inventario/public/images/logo_empresa.png" alt="Logo Empresa">
                <h1>LA GUACA</h1>
                <p class="data__empresa">COMERCIALIZADORA DE LA ESPRIELLA SAS</p>
                <p class="data__empresa"><span class="nit__empresa">NIT: </span>901656873-7</p>
                <p class="data__empresa"><span class="direccion__empresa">DIRECCIÓN: </span>CL 19 24 05 BRR 7 DE AGOSTO</p>
                <p class="data__empresa"><span class="nom__ciudad">CIUDAD: </span>SINCELEJO</p>
                <p class="data__empresa"><span class="nom_departamento">DEPARTAMENTO: </span>SUCRE</p>
                <p class="data__empresa"><span class="num_telefono">TELÉFONO: </span>3003087223</p>
            </div>

            <h2 id="nombre-factura">Factura de Venta</h2>

            <div class="cliente">
                <h3 id="nombre-factura-dinamico"></h3>
                <p>Cliente: <span id="cliente-nombre">[Nombre del Cliente]</span></p>
                <p>Fecha: <span id="fecha">[Fecha]</span></p>
            </div>

            <div class="productos">
                <h3>Productos</h3>
                <div id="lista-productos">
                    <!-- Productos serán agregados aquí por JavaScript -->
                </div>
            </div>

            <div class="total">
                <h3>Total: <span id="total-factura" style="float: right;">$0.00</span></h3>
            </div>

            <div class="footer">
                <div id="container-qrcode">
                    <canvas id="qrcode"></canvas>
                </div>
                <p>Nombre de la Empresa</p>
                <p>Dirección: [Dirección]</p>
                <p>Teléfono: [Teléfono]</p>
            </div>
        </div>

        <button id="print-button" onclick="window.print()">Imprimir</button>
        <button id="back-button" onclick="history.back()">Regresar</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const invoiceId = urlParams.get('id');
        const BASE_URL = '/inventario/public';

        async function loadFacturaDetalles() {
            try {
                let response = await fetch(`${BASE_URL}/get-data-factura?id=${invoiceId}`);

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                let data = await response.json();
                const factura = data;  // La estructura JSON ya es un array de objetos

                facturaFormatted = 'FAC' + invoiceId.toString().padStart(6, '0');

                // Llenar el nombre de la factura
                document.getElementById('nombre-factura-dinamico').textContent = `Factura: ${facturaFormatted}`;

                // Llenar datos del cliente
                document.getElementById('cliente-nombre').textContent = factura[0].identificacion || 'No reporta'; // Asumimos que el cliente es el mismo para todos los productos
                
                // Obtener la fecha y hora actual
                const now = new Date();
                const fechaHora = `${now.toLocaleDateString()} ${now.toLocaleTimeString()}`;
                document.getElementById('fecha').textContent = fechaHora;

                // Llenar lista de productos
                const productosDiv = document.getElementById('lista-productos');
                let total = 0;
                productosDiv.innerHTML = '';  // Limpiar contenido anterior

                factura.forEach(item => {

                    let montoVentaStr = item.monto_venta.replace(/,/g, '');
                    let montoVenta = parseFloat(montoVentaStr);

                    const formattedMontoVenta = montoVenta.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });


                    total += parseFloat(item.monto_venta);

                    const productoDiv = document.createElement('div');
                    productoDiv.classList.add('producto');
                    productoDiv.innerHTML = `
                        <p class="descripcion">${item.id_producto} - ${item.no_product} - ${item.nombre_marca} - Cantidad: ${item.cantidad}</p>
                        <p class="monto">${formattedMontoVenta}</p>
                    `;
                    productosDiv.appendChild(productoDiv);
                });

                let totalVentaStr = factura[0].total_venta.replace(/,/g, '');
                let totalVenta = parseFloat(totalVentaStr);

                const formattedTotalVenta = totalVenta.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Llenar total de la factura
                document.getElementById('total-factura').textContent = `$${formattedTotalVenta}`;

                // Generar código QR
                const qrData = `LAGUACA\nFactura: ${facturaFormatted}\nTotal: $${formattedTotalVenta}`;
                const qrcode = document.getElementById('qrcode');
                QRCode.toCanvas(qrcode, qrData, { width: 120 });
            } catch (error) {
                console.error("Error: ", error);
            }
        }

        loadFacturaDetalles();
    </script>
</body>
</html>
