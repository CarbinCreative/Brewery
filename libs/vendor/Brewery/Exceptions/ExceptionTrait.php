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
 *	ExceptionTrait
 *
 *	Exception trait containing logic to define framework specific exceptions, which can take additional information. Accepts a "reason" associated with exception message and exception context for better understanding and debugging.
 *
 *	@vendor Brewery
 *	@package Exceptions
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait ExceptionTrait {

	/**
	 *	@var string $context Exception context, structure context of thrown exception.
	 */
	protected $context;

	/**
	 *	@var string $reason Exception reason, optional reason for thrown exception, associated but not identical to exception message.
	 */
	protected $reason;

	/**
	 *	getContext
	 *
	 *	Returns registered exception context string.
	 *
	 *	@return null|string
	 */
	public function getContext() {

		return $this->context;

	}

	/**
	 *	getReason
	 *
	 *	Returns registered exception reason string.
	 *
	 *	@return null|string
	 */
	public function getReason() {

		return $this->reason;

	}

	/**
	 *	getCodeAsString
	 *
	 *	Implemented method must return exception error code as corresponding constant name.
	 *
	 *	@return string
	 */
	abstract public function getCodeAsString();

}