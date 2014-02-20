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
use Brewery\View\CompilerAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ViewAbstract
 *
 *	View abstract.
 *
 *	@vendor Brewery
 *	@package View
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class ViewAbstract {

	/**
	 *	@var \Brewery\View\CompilerAbstract $compiler View compiler.
	 */
	protected $compiler;

	/**
	 *	@var \Brewery\View\BlueprintAbstract $blueprint View blueprint.
	 */
	protected $blueprint;

	/**
	 *	@var array $variables View variable store.
	 */
	protected $variables;

	/**
	 *	@var string $includePath Include path.
	 */
	protected $includePath;

	/**
	 *	@var string $viewFile View file path.
	 */
	protected $viewFile;

	/**
	 *	@var string $viewFileExtension View file extension.
	 */
	protected $viewFileExtension;

	/**
	 *	@var string $viewFileExtensionPrefix View file extension prefix.
	 */
	protected $viewFileExtensionPrefix;

	/**
	 *	@var string $viewProtocol View file protocol.
	 */
	protected $viewProtocol;

	/**
	 *	Constructor
	 *
	 *	Creates a new view instance.
	 *
	 *	@param \Brewery\View\CompilerAbstract $compiler View compiler.
	 *	@param string $viewFileExtension View file extension.
	 *
	 *	@return void
	 */
	public function __construct(CompilerAbstract $compiler, $viewFileExtension, $viewFileExtensionPrefix = null) {

		$this->compiler = $compiler;

		$this->viewFileExtension = $viewFileExtension;

		$this->viewFileExtensionPrefix = $viewFileExtensionPrefix;

		$this->viewProtocol = sprintf("%sView", strtolower($this->viewFileExtension));

		if(is_null($this->viewFileExtensionPrefix) === false) {

			$this->viewProtocol = sprintf("%s%sView", strtolower($this->viewFileExtensionPrefix), ucfirst($this->viewFileExtension));

		}

		if(in_array($this->viewProtocol, stream_get_wrappers()) === false) {

			if(stream_wrapper_register($this->viewProtocol, get_class($this->compiler)) === false) {

				throw new Exceptions\ViewException(
					"Could not register stream wrapper \"" . get_class($this->compiler) . "\" to protocol \"{$this->viewProtocol}\".", "Stream wrapper failed.",
					Exceptions\ViewException::INVALID_ARGUMENT, __METHOD__
				);

			}

		}

		$this->variables['__view'] = $this;

	}

	/**
	 *	getBlueprint
	 *
	 *	Sets view blueprint.
	 *
	 *	@param \Brewery\View\BlueprintAbstract $blueprint
	 *
	 *	@return void
	 */
	public function setBlueprint(BlueprintAbstract $blueprint) {

		$this->blueprint = $blueprint;

		if(is_null($this->blueprint->getLayout()) === false) {

			$this->hasLayout = true;

			$this->hasRenderedLayout = false;

		}

	}

	/**
	 *	getBlueprint
	 *
	 *	Returns blueprint name.
	 *
	 *	@return string
	 */
	public function getBlueprint() {

		return get_class($this->blueprint);

	}

	/**
	 *	blueprint
	 *
	 *	Returns blueprint object.
	 *
	 *	@return \Brewery\View\BlueprintAbstract
	 */
	public function blueprint() {

		return $this->blueprint;

	}

	/**
	 *	setIncludePath
	 *
	 *	Sets the include path
	 *
	 *	@param string $includePath View file include path.
	 *
	 *	@return void
	 */
	public function setIncludePath($includePath) {

		$this->includePath = $includePath;

	}

	/**
	 *	getIncludePath
	 *
	 *	Returns current include path.
	 *
	 *	@param string $includePath View file include path.
	 *
	 *	@return void
	 */
	public function getIncludePath() {

		return $this->includePath;

	}

	/**
	 *	includePath
	 *
	 *	Alias for {@see Brewery\View\ViewAbstract::getIncludePath}
	 *
	 *	@param string $includePath View file include path.
	 *
	 *	@return void
	 */
	public function includePath() {

		return $this->includePath;

	}

	/**
	 *	Setter
	 *
	 *	Stores view variable.
	 *
	 *	@param string $key Variable name.
	 *	@param mixed $object Variable data.
	 *
	 *	@return void
	 */
	public function __set($key, $object) {

		$this->variables[$key] = $object;

	}

	/**
	 *	Getter
	 *
	 *	Returns view variable.
	 *
	 *	@param string $key Variable name.
	 *
	 *	@return mixed
	 */
	public function __get($key) {

		if(array_key_exists($key, $this->variables) === true) {

			return $this->variables[$key];

		}

		return null;

	}

	/**
	 *	setVariables
	 *
	 *	Sets many variables to view variable store.
	 *
	 *	@param array $variables View variables.
	 *
	 *	@return void
	 */
	public function setVariables(Array $variables) {

		$this->variables = array_merge($this->variables, $variables);

	}

	/**
	 *	getVariables
	 *
	 *	Returns variable store.
	 *
	 *	@return array
	 */
	public function getVariables() {

		return $this->variables;

	}

	/**
	 *	import
	 *
	 *	Imports view file.
	 *
	 *	@param string $viewFile View file name.
	 *
	 *	@return void
	 */
	public function import($viewFile) {

		$variables = $this->getVariables();

		if(is_array($variables) === true && count($variables) > 0) {

			extract($variables);

		}

		$viewFileExtension = $this->viewFileExtension;

		if(strlen($this->viewFileExtensionPrefix) > 0) {

			$viewFileExtension = sprintf("%s.%s", $this->viewFileExtensionPrefix, $viewFileExtension);

		}

		$includePath = $this->includePath;

		if($this->hasLayout === true && $this->hasRenderedLayout === false) {

			$includePath = BREWERY_LAYOUTS_PATH;

			$this->hasRenderedLayout = true;

		}

		require_once sprintf("%s://%s.%s", $this->viewProtocol, $includePath . $viewFile, $viewFileExtension);

	}

	/**
	 *	render
	 *
	 *	Implemented method must return rendered view as a string.
	 *
	 *	@param string $viewFile View file.
	 *	@param array $variables View variables.
	 *
	 *	@return string
	 */
	abstract public function render($viewFile, Array $variables = []);

	/**
	 *	toString
	 *
	 *	Calls {@see \Brewery\View\ViewAbstract::render}.
	 *
	 *	@return string
	 */
	public function __toString() {

		return $this->render();

	}

}