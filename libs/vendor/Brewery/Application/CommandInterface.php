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
 *	CommandInterface
 *
 *	Application command interface.
 *
 *	@vendor Brewery
 *	@package Application
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
interface CommandInterface {

	/**
	 *	getOptions
	 *
	 *	Implemented method MUST return array with valid options for command interpreter.
	 *
	 *	@return array
	 */
	public function getOptions();

	/**
	 *	handleCommand
	 *
	 *	Implemented method SHOULD handle command based on arguments.
	 *
	 *	@param array $arguments Array containing arguments passed to command.
	 *
	 *	@return array
	 */
	public function handleCommand(Array $arguments);

}