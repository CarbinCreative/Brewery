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

/* @namespace Http */
namespace Brewery\Http;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ResourceAbstract
 *
 *	HTTP resource abstract.
 *
 *	@vendor Brewery
 *	@package Http
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class ResourceAbstract {

	/**
	 *	@var \Brewery\Http\Client $httpClient Instance of {@see \Brewery\Http\Client}.
	 */
	protected $httpClient;

	/**
	 *	Constructor
	 *
	 *	Prepares HTTP request parameters.
	 *
	 *	@param \Brewery\Http\Client $httpClient Instance of {@see \Brewery\Http\Client}.
	 *	@param array $requestParameters Request parameters.
	 *
	 *	@return void
	 */
	public function __construct(Client $httpClient, Array $requestParameters = null) {

		$this->httpClient = $httpClient;

		$this->httpClient->prepare();

		$this->requestParameters = (is_null($requestParameters) === true) ? [] : $requestParameters;

	}

	/**
	 *	allowedMethods
	 *
	 *	Returns array containing allowed resource methods.
	 *
	 *	@return array
	 */
	protected function allowedMethods() {

		$definedMethods = [];

		$reflectionMethods = (new \ReflectionClass($this))->getMethods(\ReflectionMethod::IS_PUBLIC);

		foreach($reflectionMethods as $method) {

			$definedMethods[] = strtoupper($method->getName());

		}

		return array_intersect($this->httpClient->requestMethods(), $definedMethods);

	}

	/**
	 *	Callback
	 *
	 *	Whenever an undefined resource method is called, send "Method Not Allowed" status.
	 *
	 *	@param string $method Method name.
	 *	@param array $arguments Method arguments.
	 *
	 *	@return void
	 */
	public function __call($method, $arguments) {

		$this->httpClient->setStatusCode(405);

		$this->httpClient->setHeader('Allow', implode(', ', $this->allowedMethods()));

	}

}