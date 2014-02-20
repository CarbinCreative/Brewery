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

/* @namespace Container */
namespace Brewery\Container;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Coordinates
 *
 *	Container coordinates object.
 *
 *	@vendor Brewery
 *	@package Container
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Coordinates {

	/**
	 *	@var int $length
	 */
	public $length;

	/**
	 *	@var int $segments
	 */
	public $segments;

	/**
	 *	@var int $limit
	 */
	public $limit;

	/**
	 *	@var int $offset
	 */
	public $offset;

	/**
	 *	@var int $pointer
	 */
	public $pointer;

	/**
	 *	Constructor
	 *
	 *	Sets object properties.
	 *
	 *	@param array $properties
	 *
	 *	@return void
	 */
	public function __constructor(Array $properties) {

		foreach($properties as $property => $value) {

			$this->$property = $value;

		}

	}

}