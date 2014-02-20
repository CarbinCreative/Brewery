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

/* @namespace Route */
namespace Brewery\Routing\Route;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Callback
 *
 *	Route callback trait, used to create route action and route controller callbacks.
 *
 *	@vendor Brewery
 *	@package Routing\Route
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait Callback {

	/**
	 *	@var string $regexInflections Inflections for callbacks.
	 */
	private $regexInflections = '/(action|model|view|controller|constraint)$/i';

	/**
	 *	@var string $name Route callback name.
	 */
	protected $name;

	/**
	 *	@var string $callback Route callback name.
	 */
	protected $callback;

	/**
	 *	@var array $parameters Route callback parameters.
	 */
	protected $parameters;

	/**
	 *	@var bool $isOptional Specifies whether or not callback is optional.
	 */
	protected $isOptional;

	/**
	 *	@var bool $ignoreComponent Specifies whether or not to ignore component paths.
	 */
	protected $ignoreComponent = false;

	/**
	 *	Constructor
	 *
	 *	Sets callback name, callback parameters and whether or not callback is optional.
	 *
	 *	@param string $name Route callback name.
	 *	@param string $callback Route callback method name.
	 *	@param array $parameters Route callback parameters.
	 *	@param bool $isOptional Specifies whether or not callback is optional.
	 *	@param bool $ignoreComponent Specifies whether or not to ignore component paths.
	 *
	 *	@return void
	 */
	public function __construct($name, $callback, $parameters = [], $isOptional = null, $ignoreComponent = false) {

		$this->setName($name);

		$this->setCallback($callback);

		$this->setParameters($parameters);

		$this->isOptional($isOptional);

		if(is_bool($ignoreComponent) === true) {

			$this->ignoreComponent = $ignoreComponent;

		}

	}

	/**
	 *	setName
	 *
	 *	Sets route callback name.
	 *
	 *	@param string $name Route callback name.
	 *
	 *	@throws \Brewery\Routing\Route\Exceptions\CallbackException
	 *
	 *	@return void
	 */
	public function setName($name) {

		if(is_string($name) === false) {

			throw new Exceptions\CallbackException(
				'Could not set router callback name.', 'Argument 1 passed to ' . __METHOD__ . ' must be a string, ' . gettype($name) . ' given.',
				Exceptions\CallbackException::TYPEHINT, __METHOD__
			);

		}

		$this->name = $name;

	}

	/**
	 *	getName
	 *
	 *	Returns route callback name.
	 *
	 *	@return string
	 */
	public function getName() {

		return trim($this->name, '/');

	}

	/**
	 *	setCallback
	 *
	 *	Sets route callback name.
	 *
	 *	@param string $callback Route callback name.
	 *
	 *	@throws \Brewery\Routing\Route\Exceptions\CallbackException
	 *
	 *	@return void
	 */
	public function setCallback($callback) {

		if(is_string($callback) === false) {

			throw new Exceptions\CallbackException(
				'Could not set router callback.', 'Argument 1 passed to ' . __METHOD__ . ' must be a string, ' . gettype($callback) . ' given.',
				Exceptions\CallbackException::TYPEHINT, __METHOD__
			);

		}

		$this->callback = $callback;

	}

	/**
	 *	getCallback
	 *
	 *	Returns route callback name.
	 *
	 *	@return string
	 */
	public function getCallback() {

		return $this->callback;

	}

	/**
	 *	setParameters
	 *
	 *	Sets route callback parameters.
	 *
	 *	@param array $parameters Route callback parameters.
	 *
	 *	@return void
	 */
	public function setParameters(Array $parameters) {

		$this->parameters = $parameters;

	}

	/**
	 *	getParameters
	 *
	 *	Returns route callback parameters.
	 *
	 *	@return array
	 */
	public function getParameters() {

		return $this->parameters;

	}

	/**
	 *	isOptional
	 *
	 *	Returns or sets callback optional flag if input parameter is set to true or false.
	 *
	 *	@param bool $isOptional Specifies whether or not callback is optional or not.
	 *
	 *	@return bool|void
	 */
	public function isOptional($isOptional = null) {

		if(is_null($isOptional) === true) {

			if(is_bool($this->isOptional) === true) {

				return $this->isOptional;

			}

			return true;

		} else {

			if(is_bool($isOptional) === true) {

				$this->isOptional = $isOptional;

			}

		}

	}

	/**
	 *	namespacePath
	 *
	 *	Returns callback namespace path (class name with correnct namespace).
	 *
	 *	@return string
	 */
	public function namespacePath() {

		return implode(NAMESPACE_SEPARATOR, [BREWERY_APPLICATION_NAMESPACE, trim(str_replace(__NAMESPACE__, '', __CLASS__), NAMESPACE_SEPARATOR), $this->getName()]);

	}

	/**
	 *	includePath
	 *
	 *	Returns callback include path.
	 *
	 *	@return string
	 */
	public function includePath() {

		$callbackTypePath = trim(str_replace(__NAMESPACE__, '', __CLASS__), NAMESPACE_SEPARATOR);

		$callbackTypePath = preg_replace($this->regexInflections, "$1s", $callbackTypePath);

		$includePath = $callbackTypePath . DIRECTORY_SEPARATOR . $this->getName() . '.php';

		if(defined('BREWERY_COMPONENT_PATH') === true && $this->ignoreComponent === false) {

			$includePath = BREWERY_COMPONENT_PATH  . $includePath;

		} else {

			$includePath = BREWERY_ROOT_PATH . BREWERY_APPLICATION_PATH_NAME . DIRECTORY_SEPARATOR .  $includePath;

		}

		$includePath = preg_replace('/\\' . DIRECTORY_SEPARATOR . '{2,}/', DIRECTORY_SEPARATOR, $includePath);

		return $includePath;

	}

	/**
	 *	exists
	 *
	 *	Retursn boolean whether or not callback exists or not.
	 *
	 *	@return bool
	 */
	public function exists() {

		$includePath = $this->includePath();

		return file_exists($includePath);

	}

	/**
	 *	import
	 *
	 *	Imports callback resource if it exists.
	 *
	 *	@return void
	 */
	public function import() {

		if($this->exists() === true) {

			require_once $this->includePath();

		} else {

			if($this->isOptional() === false) {

				$resource = str_ireplace(BREWERY_ROOT_PATH, '', $this->includePath());

				throw new Exceptions\CallbackException(
					'Could not import route resource.', "Route resource '{$resource}' does not exist.",
					Exceptions\CallbackException::UNEXPECTED_RESULT, __METHOD__
				);

			}

		}

	}

	/**
	 *	invoke
	 *
	 *	Invokes callback.
	 *
	 *	@return mixed
	 */
	public function invoke() {

		if($this->exists() === false || $this->isOptional() === true) {

			return null;

		}

		$reflection = new \ReflectionClass($this->namespacePath());

		$instance = call_user_func_array([$reflection, 'newInstance'], []);

		if($reflection->hasMethod($this->getCallback()) === true) {

			return call_user_func_array([$instance, $this->getCallback()], $this->getParameters());

		} else {

			\Brewery\httpStatus(405);

			throw new Exceptions\CallbackException(
				'Could not invoke route.', sprintf('Route callback %s::%s does not exist.', $this->getName(), $this->getCallback()),
				Exceptions\CallbackException::TYPEHINT, __METHOD__
			);

		}

	}

}