<?php

// <!-- para fazer update nas senha nao criptografadas no banco de dados -->
// <!-- usar a funcao sql no phpmyadmin -->
// <!-- update usuarios set senha = md5(senha) where id in(1,2)  -->

namespace App\Controllers;

// os recursos do miniframework

use MF\Controller\Action;
use MF\Model\Container;
use APP\Models;

class AuthController extends Action {
    public function autenticar()
    {
        $usuario = Container::getModel('usuario');
        $usuario->__set('email',strtolower($_POST['email']));
        $usuario->__set('senha',md5($_POST['senha']));
        $usuario->autenticar();
        if($usuario->__get('id') != '' && $usuario->__get('nome') != '')
        {
            session_start();
            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');
            header('Location: /timeline');
        }
        else
        {
            //atencao , o Location comeca com L maiusculo e nao pode conter espaco entre o comando e os :
            // Location: ....
            header('Location: /?login=erro');
        }
    }

    public function sair()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
?>