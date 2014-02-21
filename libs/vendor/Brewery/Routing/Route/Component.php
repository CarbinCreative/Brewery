<?php
/**
 *	Brewery
 *
 *	Brewery is an object oriented, agile and scalable RESTful web applications framweork built for PHP 5.4 and later, which focuses on structured and enterprise-worthy application development for cloud based services and applications.
 *
 *	@link http://brewphp.org
 *
 *	@author Robin Grass <hej@carbin.se>
 *
 *	@license http://opensource.org/licenses/LGPL-2.1 The GNU Lesser General Public License, version 2.1
 */

/* @namespace Route */
namespace Brewery\Routing\Route;

/* @imports */
use Brewery\Routing\Route\ConstraintAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Component
 *
 *	Route component class, contains special logic for components.
 *
 *	@vendor Brewery
 *	@package Routing\Route
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Component {

	/**
	 *	@var string $identifier Component identifier.
	 */
	protected $identifier;

	/**
	 *	@var array $constraints Component specific route constraints.
	 */
	protected $constraints = [];

	/**
	 *	Constructor
	 *
	 *	Sets component identifier and validates component existance.
	 *
	 *	@param string $identifier Component identifier.
	 *
	 *	@throws \Brewery\Routing\Route\Exceptions\ComponentException
	 *
	 *	@return void
	 */
	public function __construct($identifier) {

		if(is_string($identifier) === false) {

			throw new Exceptions\ComponentException(
				'Could not set router component.', 'Argument 1 passed to ' . __METHOD__ . ' must be a string, ' . gettype($callback) . ' given.',
				Exceptions\ComponentException::TYPEHINT, __METHOD__
			);

		}

		$this->identifier = $identifier;

		$includePath = $this->includePath();

		if(is_dir($includePath) === false) {

			throw new Exceptions\ComponentException(
				'Could not set router component.', 'No such file or directory for "' . $includePath . '".',
				Exceptions\ComponentException::UNEXPECTED_RESULT, __METHOD__
			);

		}

	}

	/**
	 *	getIdentifier
	 *
	 *	Returns component identifier.
	 *
	 *	@return string
	 */
	public function getIdentifier() {

		return $this->identifier;

	}

	/**
	 *	registerConstraint
	 *
	 *	Registers a route constraint.
	 *
	 *	@param \Brewery\Routing\Route\ConstraintAbstract $constraint Route constraint instance.
	 *
	 *	@return void
	 */
	public function registerConstraint(ConstraintAbstract $constraint) {

		if(in_array($constraint, $this->constraints) === false) {

			$this->constraints[] = $constraint;

		}

	}

	/**
	 *	getConstraints
	 *
	 *	Returns component constraints.
	 *
	 *	@return string
	 */
	public function getConstraints() {

		return $this->constraints;

	}

	/**
	 *	includePath
	 *
	 *	Returns component include path for component specific application logic.
	 *
	 *	@return string
	 */
	public function includePath() {

		return BREWERY_COMPONENTS_PATH . $this->identifier . DIRECTORY_SEPARATOR;

	}

}