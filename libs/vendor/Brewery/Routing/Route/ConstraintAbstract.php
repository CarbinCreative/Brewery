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

/* @namespace Route */
namespace Brewery\Routing\Route;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ConstraintAbstract
 *
 *	Route callback constraint, implemented class MUST contain logic used to allow, or disallow specified route calls.
 *
 *	@vendor Brewery
 *	@package Routing\Route
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class ConstraintAbstract {

	/**
	 *	validate
	 *
	 *	Implemented method MUST contain logic  contain logic used to allow, or disallow specified route call. MUST return boolan.
	 *
	 *	@return bool
	 */
	abstract public function validate();

}