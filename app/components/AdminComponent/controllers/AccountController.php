<?php
namespace app\Controller;

class AccountController extends \Brewery\Application\ControllerAbstract {

	public function get() {

		return __METHOD__;

	}

	public function getAccountBySlug($slug) {

		return sprintf("%s | %s", __METHOD__, $slug);

	}

}