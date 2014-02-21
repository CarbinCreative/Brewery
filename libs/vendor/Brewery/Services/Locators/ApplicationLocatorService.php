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

/* @namespace Locators */
namespace Brewery\Services\Locators;

/* @imports */
use Brewery\Services\LocatorServiceAbstract;
use Brewery\Services\Exceptions\AutoloaderServiceException;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ApplicationLocatorService
 *
 *	Class used to load application files.
 *
 *	@vendor Brewery
 *	@package Services\Locators
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class ApplicationLocatorService extends LocatorServiceAbstract {

	/**
	 *	@const string REGEX_APPLICATION_RESOURCES Application regex resources.
	 */
	const REGEX_APPLICATION_RESOURCES = '/(action|constraint|command|controller|model|view)/i';

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

		if(preg_match(self::REGEX_APPLICATION_RESOURCES, $filePath, $match) !== false) {

			$className = substr($filePath, 3);

			$context = strtolower(array_pop($match));

			$className = preg_replace("/({$context})/i", '$1s', $className, 1);

			$includePath = "{$this->includePath}/{$className}.php";

			return \Brewery\path($includePath, true);

		}

		return false;

	}

	/**
	 *	import
	 *
	 *	Abstract method MUST call {@see \Brewery\Services\LocatorServiceAbstract::resolveFilePath} and SHOULD validate file integrity.
	 *
	 *	@param string $classPath Class name to import.
	 *
	 *	@throws \Brewery\Services\Exceptions\AutoloaderServiceException
	 *
	 *	@return void
	 */
	public function import($classPath) {

		$includePath = $this->resolveFilePath(\Brewery\clean($classPath, NAMESPACE_SEPARATOR));

		if(file_exists($includePath) === false) {

			throw new AutoloaderServiceException(
				"Could not include file.", "File '{$includePath}' does not exist.",
				AutoloaderServiceException::UNEXPECTED_RESULT, __METHOD__
			);

		}

		require_once $includePath;

	}

}