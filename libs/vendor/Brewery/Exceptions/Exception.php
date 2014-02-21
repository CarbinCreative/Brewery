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

/* @namespace Exceptions */
namespace Brewery\Exceptions;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Exception
 *
 *	Generic exception base class, inherits from {@see \Brewery\Exceptions\ExceptionAbstract}.
 *
 *	@vendor Brewery
 *	@package Exceptions
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Exception extends ExceptionAbstract {

	/**
	 *	getCodeAsString
	 *
	 *	Returns exception constant as a string, based on exception code.
	 *
	 *	@return string
	 */
	public function getCodeAsString() {

		$constants = array_flip((new \ReflectionClass(get_parent_class($this)))->getConstants());

		return $constants[$this->getCode()];

	}

}