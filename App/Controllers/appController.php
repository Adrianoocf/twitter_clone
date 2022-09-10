<?php

    // cd\projetos html\twitter_clone\public
    // php -S localhost:8080

    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;
    use APP\Models;

    class AppController extends Action
    {
        public function timeline()
        {
            $this->validaAutenticacao();
            $tweet = Container::getModel('Tweet');
            $tweet->__set('id_usuario',$_SESSION['id']);
            $tweets = $tweet->getAll();
            $this->view->tweets = $tweets;
            $this->render('timeline');
        }

        public function tweet()
        {
            $this->validaAutenticacao();
            $tweet = Container::getModel('Tweet');
            $tweet->__set('tweet',$_POST['tweet']);
            $tweet->__set('id_usuario',$_SESSION['id']);
            $tweet->salvar();
            header('Location:/timeline');
        }

        public function validaAutenticacao()
        {
            session_start();
            if(  ($_SESSION['id']=='') || ($_SESSION['nome']=='') || 
            ( !isset($_SESSION['id'])) || (!isset($_SESSION['nome'])))
            {
                header('Location: /?login=erro');
            }
        }

        public function quemSeguir()
        {
            $this->validaAutenticacao();
            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : ''; 
            $usuarios = array();
            if($pesquisarPor != '')
            {
                $usuario = Container::getModel('usuario');
                $usuario->__set('nome',$pesquisarPor);
                $usuarios = $usuario->getAll();
            }
            $this->view->usuarios = $usuarios;
            $this->render('quemSeguir');
        }
    }
?>