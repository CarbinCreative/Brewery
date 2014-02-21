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

/* @namespace Factory */
namespace Brewery\Core\Factory;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	FactoryAbstract
 *
 *	Abstract which enables chaining functionality to classes initialized via {@see \Brewery\Core\Factory\FactoryAbstract::initialize}.
 *
 *	@vendor Brewery
 *	@package Core\Factory
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class FactoryAbstract {

	/**
	 *	@var array $store Factory store array, contains class instance references.
	 */
	protected $store = [];

	/**
	 *	@var string $identifier Instance identifier to an {@see \Brewery\Core\Factory\FactoryAbstract} object.
	 */
	protected $identifier;

	/**
	 *	@var \Brewery\Core\Factory\FactoryAbstract $parent Reference to parent {@see \Brewery\Core\Factory\FactoryAbstract} class, defaults to null.
	 */
	protected $parent = null;

	/**
	 *	Constructor
	 *
	 *	Sets factory class properties.
	 *
	 *	@param array $store Factory store array, contains class instance references.
	 *	@param string $identifier Instance identifier.
	 *	@param FactoryAbstract $parent Reference to parent class, an instance of {@see \Brewery\Core\Factory\FactoryAbstract}.
	 *
	 *	@return void
	 */
	public function __construct(Array $store, $identifier = null, FactoryAbstract $parent = null) {

		$this->store = $store;

		$this->identifier = $identifier;

		$this->parent = $parent;

	}

	/**
	 *	set
	 *
	 *	Sets item to factory store.
	 *
	 *	@param string $key Identifier to factory store item.
	 *	@param mixed $object Mixed object to store in factory store.
	 *
	 *	@return void
	 */
	public function set($key, $object) {

		$this->store[$key] = $object;

		if(isset($this->parent) === true && is_string($this->identifier)) {

			$_key = $this->identifier;

			$this->parent->$_key = $this->store;

		}

	}

	/**
	 *	Setter
	 *
	 *	Calls method {@see \Brewery\Core\Factory\FactoryAbstract::set}.
	 *
	 *	@param string $key Identifier to factory store item.
	 *	@param mixed $object Mixed object to store in factory store.
	 *
	 *	@return void
	 */
	public function __set($key, $object) {

		$this->set($key, $object);

	}

	/**
	 *	get
	 *
	 *	Returns an item from factory store, or an empty stdClass.
	 *
	 *	@param string $key Identifier to factory store item.
	 *
	 *	@return Object|object
	 */
	public function get($key) {

		if(array_key_exists($key, $this->store) === false) {

			$this->store[$key] = [];

		}

		if(array_key_exists($key, $this->store) === true ) {

			$store = $this->store[$key];

			if(is_array($store) === true) {

				return new \Brewery\Core\Factory\Object($store, $key, $this);

			}

			return $store;

		}

		return new \stdClass();

	}

	/**
	 *	Getter
	 *
	 *	Calls method {@see \Brewery\Core\Factory\FactoryAbstract::get}.
	 *
	 *	@param string $key Identifier to factory store item.
	 *
	 *	@return void
	 */
	public function __get($key) {

		return $this->get($key);

	}

	/**
	 *	getFactoryStore
	 *
	 *	Returns {@see FactoryAbstract::$store}.
	 *
	 *	@return array
	 */
	public function getFactoryStore() {

		return $this->store;

	}

	/**
	 *	hasInstance
	 *
	 *	Checks whether a class exists in factory store.
	 *
	 *	@param string $name Instance identifier name.
	 *
	 *	@return bool
	 */
	public function hasInstance($name) {

		return (is_object($this->get($name)) && is_a($this->get($name), '\Brewery\Core\Factory\Object') === false);

	}

	/**
	 *	initialize
	 *
	 *	Creates a new instance of input class via {@man ReflectionClass}, throw exception if initialization failed.
	 *
	 *	@param string $className Class name, including namespace.
	 *	@param string $instance Name of class instance name.
	 *	@param array $parameters Optional parameter, should be an array of parameters for {@see $classMethod}.
	 *	@param string $classMethod Optional parameter, name of class method. Defaults to 'newInstance'.
	 *
	 *	@throws Exceptions\FactoryException
	 *
	 *	@return void
	 */
	public function initialize($className, $instance, $parameters = [], $classMethod = 'newInstance') {

		// Attempt to create a new instance of input class, via a ReflectionClass
		$this->set($instance, call_user_func_array([new \ReflectionClass($className), $classMethod], $parameters));

		// Throw exception if function call_user_func_array failed
		if($this->get($instance) === false) {

			unset($this->store[$instance]);

			throw new Exceptions\FactoryException(
				'Could not register class to factory store.', "Could not create a new instance of \"{$class}\".",
				Exceptions\FactoryException::UNEXPECTED_RESULT, __METHOD__
			);

		}

	}

}