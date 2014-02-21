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

/* @namespace Generics */
namespace Brewery\Generics;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	RegistryTrait
 *
 *	Trait used to create registry classes.
 *
 *	@vendor Brewery
 *	@package Generics
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait RegistryTrait {

	/**
	 *	@var array $registryStore Registry store.
	 */
	protected $registryStore = [];

	/**
	 *	Mutator
	 *
	 *	Sets or overwrites property in registry.
	 *
	 *	@param string $property Property identifier.
	 *	@param mixed $propertyData Property data.
	 *
	 *	@return void
	 */
	public function __set($property, $propertyData) {

		$this->registryStore[$property] = $propertyData;

	}

	/**
	 *	Accessor
	 *
	 *	Returns property in registry, if it exists.
	 *
	 *	@param string $property Property identifier.
	 *
	 *	@return mixed
	 */
	public function __get($property) {

		return $this->registryStore[$property];

	}

}