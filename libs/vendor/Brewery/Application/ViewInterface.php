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

/* @namespace Application */
namespace Brewery\Application;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ViewInterface
 *
 *	Application view interface.
 *
 *	@vendor Brewery
 *	@package Application
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
interface ViewInterface {

	/**
	 *	render
	 *
	 *	Implemented method MUST return rendered output as a string.
	 *
	 *	@return string
	 */
	public function render();

}