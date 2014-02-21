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

/* @namespace Route */
namespace Brewery\Routing\Route;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Controller
 *
 *	Route controller callback, inherits logic from {@see \Brewery\Routing\Route\Callback}.
 *
 *	@vendor Brewery
 *	@package Routing\Route
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Controller {

	/* @coalesce */
	use Callback;

}