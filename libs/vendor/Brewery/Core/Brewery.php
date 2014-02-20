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
 *	@license http://opensource.org/licenses/MIT MIT
 */

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Brewery
 *
 *	Core registry factory class, used to create instances of additional classes within the framework.
 *
 *	@vendor Brewery
 *	@package Core
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Brewery {

	/* @coalesce \Brewery\Generics\SingletonTrait */
	use \Brewery\Generics\SingletonTrait;

	/**
	 *	@var \Brewery\Core\InternalFactory\Factory $factory Instance of {@see \Brewery\Core\InternalFactory\Factory}.
	 */
	protected $factory;

	/**
	 *	Constructor
	 *
	 *	Creates a new instance of {@see \Brewery\Core\Factory\Factory}, visibility is set to "private" since this is a singleton class.
	 *
	 *	@return void
	 */
	private function __construct() {

		// Create a new instance of Factory
		$this->factory = new \Brewery\Core\Factory\Factory();

	}

	/**
	 *	getFactoryStore
	 *
	 *	Calls {@see \Brewery\Core\InternalFactory\FactoryAbstract::getFactoryStore}.
	 *
	 *	@return array
	 */
	public function getFactoryStore() {

		return $this->factory->getFactoryStore();

	}

	/**
	 *	Setter
	 *
	 *	Calls {@see \Brewery\Core\InternalFactory\FactoryAbstract::set}.
	 *
	 *	@param string $name
	 *	@param object $instance
	 *
	 *	@return void
	 */
	public function __set($name, $instance) {

		$this->factory->set($name, $instance);

	}

	/**
	 *	Getter
	 *
	 *	Calls {@see \Brewery\Core\Factory\FactoryAbstract::get}.
	 *
	 *	@param string $name Instance identifier name.
	 *
	 *	@return object|InternalFactoryObject
	 */
	public function __get($name) {

		return $this->factory->get($name);

	}

	/**
	 *	hasInstance
	 *
	 *	Calls {@see \Brewery\Core\Factory\FactoryAbstract::hasInstance}.
	 *
	 *	@param string $name Instance identifier name.
	 *
	 *	@return bool
	 */
	public function hasInstance($name) {

		return $this->factory->hasInstance($name);

	}

	/**
	 *	initialize
	 *
	 *	Calls {@see \Brewery\Core\InternalFactory\FactoryAbstract::initialize}.
	 *
	 *	@return void
	 */
	public function initialize($className, $instance, $parameters = [], $classMethod = 'newInstance') {

		$this->factory->initialize($className, $instance, $parameters, $classMethod);

	}

}