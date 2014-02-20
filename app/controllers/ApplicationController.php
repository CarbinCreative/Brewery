<?php
namespace app\Controller;

use Brewery\Application\ControllerAbstract;

class ApplicationController extends ControllerAbstract {

	public function get() {

		return $this->render([
			'user' => 'Robin'
		]);

	}

}