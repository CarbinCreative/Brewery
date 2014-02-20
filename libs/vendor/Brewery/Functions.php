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

/* @namespace Brewery */
namespace Brewery;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	clean
 *
 *	Cleans input string from multiple occurances of input character.
 *
 *	@param string $string Uncleansed input string.
 *	@param string $character Character to use as delimiter.
 *
 *	@return string
 */
function clean($string, $character) {

	return preg_replace("#{$character}+#", $character, trim($string, $character));

}

/**
 *	path
 *
 *	Returns a clean and fully resolved path, either relative or absolute.
 *
 *	@param string $unresolvedPath Unresolved path string.
 *	@param bool $isAbsolute If set to true, output path is absolute.
 *
 *	@return string
 */
function path($unresolvedPath, $isAbsolute = false) {

	$delimiter = '/';

	$path = clean($unresolvedPath, $delimiter);

	$path = str_replace($delimiter, DIRECTORY_SEPARATOR, $path);

	if(substr($unresolvedPath, -1) === $delimiter) {

		$path .= DIRECTORY_SEPARATOR;

	}

	if($isAbsolute === true) {

		$path = BREWERY_ROOT_PATH . $path;

	}

	return $path;

}

/**
 *	import
 *
 *	Imports resources in libs/vendor based on namespace.
 *
 *	@param string $namespace Resource namespace.
 *
 *	@return void
 */
function import($namespace) {

	$path = clean(str_replace(NAMESPACE_SEPARATOR, '/', $namespace), '/');

	$path = path("libs/vendor/{$path}.php", true);

	if(file_exists($path) === true) {

		require_once $path;

	}

}

/**
 *	environment
 *
 *	Imports environment resource if it exists.
 *
 *	@param string $environmentResource Environment resource to load.
 *	@param bool $returnPath Return only path.
 *
 *	@return void
 */
function environment($environmentResource, $returnPath = false) {

	if(strpos($environmentResource, '.') < 0) {

		$environmentResource .= '.php';

	}

	$unresolvedPath = implode('/', [
		'/app/environment/',
		ucfirst(strtolower(BREWERY_ENVIRONMENT)),
		"/{$environmentResource}"
	]);

	$environmentPath = path($unresolvedPath, true);

	if(file_exists($environmentPath) === true) {

		if($returnPath === true) {

			return $environmentPath;

		}

		require_once $environmentPath;

	}

}

/**
 *	asset
 *
 *	Returns clean assets path, either public or private assets path.
 *
 *	@param string $asset Asset to load.
 *
 *	@return string
 */
function asset($asset) {

	$patternImageAsset = '/(\.(jpe?g|png|gif|svgz?)$)/';

	$patternStylesheetAsset = '/(\.((c|sc|le)ss)$)/';

	$patternScriptAsset = '/(\.(js|coffee))$)/';

	if(preg_match($patternImageAsset, $asset) !== false) {

		$assetPath = path("public/assets/img/" . $asset);

	} else if(preg_match($patternStylesheetAsset, $asset) !== false) {

		$assetPath = path("public/assets/css/" . $asset);

	} else if(preg_match($patternScriptAsset, $asset) !== false) {

		$assetPath = path("public/assets/js/" . $asset);

	} else {

		$assetPath = path("public/assets" , $asset);

	}

	return $assetPath;

}


/**
 *	dump
 *
 *	Wraps a var_dump within <pre>-elements.
 *
 *	@return string
 */
function dump() {

	echo '<pre style="font: 14px monaco, monospace;">';

	$args = func_get_args();

	if(count($args) === 1 && is_string($args[0]) === true) {

		echo $args[0] . "\n";

	} else {

		$callback = (end($args) === 'var_dump') ? 'var_dump' : 'print_r';

		if(count($args) === 1) {

			$arguments = $args[0];

		} else {

			$arguments = array_slice($args, 0, count($args) - 1);

		}

		if((end($args) !== 'var_dump' || end($args) !== 'print_r') && count($args) > 1) {

			$arguments[] = end($args);

		}

		if(is_array($arguments) === false) {

			$arguments = [$arguments];

		}

		if($callback === 'print_r') {

			$arguments = [$arguments, false];

		}

		return call_user_func_array($callback, $arguments);

	}

	echo '</pre>';

}

/**
 *	slug
 *
 *	Returns an URI friendly "slug" from a string.
 *
 *	@param string $string Unresolved string.
 *	@param array $replace Replacement characters.
 *	@param string $delimiter Slug delimiter.
 *
 *	@return string
 */
function slug($string, Array $replace = null, $delimiter = '-') {

	if(is_array($replace) === true) {

		$string = str_replace($replace, ' ', $string);

	}

	$slug = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
	$slug = preg_replace("%[^-/+|\w ]%", '', $slug);
	$slug = strtolower(trim($slug, '-'));
	$slug = preg_replace("/[_|+ -]+/", $delimiter, $slug);
	$slug = preg_replace('/(\-\/)/', '', $slug);
	$slug = preg_replace('/(\/\-)/', '/', $slug);

	return $slug;

}

/**
 *	url
 *
 *	Returns full URL, excluding fragment and credentials as these are not supported by PHP.
 *
 *	@return string
 */
function url() {

	$ssl = (empty($_SERVER['HTTPS']) === true) ? '' : (strtolower($_SERVER['HTTPS']) === 'on') ? 's' : '';

	$protocol = strtolower($_SERVER['SERVER_PROTOCOL']);
	$protocol = substr($protocol, 0, strpos($protocol, '/')) . $ssl;

	$port = (intval($_SERVER['SERVER_PORT']) === 80) ? '' : (':' . $_SERVER['SERVER_PORT']);

	$url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];

	return $url;

}

/**
 *	uri
 *
 *	Either returns request URI if no argument is provided or returns a valid URI string.
 *
 *	@param string $uri Unresolved URI.
 *
 *	@return string
 */
function uri($uri = null) {

	if(is_null($uri) === true) {

		$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
		$scriptName = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));

		$segments = array_diff_assoc($requestUri, $scriptName);
		$segments = array_filter($segments);

		if(empty($segments) === true) {

			return '/';

		}

		$uriPath = implode('/', $segments);

		$uriPath = parse_url($uriPath, PHP_URL_PATH);

		return $uriPath;

	}

	return slug(clean(clean($uri, ' '), '/'));

}

/**
 *	segment
 *
 *	Returns request URI segment, if it exists.
 *
 *	@param int $index Segment index.
 *
 *	@return string
 */
function segment($index) {

	$segments = explode('/', uri());

	if($index <= 0) {

		$index = 1;

	}

	if($index > 0 && $index <= count($segments)) {

		return $segments[$index];

	}

	return null;

}

/**
 *	httpStatus
 *
 *	Sets HTTP response status
 *
 *	@return void
 */
function httpStatus($httpStatusCode = 200) {

	$brewery = \Brewery::getInstance();

	$brewery->httpClient->setStatusCode($httpStatusCode);

}

/**
 *	httpHeader
 *
 *	Sets HTTP header.
 *
 *	@param string $header Header name.
 *	@param string $data Header value.
 *
 *	@return void
 */
function httpHeader($header, $data) {

	$brewery = \Brewery::getInstance();

	$brewery->httpClient->setHeader($header, $data);

}

/**
 *	httpHeaders
 *
 *	Sets HTTP headers.
 *
 *	@param array $headers
 *
 *	@return void
 */
function httpHeaders(Array $headers = []) {

	foreach($headers as $header => $data) {

		httpHeader($header, $data);

	}

}