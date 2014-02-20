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

/* @namespace Services */
namespace Brewery\Services;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	AutoloaderService
 *
 *	Autoloader service handler, invokes registered locators.
 *
 *	@vendor Brewery
 *	@package Services
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class AutoloaderService implements ServiceInterface {

	/**
	 *	@var array $namespaceLocators Array containing namespace based locators.
	 */
	protected $namespaceLocators = [];

	/**
	 *	registerNamespaceLocator
	 *
	 *	Registeres a new namespace locator class to a defined namespace.
	 *
	 *	@param string $namespace Namespace, full or in part, to associate to a locator class.
	 *	@param LocatorServiceAbstract $locator Instance of a custom locator service class.
	 *
	 *	@throws \Brewery\Services\Exceptions\AutoloaderServiceException
	 *
	 *	@return void
	 */
	public function registerNamespaceLocator($namespace, LocatorServiceAbstract $locator) {

		if(preg_match(NAMESPACE_PCRE, $namespace) === 0) {

			throw new Exceptions\AutoloaderServiceException(
				sprintf("Could not register namespace locator \"%s\" to namespace \"%s\".", get_class($locator), $namespace),
				'Namespace contains illegal characters, must be alphanumeric and may contain underscores and namespace separator.',
				Exceptions\AutoloaderServiceException::INVALID_ARGUMENT, __METHOD__
			);

		}

		if(array_key_exists($namespace, $this->namespaceLocators) === true) {

			throw new Exceptions\AutoloaderServiceException(
				sprintf("Could not register namespace locator \"%s\" to namespace \"%s\".", get_class($locator), $namespace),
				'Namespace already has a locator registered to it.',
				Exceptions\AutoloaderServiceException::BAD_CALL, __METHOD__
			);

		}

		$this->namespaceLocators[$namespace] = $locator;

	}

	/**
	 *	unregisterNamespaceLocator
	 *
	 *	Unregisters an existing namespace locator from class member store.
	 *
	 *	@param string $namespace Namespace, full or in part, to associate to a locator class.
	 *
	 *	@return void
	 */
	public function unregisterNamespaceLocator($namespace) {

		if(array_key_exists($namespace, $this->namespaceLocators) === true) {

			unset($this->namespaceLocators[$namespace]);

		}

	}

	/**
	 *	getNamespaceLocator
	 *
	 *	Attempts to get a registered namespace locator.
	 *
	 *	@param string $className Class name including namespace.
	 *
	 *	@return null|LocatorService
	 */
	protected function getNamespaceLocator($className) {

		$namespaceLocator = null;

		foreach($this->namespaceLocators as $namespace => $locator) {

			if(strlen(stristr($className, $namespace)) > 0) {

				$namespaceLocator = $locator;

				break;

			}

		}

		return $namespaceLocator;

	}

	/**
	 *	register
	 *
	 *	Registerers {@see load} as an autoloader callback.
	 *
	 *	@return bool
	 */
	public function register() {

		return spl_autoload_register(array($this, 'load'));

	}

	/**
	 *	unregister
	 *
	 *	Unregisterers {@see load} as an autoloader callback.
	 *
	 *	@return bool
	 */
	public function unregister() {

		return spl_autoload_unregister(array($this, 'load'));

	}

	/**
	 *	load
	 *
	 *	Fetches namespace locator and invokes it's import method.
	 *
	 *	@param string $className Class name including namespace.
	 *
	 *	@return bool
	 */
	public function load($className) {

		$locator = $this->getNamespaceLocator($className);

		if($locator === null) {

			return false;

		}

		$locator->import($className);

		return class_exists($className);

	}

}