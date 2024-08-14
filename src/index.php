
<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();

// Cragamos las dependencias de composer

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../lib/View.php';

use Views\View\View;
use Proyecto\Controller\AuthController;
use Proyecto\Controller\MarcaController;
use Proyecto\Controller\ProductoController;
use Proyecto\Models\Productos;
use Proyecto\Models\Marcas;
use Proyecto\Models\Inventario;
use Proyecto\Controller\InventarioController;
use Proyecto\Controller\VentasController;
use Proyecto\Controller\DevolucionController;
use Proyecto\Controller\FacturasController;
use Proyecto\Controller\ClientesController;
use Proyecto\Controller\DescuentosController;
use Proyecto\Models\Facturas;

// Instancias
$AuthController = new AuthController();
$view = new View();
$productos = new Productos();
$marcas = new Marcas();
$inventario = new Inventario;
$facturas = new Facturas();
$inventarioController = new InventarioController($view, $inventario, $marcas, $productos);
$marcaController = new MarcaController($view);
$productoController = new ProductoController($view, $productos, $marcas);
$ventaController = new VentasController($view, $productos, $marcas);
$facturasController = new FacturasController($view);
$devolucionController = new DevolucionController($view, $facturas);
$clientesController = new ClientesController($view);
$descuentosController = new DescuentosController($view);
// Obtener la URI y el metodo de solicitud


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$requestUri = str_replace(BASE_URL, '', $path);

// echo "Original Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
// echo "Processed Request URI: " . $requestUri . "<br>";

// Definimos las rutas y los metodos correspondientes

$routes = [
    'GET' => [
        '/' => [$AuthController, 'showLoginForm'], // Ruta por defecto
        '/login.php' => [$AuthController, 'login'],
        '/logout' => [$AuthController, 'logout'],
        '/inventario' => [$inventarioController, 'list'],
        '/marcas' => [$marcaController, 'list'],
        '/add-marcas' => [$marcaController, 'addMarca'],
        '/productos' => [$productoController, 'list'],
        '/add-productos' => [$productoController, 'addProducto'],
        '/get-add-cantidades' => [$productoController, 'search'],
        '/add-cantidad-product' => [$productoController, 'addCantidadProducto'],
        '/search-update-marcas' => [$marcaController, 'searchUpdate'],
        '/update-form-marca' => [$marcaController, 'updateMarca'],
        '/search-update-productos' => [$productoController, 'searchUpdateProducto'],
        '/update-form-product' => [$productoController, 'updateProduct'],
        '/search-add-venta' => [$ventaController, 'searchAddVenta'],
        '/ventas' => [$ventaController, 'listVentas'],
        '/get-factura' => [$facturasController, 'renderFactura'],
        '/get-data-factura' => [$facturasController, 'getFacturaData'],
        '/facturas' => [$facturasController, 'listFacturData'],
        '/detalles-facturas' => [$facturasController, 'listDetalleFactura'],
        '/clientes' => [$clientesController, 'getDataCliente'],
        '/search-factura-devolucion' => [$devolucionController, 'searchFacturaDevolucion'],
        '/add-devolucion' => [$devolucionController, 'addDevolucion'],
        '/list-devoluciones' => [$devolucionController, 'listDevoluciones'],
        '/validate-descuentos'     => [$descuentosController, 'listDescuentosVencidos'],
        '/update-user' => [$AuthController, 'updateUser'],
        '/form-update-user' => [$AuthController, 'formUpdate'],
        '/dashboard' => [$ventaController, 'renderDasboard'],
        '/dasboard-ventas' => [$ventaController, 'getdataDashboard']
    ],
    'POST' => [
        '/login.php' => [$AuthController, 'login'],
        '/inventario' => [$inventarioController, 'searchData'],
        '/add-marcas' => [$marcaController, 'addMarca'],
        '/add-productos' => [$productoController, 'addProducto'],
        '/get-add-cantidades' => [$productoController, 'search'],
        '/add-cantidad-product' => [$productoController, 'addCantidadProducto'],
        '/search-update-marcas' => [$marcaController, 'searchUpdate'],
        '/update-form-marca' => [$marcaController, 'updateMarca'],
        '/search-update-productos' => [$productoController, 'searchUpdateProducto'],
        '/update-form-product' => [$productoController, 'updateProduct'],
        '/search-add-venta' => [$ventaController, 'searchAddVenta'],
        '/ventas' => [$ventaController, 'listVentas'],
        '/finalizar-venta' => [$ventaController, 'finalizarVenta'],
        '/get-factura' => [$ventaController, 'renderFactura'],
        '/get-data-factura' => [$ventaController, 'getFacturaData'],
        '/facturas' => [$facturasController, 'listFacturData'],
        '/detalles-facturas' => [$facturasController, 'listDetalleFactura'],
        '/clientes' => [$clientesController, 'getDataCliente'],
        '/search-factura-devolucion' => [$devolucionController, 'searchFacturaDevolucion'],
        '/add-devolucion' => [$devolucionController, 'addDevolucion'],
        '/list-devoluciones' => [$devolucionController, 'listDevoluciones'],
        '/validate-descuentos'     => [$descuentosController, 'listDescuentosVencidos'],
        '/quitar-descuentos'        => [$descuentosController, 'quitarDescuento'],
        '/update-user' => [$AuthController, 'updateUser'],
        '/form-update-user' => [$AuthController, 'formUpdate'],
        '/dashboard' => [$ventaController, 'renderDasboard'],
        '/dasboard-ventas' => [$ventaController, 'getdataDashboard']
    ]
];

// Definimos las rutas protegidas, esto con el fin de evaluar si estan en el requestUri

$routesProtected = [
    '/logout',
    '/inventario',
    '/marcas',
    '/add-marcas',
    '/productos',
    '/add-productos',
    '/get-add-cantidades',
    '/add-cantidad-product',
    '/search-update-marcas',
    '/update-form-marca',
    '/search-update-productos',
    '/update-form-product',
    '/search-add-venta',
    '/search-factura-devolucion',
    '/get-factura',
    '/get-data-factura',
    '/ventas',
    '/facturas',
    '/detalles-facturas',
    '/clientes',
    '/search-factura-devolucion',
    '/add-devolucion',
    '/list-devoluciones',
    '/validate-descuentos',
    '/validate-descuentos',
    '/update-user',
    '/form-update-user',
    '/dashboard',
    '/dasboard-ventas'
];  

// Verificamos si la ruta actual es una ruta Protegida y si esta logeado el usuario

if(in_array($requestUri, $routesProtected) && (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true)) {
    header('Location: /inventario/public');
    exit();
}

// Buscamos la ruta y ejecutamos el método corresponiente

if(isset($routes[$requestMethod][$requestUri])) {
    call_user_func($routes[$requestMethod][$requestUri]);
}
