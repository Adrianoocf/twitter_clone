<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
use APP\Models;

class IndexController extends Action {

	public function index() 
	{
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}

	public function inscreverse() {
		
		$this->view->erroCadastro = false;
		$this->view->erroEmail = false;

		$this->view->usuario = array(
			'nome' => '',
			'email' => '',
			'senha' =>  ''
		); 
		
		$this->render('inscreverse');
	}

	public function registrar()
	{
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', strtolower($_POST['email']));
		$usuario->__set('senha', md5($_POST['senha']));
		
		$quantUsuarios = $usuario->getUsuarioPorEmail();
		
		$autenticado = 0;
		$posicaoArroba = strripos($usuario->email, '@');
		$posicaoPonto = strripos($usuario->email, '.');
		if ($posicaoArroba > 0)
		{
			$autenticado++;
		} 
		if ($posicaoPonto > $posicaoArroba)
		{
			$autenticado++;
		}

		if(($autenticado == 2) && (strlen($_POST['senha']) > 3) && ($usuario->validarCadastro()) && (count($quantUsuarios) == 0 ))
		{
			$usuario->salvar();
			$this->render('cadastro');
		}
		else
		{
				$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			); 
			$this->view->erroCadastro = true;
			$this->render('inscreverse');
		}
	}

}


?>