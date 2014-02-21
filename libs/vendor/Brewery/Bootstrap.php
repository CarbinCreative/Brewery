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

/* @namespace Brewery */
namespace Brewery;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	@const string NAMESPACE_SEPARATOR Namespace separator constant.
 */
defined('NAMESPACE_SEPARATOR') || define('NAMESPACE_SEPARATOR', '\\');

/**
 *	@const string NAMESPACE_PCRE Regular expression for namespaces.
 */
define('NAMESPACE_PCRE', '/^[a-z\\\\][a-z0-9\_\\\\]+$/i');



// Load Brewery functions early
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Helpers.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Functions.php';

// Load required libraries
import('Brewery\Generics\SingletonTrait');
import('Brewery\Generics\RegistryTrait');

import('Brewery\Exceptions\ExceptionTrait');
import('Brewery\Exceptions\ExceptionAbstract');
import('Brewery\Exceptions\ErrorException');
import('Brewery\Exceptions\Exception');

// Autoloader services
import('Brewery\Services\Exceptions\AutoloaderServiceException');
import('Brewery\Services\Exceptions\ServiceException');
import('Brewery\Services\ServiceInterface');
import('Brewery\Services\LocatorServiceAbstract');
import('Brewery\Services\AutoloaderService');
import('Brewery\Services\Locators\VendorLocatorService');
import('Brewery\Services\Locators\ApplicationLocatorService');

// Require Brewery core class
import('Brewery\Core\Brewery');

// Import and invoke configuration parsers
import('Brewery\Config\ConstantsParser');

// Parses and creates constant reference
// @see app/Environment/Constants.php
new Config\ConstantsParser();

// Autoloading service
$autoloader = new \Brewery\Services\AutoloaderService();
$autoloader->registerNamespaceLocator('Brewery', new \Brewery\Services\Locators\VendorLocatorService(path('libs/vendor'), '.php'));
$autoloader->registerNamespaceLocator('app', new \Brewery\Services\Locators\ApplicationLocatorService(path('app'), '.php'));
$autoloader->register();

// Invoke required classes
$brewery = \Brewery::getInstance();

$brewery->initialize('\Brewery\Http\Client', 'httpClient');
$brewery->initialize('\Brewery\Uri\Object', 'uriObject');

// Prepare or invoke required library classes
$brewery->httpClient->prepare();