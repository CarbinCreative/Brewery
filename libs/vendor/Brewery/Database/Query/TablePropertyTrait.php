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

/* @namespace Query */
namespace Brewery\Database\Query;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	TablePropertyTrait
 *
 *	Trait used for table properties (columns).
 *
 *	@vendor Brewery
 *	@package Database\Query
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait TablePropertyTrait {

	/**
	 *	registerProperty
	 *
	 *	Registers table property.
	 *
	 *	@param string $name
	 *	@param string $sqlType
	 *	@param string $dataType
	 *	@param bool $allowNull
	 *
	 *	@return void
	 */
	protected function registerProperty($name, $sqlType, $dataType, $allowNull) {

		$this->properties[$name] = (object) [
			'name' => $name,
			'sqlType' => $sqlType,
			'dataType' => $dataType,
			'allowNull' => $allowNull
		];

	}

	/**
	 *	registerProperty
	 *
	 *	Unregisters table property.
	 *
	 *	@return void
	 */
	protected function unregisterProperty($name) {

		if(array_key_exists($name, $this->properties) === true) {

			unset($this->properties[$name]);

		}

	}

	/**
	 *	propertyName
	 *
	 *	Returns property name with alias if it exists.
	 *
	 *	@return string
	 */
	protected function propertyName($property) {

		if(array_key_exists($property, $this->properties) === true) {

			if($this->hasAlias === true) {

				return sprintf('%s.%s', $this->alias, $property);

			}

			return $property;

		}

		return null;

	}

}