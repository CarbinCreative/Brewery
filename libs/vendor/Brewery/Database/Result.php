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

/* @namespace Database */
namespace Brewery\Database;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Result
 *
 *	Database statement result object.
 *
 *	@vendor Brewery
 *	@package Database
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Result {

	/**
	 *	Constructor
	 *
	 *	Passes through current connection and statement objects.
	 *
	 *	@param \Brewery\Database\Connection $connection Database connection object.
	 *	@param \Brewery\Database\Statement $statement Current statement object.
	 *
	 *	@return void
	 */
	public function __construct(Connection $connection, Statement $statement) {}

}