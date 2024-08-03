<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/inventario/public/css/layout_styles.css">
    <link rel="stylesheet" href="/inventario/public/css/layout_form.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css" />
</head>
<body>
    <section class="page__section">
        <aside class="sidebar">
            <section class="perfil__section">
                <div class="img__container">
                <img class="image__user" src="/inventario/public/images/user_image.svg" alt="Por definir">
                </div>
                <p class="perfil__user-name">Usuario</p>
            </section>
            <section>
                <a href="<?= BASE_URL . '/logout'?>">Cerrar Sesion</a>
            </section>
            <ul class="categories__list">
                <li>
                    <a href="#" class="dropdown-toggle">Listar</a>
                    <ul class="dropdown-menu">
                        <?php 
                            if(isset($_SESSION['role_user']) && $_SESSION['role_user'] == 1) {
                                echo '<li><a href="#"> <span class="marca">></span> Inventario</a></li>';
                            } 
                        ?> 
                        <li><a href="<?= BASE_URL . '/marcas'?>"> <span class="marca">></span> Marcas</a></li>
                        <li><a href="<?= BASE_URL . '/productos'?>"> <span class="marca">></span> Productos</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle">Insertar</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= BASE_URL . '/add-marcas'?>"> <span class="marca">></span> Marcas</a></li>
                        <li><a href="<?= BASE_URL . '/add-productos'?>"> <span class="marca">></span> Productos</a></li>
                        <li><a href="<?= BASE_URL . '/get-add-cantidades'?>"> <span class="marca">></span> Cantidades</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle">Ventas</a>
                    <ul class="dropdown-menu">
                        <li><a href="#"> <span class="marca">></span> Ingresar Venta</a></li>
                        <li><a href="#"> <span class="marca">></span> Registro de Ventas</a></li>
                    </ul>
                </li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <section class="content">
                <?= $content ?>
            </section>
            <footer class="footer">Footer</footer>
        </main>
    </section>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        new DataTable('#example');

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelectorAll('.dropdown-toggle');
            
                dropdownToggle.forEach(function(toggle) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        const dropdownMenu = this.nextElementSibling;
                        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
                    });
                });
        });
    </script>
    <?= isset($scriptsHtml) ? $scriptsHtml : '' ?> 
</body>
</html>