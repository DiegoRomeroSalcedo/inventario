<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/inventario/public/css/login.css">
</head>
<body>
    <section class="container__form">
        <form class="form__login" action="<?= BASE_URL . '/login.php'?>" method="post">
            <h1>Login</h1>
            <div class="container__inputs">
                <label for="username">Usuario: </label>
                <input id="username" type="text" name="username" placeholder="laguaca" required>
            </div>
            <div class="container__inputs">
                <label for="password">Contraseña: </label>
                <input id="password" type="password" name="password">
            </div>
            <div class="container__button">
                <button type="submit">Ingresar</button>
            </div>
        </form>
    </section>
</body>
</html>