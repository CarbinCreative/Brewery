<?php
namespace app\Constraint;

use Brewery\Routing\Route\ConstraintAbstract;

class FooConstraint extends ConstraintAbstract {

	public function validate() {

		return true;

	}

}