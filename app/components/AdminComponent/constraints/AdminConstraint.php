<?php
namespace app\Constraint;

use Brewery\Routing\Route\ConstraintAbstract;

class AdminConstraint extends ConstraintAbstract {

	public function validate() {

		return true;

	}

}