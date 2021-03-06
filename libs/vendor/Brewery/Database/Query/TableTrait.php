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

/* @namespace Query */
namespace Brewery\Database\Query;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	TableTrait
 *
 *	Trait used for tables.
 *
 *	@vendor Brewery
 *	@package Database\Query
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait TableTrait {

	/**
	 *	@var string $name
	 */
	protected $name;

	/**
	 *	@var string $alias
	 */
	protected $alias;

	/**
	 *	@var bool $hasAlias
	 */
	protected $hasAlias = false;

	/**
 	 *	@var array $properties
	 */
	protected $properties = [];

}