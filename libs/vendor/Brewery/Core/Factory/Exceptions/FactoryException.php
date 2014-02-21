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

/* @namespace Exceptions */
namespace Brewery\Core\Factory\Exceptions;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	FactoryException
 *
 *	Factory specific exception class.
 *
 *	@vendor Brewery
 *	@package Brewery\Core\Factory
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class FactoryException extends \Brewery\Exceptions\Exception {}