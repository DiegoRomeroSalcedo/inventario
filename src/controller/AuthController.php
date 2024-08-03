<?php

// Creamos el endpoint de Login, este verificará las credenciales de usuario, generará un token JWT y lo devolvera al cliente


namespace Proyecto\Controller;

use Proyecto\Models\User;

class AuthController {

    public function showLoginForm() {
        include __DIR__ . '/../views/login.php';
    }

    public function login() {
        session_start();


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
                echo "Credenciales incorrectas";
            }
        }

        include __DIR__ . '/../views/login.php';
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /inventario/public');
        exit();
    }
}
