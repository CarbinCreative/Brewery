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
 *	ErrorException
 *
 *	Error exception class for captured for triggered errors, inherits from {@see \Brewery\Exceptions\ExceptionAbstract}.
 *
 *	@vendor Brewery
 *	@package Exceptions
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class ErrorException extends ExceptionAbstract {

	/**
	 *	getCodeAsString
	 *
	 *	Returns error code constant as a string.
	 *
	 *	@return string
	 */
	public function getCodeAsString() {

		switch($this->getCode()) {

			case @E_RECOVERABLE_ERROR :

				return 'CATCHABLE';

				break;
			case E_USER_ERROR :

				return 'FATAL';

				break;
			case E_WARNING :
			case E_USER_WARNING :

				return 'WARNING';

				break;
			case E_NOTICE :
			case E_USER_NOTICE :
			case @E_STRICT :

				return 'NOTICE';

				break;
			default :

				return 'UNKNOWN';

			break;

		}

	}

}