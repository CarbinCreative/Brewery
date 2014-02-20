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
 *	Fragment
 *
 *	Container data fragment abstract.
 *
 *	@vendor Brewery
 *	@package Container
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class Fragment {

	/**
	 *	@var \Brewery\Container\Collection $collection Instance of {@see \Brewery\Container\Collection}.
	 */
	protected $collection;

	/**
	 *	@var \Brewery\Container\FragmentCoordinates $coordinates Instance of {@see \Brewery\Container\FragmentCoordinates}.
	 */
	protected $coordinates;

	/**
	 *	registerObjects
	 *
	 *	Registers {@see \Brewery\Container\Collection} object and {@see \Brewery\Container\FragmentCoordinates} object.
	 *
	 *	@param \Brewery\Container\Collection $collection Instance of {@see \Brewery\Container\Collection}.
	 *	@param \Brewery\Container\FragmentCoordinates $coordinates Instance of {@see \Brewery\Container\FragmentCoordinates}.
	 *
	 *	@return void
	 */
	public function registerObjects(Collection $collection, FragmentCoordinates $coordinates) {

		$this->collection = $collection;

		$this->coordinates = $coordinates;

	}

	/**
	 *	getCollectionObject
	 *
	 *	Returns registered colleciton object.
	 *
	 *	@return object
	 */
	public function getCollectionObject() {

		return $this->collection;

	}

	/**
	 *	getCoordinatesObject
	 *
	 *	Returns registered coordinates object.
	 *
	 *	@return object
	 */
	public function getCoordinatesObject() {

		return $this->coordinates;

	}

	/**
	 *	extract
	 *
	 *	Should contain logic to extract a segment of data form registered {@see \Brewery\Container\Fragment::$collection} object based on the properties of registered {@see \Brewery\Container\Fragment::$coordinates} object, may return a new {@see \Brewery\Container\Collection} object.
	 *
	 *	@return mixed
	 */
	abstract public function extract();

}