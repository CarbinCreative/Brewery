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
 *	Object
 *
 *	Handles class initialization and enables chaining functionality to classes initialized via {@see \Brewery\Core\Factory\FactoryAbstract::initialize}.
 *
 *	@vendor Brewery
 *	@package Core\Factory
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Object extends FactoryAbstract {

	/**
	 *	Constructor
	 *
	 *	Invokes parent constructor.
	 *
	 *	@param array $store Factory store array, contains class instance references.
	 *	@param string $identifier Instance identifier.
	 *	@param \Brewery\Core\Factory\FactoryAbstract $parent Reference to parent class, an instance of {@see \Brewery\Core\Factory\FactoryAbstract}.
	 *
	 *	@return void
	 */
	public function __construct(Array $store, $identifier = null, FactoryAbstract $parent = null) {

		parent::__construct($store, $identifier, $parent);

	}

}