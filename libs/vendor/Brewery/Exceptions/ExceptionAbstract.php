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
 *	ExceptionAbstract
 *
 *	Abstract used to define framework specific exceptions, uses {@see \Brewery\Exceptions\ExceptionTrait}.
 *
 *	@vendor Brewery
 *	@package Exceptions
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class ExceptionAbstract extends \Exception {

	/* @coalesce */
	use ExceptionTrait;

	/**
	 *	@const int NATIVE Exception code for native PHP exceptions.
	 */
	const NATIVE = 1;

	/**
	 *	@const int BAD_CALL Bad call exception code.
	 */
	const BAD_CALL = 2;

	/**
	 *	@const int TYPEHINT Typehint exception code.
	 */
	const TYPEHINT = 4;

	/**
	 *	@const int MISSING_ARGUMENT Missing argument exception code.
	 */
	const MISSING_ARGUMENT = 8;

	/**
	 *	@const int INVALID_ARGUMENT Invalid argument exception code.
	 */
	const INVALID_ARGUMENT = 16;

	/**
	 *	@const int MALFORMED_ARGUMENT Malformed argument exception code.
	 */
	const MALFORMED_ARGUMENT = 32;

	/**
	 *	@const int EMPTY_RESULT Empty result exception code.
	 */
	const EMPTY_RESULT = 64;

	/**
	 *	@const int UNEXPECTED_RESULT Unexpected result exception code.
	 */
	const UNEXPECTED_RESULT = 128;

	/**
	 *	@const int DOMAIN Domain exception code.
	 */
	const DOMAIN = 256;

	/**
	 *	@const int RUNTIME Runtime exception code.
	 */
	const RUNTIME = 512;

	/**
	 *	@const int NETWORK Network exception code.
	 */
	const NETWORK = 1024;

	/**
	 *	@const int GENERIC Generic exception code.
	 */
	const GENERIC = 2048;

	/**
	 *	Constructor
	 *
	 *	Sets exception properties if applicable and invokes exception parent constructor.
	 *
	 *	@param string $message Exception message, brief exception description.
	 *	@param string $reason Exception reason, precise exception description.
	 *	@param int|constant $code Exception error code.
	 *	@param string $context Exception context string.
	 *
	 *	@return void
	 */
	public function __construct($message, $reason = null, $code = self::GENERIC, $context = null) {

		if(is_string($reason) === true) {

			$this->reason = $reason;

		}

		if(is_string($context) === true) {

			$this->context = $context;

		}

		parent::__construct($message, $code);

	}

}