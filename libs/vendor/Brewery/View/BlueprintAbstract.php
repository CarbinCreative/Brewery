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

/* @namespace View */
namespace Brewery\View;

/* @aliases */
use Brewery\View\CompilerAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	BlueprintAbstract
 *
 *	Blueprint used to define view blueprints.
 *
 *	@vendor Brewery
 *	@package View
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class BlueprintAbstract {

	/**
	 *	@var array $httpHeaders
	 */
	protected $httpHeaders = [];

	/**
	 *	@var string $layout
	 */
	protected $layout;

	/**
	 *	Constructor
	 *
	 *	Sets view default headers, layout and compiler.
	 *
	 *	@param array $httpHeaders
	 *	@param string $layout
	 *
	 *	@return void
	 */
	public function __construct(Array $httpHeaders = [], $layout = null) {

		$this->httpHeaders = $httpHeaders;

		if(is_string($layout) === true) {

			$this->layout = $layout;

		}

		\Brewery\httpHeaders($this->httpHeaders);

	}

	/**
	 *	setLayout
	 *
	 *	Sets layout.
	 *
	 *	@param string $layout
	 *
	 *	@return void
	 */
	public function setLayout($layout) {

		$this->layout = $layout;

	}

	/**
	 *	getLayout
	 *
	 *	Returns layout.
	 *
	 *	@return string
	 */
	public function getLayout() {

		return $this->layout;

	}

}