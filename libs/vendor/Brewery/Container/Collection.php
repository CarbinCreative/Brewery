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

/* @namespace Container */
namespace Brewery\Container;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Collection
 *
 *	Collection class used for object or array collection of data.
 *
 *	@vendor Brewery
 *	@package Container
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Collection {

	/**
	 *	@var array $collection Data collection.
	 */
	protected $collection = [];

	/**
	 *	Constructor
	 *
	 *	Invokes {@see Collection::setCollection}.
	 *
	 *	@param array|object $collection Data collection.
	 *
	 *	@return void
	 */
	public function __construct($collection) {

		$this->setCollection($collection);

	}

	/**
	 *	setCollection
	 *
	 *	Sets collection data.
	 *
	 *	@param array|object $collection Data collection.
	 *
	 *	@return void
	 */
	public function setCollection($collection) {

		$this->collection = $collection;

	}

	/**
	 *	getCollection
	 *
	 *	Returns registered collection.
	 *
	 *	@return array|object
	 */
	public function getCollection() {

		return $this->collection;

	}

}