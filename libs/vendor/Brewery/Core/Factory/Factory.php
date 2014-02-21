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

/* @namespace Factory */
namespace Brewery\Core\Factory;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Factory
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
class Factory extends FactoryAbstract {

	/**
	 *	Constructor
	 *
	 *	Invokes parent constructor, passes through an empty array.
	 *
	 *	@return void
	 */
	public function __construct() {

		parent::__construct([]);

	}

}