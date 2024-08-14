<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/inventario/public/css/layout_styles.css">
    <link rel="stylesheet" href="/inventario/public/css/layout_form.css">
    <?= isset($stylesCss) ? $stylesCss : '' ?> 
    <?= isset($stylesLibraries) ? $stylesLibraries:  '' ?> 
    <style>
        div.dt-container {
            width: 1050px !important;
            margin: 0 auto !important;
        }
    </style>
</head>
<body>
    <section class="page__section">
        <aside class="sidebar">
            <section class="perfil__section">
                <div class="img__container">
                <img class="image__user" src="/inventario/public/images/logo_empresa.png" alt="Por definir">
                </div>
            </section>
            <section class="perfil_section">
                <?php 
                    if(isset($_SESSION['role_user']) && $_SESSION['role_user'] == 1) {
                        echo '<p class="perfil__user-name"><a href="' . BASE_URL . '/update-user">' . $_SESSION['username'] . '</a></p>';
                    } else {
                        echo '<p class="perfil__user-name">' . $_SESSION['username'] . '</p>';
                    }
                ?> 
            </section>
            <ul class="categories__list">
                <li>
                    <a href="#" class="dropdown-toggle">Listar</a>
                    <ul class="dropdown-menu">
                        <?php 
                            if(isset($_SESSION['role_user']) && $_SESSION['role_user'] == 1) {
                                echo '<li><a href="' .BASE_URL . '/inventario"> Inventario</a></li>';
                                echo '<li><a href="' .BASE_URL . '/facturas"> Facturas</a></li>';
                                echo '<li><a href="' .BASE_URL . '/ventas"> Ventas</a></li>';
                                echo '<li><a href="' .BASE_URL . '/validate-descuentos"> Descuentos Vencidos</a></li>';
                            } 
                        ?> 
                        <li><a href="<?= BASE_URL . '/clientes'?>"> Clientes</a></li>
                        <li><a href="<?= BASE_URL . '/marcas'?>"> Marcas</a></li>
                        <li><a href="<?= BASE_URL . '/productos'?>"> Productos</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle">Insertar</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= BASE_URL . '/add-marcas'?>"> Marcas</a></li>
                        <li><a href="<?= BASE_URL . '/add-productos'?>"> Productos</a></li>
                        <li><a href="<?= BASE_URL . '/get-add-cantidades'?>"> Cantidades</a></li>
                    </ul>
                </li>
                    <?php 
                        if(isset($_SESSION['role_user']) && $_SESSION['role_user'] == 1) {

                            echo '<li>
                                <a href="#" class="dropdown-toggle">Actualizar</a>
                                <ul class="dropdown-menu">
                                    <li><a href="'. BASE_URL . '/search-update-marcas"> Marcas</a></li>
                                    <li><a href="' . BASE_URL . '/search-update-productos"> Productos</a></li>
                                </ul>
                            </li>';

                        }
                    ?>
                <li>
                    <a href="#" class="dropdown-toggle">Ventas</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= BASE_URL . '/search-add-venta' ?>"> Ingresar Venta</a></li>
                        <?php 
                        if(isset($_SESSION['role_user']) && $_SESSION['role_user'] == 1) {
                            echo '<li><a href="'.BASE_URL.'/dashboard"> Registro de Ventas</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle">Devoluciones</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= BASE_URL . '/search-factura-devolucion'?>">Insertar Devolucion</a></li>
                        <li><a href="<?= BASE_URL . '/list-devoluciones'?>">Listar Devoluciones</a></li>
                    </ul>
                </li>
            </ul>
            <section class="logout">
                <a href="<?= BASE_URL . '/logout'?>"><img src="/inventario/public/images/logout.svg" alt="Logout_image"></a>
            </section>
        </aside>
        <main class="main-content">
            <section class="content">
                <?= $content ?>
            </section>
            <footer class="footer">COMERCIALIADORA DE LA ESPRIELLA © NIT: 901656873-7 CL 19 24 05 BRR 7 DE AGOSTO
                Teléfono: 3003087223
            </footer>
        </main>
    </section>
    <?= isset($librariesHtml) ? $librariesHtml : '' ?> 
    <?= isset($scriptsHtml) ? $scriptsHtml : '' ?>  
    <script>
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
</body>
</html>