<?php
namespace app\Constraint;

use Brewery\Routing\Route\ConstraintAbstract;

class BarConstraint extends ConstraintAbstract {

	public function validate() {

		return true;

	}

}