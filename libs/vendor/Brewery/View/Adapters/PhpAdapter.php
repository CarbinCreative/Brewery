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

/* @namespace Adapters */
namespace Brewery\View\Adapters;

/* @aliases */
use Brewery\View\ViewAbstract;
use Brewery\View\Compilers\PhpCompiler;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	PhpAdapter
 *
 *	View adapter for PHP files.
 *
 *	@vendor Brewery
 *	@package View\Adapters
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class PhpAdapter extends ViewAbstract {

	/* @coalesce \Brewery\View\Adapters\AdapterTrait */
	use \Brewery\View\Adapters\AdapterTrait;

	/**
	 *	Constructor
	 *
	 *	Invokes parent constructor and passes in instance of {@see Brewery\View\Compilers\PhpCompiler}.
	 *
	 *	@return void
	 */
	public function __construct() {

		parent::__construct(new PhpCompiler(), 'php');

	}

}