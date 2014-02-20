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
 *	@license http://opensource.org/licenses/MIT MIT
 */

/* @namespace Query */
namespace Brewery\Database\Query;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Builder
 *
 *	Simple query builder class.
 *
 *	@vendor Brewery
 *	@package Database\Query
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Builder {

	/* @coalesce */
	use TableTrait, TablePropertyTrait;

}