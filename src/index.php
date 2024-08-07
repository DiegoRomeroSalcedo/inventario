
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


// Instancias
$AuthController = new AuthController();
$view = new View();
$productos = new Productos();
$marcas = new Marcas();
$inventario = new Inventario;
$inventarioController = new InventarioController($view, $inventario, $marcas, $productos);
$marcaController = new MarcaController($view);
$productoController = new ProductoController($view, $productos, $marcas);

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
        '/update-form-product' => [$productoController, 'updateProduct']
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
        '/update-form-product' => [$productoController, 'updateProduct']
    ]
];

// Definimos las rutas protegidas, esto con el fin de evaluar si estan en el requestUri

$routesProtected = [
    '/marcas',
    '/add-marca',
    '/add-productos',
    '/get-add-cantidades'
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
