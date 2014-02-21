<?php
/**
 *	Brewery
 *
 *	Brewery is an object oriented, agile and scalable RESTful web applications framework built for PHP 5.4 and later, which focuses on structured and enterprise-worthy application development for cloud based services and applications.
 *
 *	@link http://brewphp.org
 *
 *	@author Robin Grass <hej@carbin.se>
 *
 *	@license http://opensource.org/licenses/LGPL-2.1 The GNU Lesser General Public License, version 2.1
 */

/* @namespace Routing */
namespace Brewery\Routing;

/* @imports */
use \Brewery\Routing\Route\Action;
use \Brewery\Routing\Route\Controller;
use \Brewery\Routing\Route\Component;
use \Brewery\Routing\Route\Constraint;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Route
 *
 *	Class used to delegate routes.
 *
 *	@vendor Brewery
 *	@package Routing
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Route {

	/**
	 *	@var \Brewery\Routing\Route\Action $action Route action callback object.
	 */
	protected $action;

	/**
	 *	@var \Brewery\Routing\Route\Controller $controller Route controller callback object.
	 */
	protected $controller;

	/**
	 *	@var \Brewery\Routing\Route\Component $component Route component object.
	 */
	protected $component;

	/**
	 *	@var array $constraints Route callback constraints.
	 */
	protected $constraints = [];

	/**
	 *	registerAction
	 *
	 *	Registers a route action.
	 *
	 *	@param \Brewery\Routing\Route\Action $action Route action instance.
	 *
	 *	@return void
	 */
	public function registerAction(Action $action) {

		$this->action = $action;

	}

	/**
	 *	registerController
	 *
	 *	Registers a route controller.
	 *
	 *	@param \Brewery\Routing\Route\Component $controller Route controller instance.
	 *
	 *	@return void
	 */
	public function registerController(Controller $controller) {

		$this->controller = $controller;

	}

	/**
	 *	registerComponent
	 *
	 *	Registers a route component.
	 *
	 *	@param \Brewery\Routing\Route\Component $component Route component instance.
	 *
	 *	@throws \Brewery\Routing\Exceptions\RouterException
	 *
	 *	@return void
	 */
	public function registerComponent(Component $component) {

		$this->component = $component;

		$this->constraints = array_merge($this->constraints, $this->component->getConstraints());

		if(defined('BREWERY_COMPONENT_NAME') === true && defined('BREWERY_COMPONENT_PATH') === true) {

			throw new Exceptions\RouterException(
				'Could not register router component.', 'Router component already defined.',
				Exceptions\RouterException::BAD_CALL, __METHOD__
			);

		}

		define('BREWERY_COMPONENT_NAME', $component->getIdentifier());

		define('BREWERY_COMPONENT_PATH', $component->includePath());

	}

	/**
	 *	registerConstraint
	 *
	 *	Registers a route constraint.
	 *
	 *	@param \Brewery\Routing\Route\Constraint $constraint Route constraint instance.
	 *
	 *	@return void
	 */
	public function registerConstraint(Constraint $constraint) {

		if(in_array($constraint, $this->constraints) === false) {

			$this->constraints[] = $constraint;

		}

	}

	/**
	 *	action
	 *
	 *	Returns registered instance of {@see \Brewery\Routing\Route\Action}.
	 *
	 *	@return \Brewery\Routing\Route\Action
	 */
	public function action() {

		return $this->action;

	}

	/**
	 *	controller
	 *
	 *	Returns registered instance of {@see \Brewery\Routing\Route\Controller}.
	 *
	 *	@return \Brewery\Routing\Route\Controller
	 */
	public function controller() {

		return $this->controller;

	}

	/**
	 *	component
	 *
	 *	Returns registered instance of {@see \Brewery\Routing\Route\Component}.
	 *
	 *	@return \Brewery\Routing\Route\Component
	 */
	public function component() {

		return $this->component;

	}

	/**
	 *	importResources
	 *
	 *	Imports registered resources.
	 *
	 *	@return void
	 */
	public function importResources() {

		$this->action()->import();

		$this->controller()->import();

		foreach($this->constraints as $constraint) {

			$constraint->import();

		}

	}

	/**
	 *	validate
	 *
	 *	Validates all router constraints.
	 *
	 *	@throws \Brewery\Routing\Exceptions\RouterException
	 *
	 *	@return void
	 */
	public function validate() {

		foreach($this->constraints as $constraint) {

			if($constraint->invoke() === false) {

				throw new Exceptions\RouterException(
					'Could not delegate route.', 'Route constraint "' . $constraint->getName() . '" failed.',
					Exceptions\RouterException::UNEXPECTED_RESULT, __METHOD__
				);

			}

		}

	}

}