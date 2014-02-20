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

/* @namespace View */
namespace app\View\Blueprints;

/* @aliases */
use Brewery\View\BlueprintAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Pistachio
 *
 *	Pistachio blueprint.
 *
 *	@vendor Brewery
 *	@package View
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Pistachio extends BlueprintAbstract {

	/**
	 *	@var array $httpHeaders
	 */
	protected $httpHeaders = [
		'X-Brewery-Version' => '1.0'
	];

	protected $layout = 'pistachio';

}