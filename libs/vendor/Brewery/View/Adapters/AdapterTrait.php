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

/* @namespace Adapters */
namespace Brewery\View\Adapters;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	AdapterTrait
 *
 *	Trait used for most view adapters.
 *
 *	@vendor Brewery
 *	@package Generics
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait AdapterTrait {

	/**
	 *	render
	 *
	 *	Captures output and sets additional variables.
	 *
	 *	@param string $viewFile
	 *	@param $variables
	 *
	 *	@return string
	 */
	public function render($viewFile, Array $variables = []) {

		$this->compiler->compile();

		$this->setVariables($variables);

		if(is_null($this->blueprint) === false && is_null($this->blueprint->getLayout()) === false) {

			$this->setVariables([
				'__viewFile' => $viewFile
			]);

			$viewFile = $this->blueprint->getLayout();

			$this->blueprint->setLayout(null);

		}

		ob_start();

		$this->import($viewFile);

		$output = ob_get_clean();

		return $output;

	}

}