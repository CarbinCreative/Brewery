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

/* @namespace Locators */
namespace Brewery\Services\Locators;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	VendorLocatorService
 *
 *	Class used to load vendor package files.
 *
 *	@vendor Brewery
 *	@package Services\Locators
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class VendorLocatorService extends \Brewery\Services\LocatorServiceAbstract {

	/**
	 *	resolveFilePath
	 *
	 *	Abstract method MUST resolve file path, and return resolved path as a string.
	 *
	 *	@param string $filePath File path name to resolve path for.
	 *
	 *	@return string
	 */
	protected function resolveFilePath($filePath) {

		$filePath = \Brewery\clean(str_replace(NAMESPACE_SEPARATOR, '/', $filePath), '/');

		return \Brewery\path($this->includePath . "{$filePath}.php", true);

	}

	/**
	 *	import
	 *
	 *	Abstract method MUST call {@see \Brewery\Services\LocatorServiceAbstract::resolveFilePath} and SHOULD validate file integrity.
	 *
	 *	@param string $classPath Class name to import.
	 *
	 *	@return void
	 */
	public function import($classPath) {

		$includePath = $this->resolveFilePath(\Brewery\clean($classPath, NAMESPACE_SEPARATOR));

		if(file_exists($includePath) === false) {

			throw new \Brewery\Services\Exceptions\AutoloaderServiceException(
				"Could not include file.", "File '{$includePath}' does not exist.",
				\Brewery\Services\Exceptions\AutoloaderServiceException::UNEXPECTED_RESULT, __METHOD__
			);

		}

		require_once $includePath;

	}

}