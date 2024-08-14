<?php

// Creamos el endpoint de Login, este verificará las credenciales de usuario, generará un token JWT y lo devolvera al cliente


namespace Proyecto\Controller;

use Proyecto\Models\User;
use Views\View\View;
use Proyecto\Utils\Encryption;

class AuthController {

    protected $carpeta = "Autenticacion";

    public function showLoginForm() {
        include __DIR__ . '/../views/login.php';
    }

    public function login() {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Llamamos al modelo para verificar las credenciales

            $authModel = new User();

            if($authModel->validateCredentials($username, $password)){
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $authModel->getId();
                $_SESSION['role_user'] = $authModel->getRole();
                $_SESSION['username'] = $username;

                header('Location: /inventario/public/marcas');
                exit();
            } else {
                $_SESSION['error_login'] = "Credenciales Incorrectas";
                header('Location: /inventario/public');
                exit();
            }
        }

        include __DIR__ . '/../views/login.php';
    }

    public function updateUser() {
        $data = [
            'data' => [],
        ];

        $user = new User();
        $userData = $user->getDataUser();


        $data['data'] = $userData;

        $view = new View();
        $views = $view->assign('data', $data);
        $views = $view->render('update_user.php', $this->carpeta);
    }

    public function formUpdate() {

        $data = [
            'desencriptado' => [],
        ];

        if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['data'])) {
            $dataFromGet = $_GET['data'];
            $descryptedData = Encryption::decrypt($dataFromGet);
            $data['desencriptado'] = $descryptedData;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_usuario = $_POST['id_usuario'];
            $contraseña = $_POST['contraseña'];
            $nombre_usuario = $_POST['nombre_usuario'];

            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);


            $usuario = new User();
            $usuarios = $usuario->updateUser($id_usuario, $nombre_usuario, $hashedPassword);
        }

        $view = new View();
        $views = $view->assign('data', $data);
        $views = $view->render('form_update_usuario.php', $this->carpeta);
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /inventario/public');
        exit();
    }
}
