<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {


	public function timeline() {

		$this->validaAutenticacao();
			
		//recuperação dos tweets
		$tweet = Container::getModel('Tweet');
		$tweet->__set('id_usuario', $_SESSION['id']);
	
		// $tweets = $tweet->getAll();
		$total_registros_pagina = 4; // limit
		$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1 ;
		$deslocamento = ($pagina-1)* $total_registros_pagina ; // offset

		$tweets = $tweet->getPorPagina($total_registros_pagina, $deslocamento); // aplicando .. limit e offset
		$total_tweets = $tweet->getTotalRegistros();

		$total_de_paginas = ceil($total_tweets['total_tweet'] / $total_registros_pagina);
		$this->view->total_de_paginas = $total_de_paginas;

		$this->view->tweets = $tweets;

		$this->view->pagina_ativa = $pagina;
		$this->view->total_de_registros_pagina = $total_registros_pagina;

		// recuperacao do menu tweet seguindo e seguidores
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

		$this->view->info_usuario = $usuario->getInfoUsuario();
		$this->view->total_tweets = $tweet->getTotalRegistros();
		$this->view->total_seguindo = $usuario->getTotalSeguindo();
		$this->view->total_seguidores = $usuario->getTotalSeguidores();
	
		$this->render('timeline');
	}

	public function tweet() {

		$this->validaAutenticacao();

		$tweet = Container::getModel('Tweet');

		$tweet->__set('tweet', $_POST['tweet']);
		$tweet->__set('id_usuario', $_SESSION['id']);

		$tweet->salvar();

		header('Location: /timeline');
		
	}

	public function validaAutenticacao() {

		session_start();

		if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
			header('Location: /?login=erro');
		}	

	}

	public function quemSeguir() {

		$this->validaAutenticacao();

		$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
		
		$usuarios = array();

		// if($pesquisarPor != '') {
			
			$usuario = Container::getModel('Usuario');
			$usuario->__set('nome', $pesquisarPor);
			$usuario->__set('id', $_SESSION['id']);
			$usuarios = $usuario->getAll();
		// }


		$this->view->usuarios = $usuarios;
		
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

		//recuperação dos tweets
		$tweet = Container::getModel('Tweet');
		$tweet->__set('id_usuario', $_SESSION['id']);

		$this->view->info_usuario = $usuario->getInfoUsuario();
		$this->view->total_tweets = $tweet->getTotalRegistros();
		$this->view->total_seguindo = $usuario->getTotalSeguindo();
		$this->view->total_seguidores = $usuario->getTotalSeguidores();

		$this->render('quemSeguir');
	}	

	public function acao() {

		$this->validaAutenticacao();

		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

		if($acao == 'seguir') {
			$usuario->seguirUsuario($id_usuario_seguindo);

		} else if($acao == 'deixar-de-seguir') {
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);

		}
		header('Location: /quem_seguir');
	}

	public function apagarTweet() {

		$this->validaAutenticacao();
		$apagarTweet = isset($_GET['apagar']) ? $_GET['apagar'] : '';
		// print_r($_GET);
		if($apagarTweet == 'sim')
		{
			if (isset($_GET['tweet']) && ($_GET['tweet']) != '')
			{
				$tweet = Container::getModel('tweet');
				$tweet->__set('id', $_GET['tweet']);
				$tweet->apagarTweet($tweet->__get('id'));
			}
		}
		header('Location: /timeline');
	}
}

?>