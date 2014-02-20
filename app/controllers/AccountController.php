<?php
namespace app\Controller;

use Brewery\Application\ControllerAbstract;

class AccountController extends ControllerAbstract {

	public function get() {

		return __METHOD__;

	}

	public function getPerson() {

		return __METHOD__;

	}

	public function ajaxGet() {

		\Brewery\httpHeader('X-Requested-With', 'XMLHttpRequest');

		return __METHOD__;

	}

}