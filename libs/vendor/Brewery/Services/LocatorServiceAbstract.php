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

/* @namespace Services */
namespace Brewery\Services;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	LocatorServiceAbstract
 *
 *	Abstract used to create locator service objects.
 *
 *	@vendor Brewery
 *	@package Services
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class LocatorServiceAbstract implements ServiceInterface {

	/**
	 *	@var string $includePath Resolved locator include path.
	 */
	protected $includePath;

	/**
	 *	@var string $fileExtension File extension to associated to resolved path.
	 */
	protected $fileExtension;

	/**
	 *	Constructor
	 *
	 *	Resolves input include path and file extension.
	 *
	 *	@param string $unresolvedIncludePath Unresolved include path.
	 *	@param string $fileExtension File extension.
	 *
	 *	@return void
	 */
	public function __construct($unresolvedIncludePath, $fileExtension) {

		$this->resolveIncludePath($unresolvedIncludePath);

		$this->resolveFileExtension($fileExtension);

	}

	/**
	 *	resolveIncludePath
	 *
	 *	Converts forward slashes into valid directory separators.
	 *
	 *	@param string $unresolvedIncludePath Unresolved include path.
	 *
	 *	@throws \Brewery\Services\Exceptions\ServiceException
	 *
	 *	@return void
	 */
	protected function resolveIncludePath($unresolvedIncludePath) {

		$resolvedIncludePath = \Brewery\path($unresolvedIncludePath);

		if(substr($resolvedIncludePath, -1) !== DIRECTORY_SEPARATOR) {

			$resolvedIncludePath = $resolvedIncludePath . DIRECTORY_SEPARATOR;

		}

		if(is_dir($resolvedIncludePath) === false) {

			throw new Exceptions\ServiceException(
				'Could not resolve include path for ' . __CLASS__ . '.',
				'Include path is not a valid directory path.',
				Exceptions\ServiceException::INVALID_ARGUMENT, __METHOD__
			);

		}

		$this->includePath = $resolvedIncludePath;

	}

	/**
	 *	resolveFileExtension
	 *
	 *	Validates file extension and sets input file extension to class property.
	 *
	 *	@param string $fileExtension File extension.
	 *
	 *	@throws \Brewery\Services\Exceptions\ServiceException
	 *
	 *	@return void
	 */
	protected function resolveFileExtension($fileExtension) {

		if(preg_match('/^\.[a-z0-9\.\_\-]+$/', $fileExtension) === 0) {

			throw new Exceptions\ServiceException(
				'Could not set file extension for ' . __CLASS__ . '.',
				'File extension is malformed, only accepts alpha numeric characters, underscores, dashes and punctuation.',
				Exceptions\ServiceException::MALFORMED_ARGUMENT, __METHOD__
			);

		}

		$this->fileExtension = $fileExtension;

	}

	/**
	 *	resolveFilePath
	 *
	 *	Abstract method MUST resolve file path, and return resolved path as a string.
	 *
	 *	@param string $filePath File path name to resolve path for.
	 *
	 *	@return string
	 */
	abstract protected function resolveFilePath($filePath);

	/**
	 *	import
	 *
	 *	Abstract method MUST call {@see \Brewery\Services\LocatorServiceAbstract::resolveFilePath} and SHOULD validate file integrity.
	 *
	 *	@param string $classPath Class name to import.
	 *
	 *	@return void
	 */
	abstract public function import($classPath);

}