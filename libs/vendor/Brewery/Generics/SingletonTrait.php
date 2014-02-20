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

/* @namespace Generics */
namespace Brewery\Generics;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	SingletonTrait
 *
 *	Trait used to create class singletons.
 *
 *	@vendor Brewery
 *	@package Generics
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait SingletonTrait {

	/**
	 *	@staticvar object $_instance Instance variable to itself.
	 */
	protected static $__instance;

	/**
	 *	Clone mutator
	 *
	 *	State is set to "final" and visibility is set to "private" to disable object cloning.
	 *
	 *	@return void
	 */
	final private function __clone() {}

	/**
	 *	getInstance
	 *
	 *	Returns instance of self, if no instance exists, one is created.
	 *
	 *	@return self
	 */
	public static function getInstance() {

		if(is_object(self::$__instance) === false) {

			self::$__instance = new self();

		}

		return self::$__instance;

	}

}