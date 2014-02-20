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
namespace Brewery\View;

/* @aliases */
use Brewery\View\StreamAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	CompilerAbstract
 *
 *	View compiler abstract.
 *
 *	@vendor Brewery
 *	@package View
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class CompilerAbstract extends StreamAbstract {

	/**
	 *	@var string $rawOutput Raw output data.
	 */
	protected $rawOutput;

	/**
	 *	@var string $compiledOutput Compiled output data.
	 */
	protected $compiledOutput;

	/**
	 *	@var array $compilerTokenCallbacks Compiler token callback handlers.
	 */
	protected $compilerTokenCallbacks = [];

	/**
	 *	@var string $viewFileExtension View file extension.
	 */
	protected $viewFileExtension;

	/**
	 *	@var string $viewFileExtensionPrefix View file extension prefix.
	 */
	protected $viewFileExtensionPrefix;

	/**
 	 *	registerCallback
 	 *
 	 *	Registers callback function for tokens.
 	 *
 	 *	@param callable $callback
 	 *
 	 *	@return void
	 */
	protected function registerCallback($callback) {

		if(in_array($callback, $this->compilerTokenCallbacks) === false) {

			$this->compilerTokenCallbacks[] = $callback;

		}

	}

	/**
	 *	compile
	 *
	 *	Iterates through each compiler token callback and compiles view output.
	 *
	 *	@return void
	 */
	public function compile() {

		if(strlen($this->compiledOutput) > 0 && count($this->compilerTokenCallbacks) > 0) {

			foreach($this->compilerTokenCallbacks as $compilerTokenCallback) {

				$this->compiledOutput = call_user_func_array([$this, $compilerTokenCallback], [$this->compiledOutput]);

			}

		}

	}

	/**
	 *	open
	 *
	 *	Pretty alias for {@see \Brewery\View\StreamAbstract::stream_open}.
	 *
	 *	@param string $path Stream path.
	 *	@param string $mode Stream mode.
	 *	@param string $options Stream options.
	 *	@param string $openedPath Stream opened path.
	 *
	 *	@return bool
	 */
	public function open($path, $mode, $options, $openedPath) {

		$viewProtocol = sprintf("%sView", strtolower($this->viewFileExtension));

		if(is_null($this->viewFileExtensionPrefix) === false) {

			$viewProtocol = sprintf("%s%sView", strtolower($this->viewFileExtensionPrefix), ucfirst($this->viewFileExtension));

		}

		$path = str_ireplace("{$viewProtocol}://", '', $path);

		$this->rawOutput = file_get_contents($path);

		$this->compiledOutput = $this->rawOutput;

		$this->stat = stat($path);

		$this->compile();

		$this->data = $this->compiledOutput;

		return true;

	}

}