<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
use APP\Models;

class IndexController extends Action {

	public function index() {

		$this->render('index');
	}

	public function inscreverse() {
		$this->render('inscreverse');
	}

	public function registrar()
	{
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', $_POST['senha']);
		
		$quantUsuarios = $usuario->getUsuarioPorEmail();

		if( ($usuario->validarCadastro()) && (count($quantUsuarios) == 0 ))
		{
			$usuario->salvar();
			$this->render('cadastro');
		}
		else
		{

		}
	}

}


?>