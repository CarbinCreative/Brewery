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
 *	@license http://opensource.org/licenses/MIT MIT
 */



/**
 *	@const string BREWERY_ROOT_PATH Brewery root path.
 */
define('BREWERY_ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

try {

	// Require Brewery bootstrap
	require_once implode(DIRECTORY_SEPARATOR, [rtrim(BREWERY_ROOT_PATH, DIRECTORY_SEPARATOR), 'libs', 'vendor', 'Brewery', 'Bootstrap.php']);

	// Load environment configuration
	\Brewery\environment('config');

	$brewery = \Brewery::getInstance();

	$router = new \Brewery\Routing\Router();

	$output = $router->delegate();

	$brewery->httpClient->sendHeaders();

	echo $output;

} catch (\Brewery\Exceptions\ExceptionAbstract $exception) {

	$brewery = \Brewery::getInstance();

	$brewery->httpClient->sendHeaders();

	\Brewery\dump($exception);

}
?>