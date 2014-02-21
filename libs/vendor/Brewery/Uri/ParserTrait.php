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

/* @namespace Uri */
namespace Brewery\Uri;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ParserTrait
 *
 *	URI parser trait.
 *
 *	@vendor Brewery
 *	@package Uri
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
trait ParserTrait {

	/**
	 *	@var bool $allow_undiwse Specified whether to allow unwise URI chars.
	 */
	protected $allowUnwise = false;

	/**
	 *	@var string $uriRegex URI regex.
	 */
	protected $uriRegex;

	/**
	 *	@var string $uriSchemeRegex URI schema regex.
	 */
	protected $uriSchemeRegex;

	/**
	 *	@var string $uriHostRegex URI host regex.
	 */
	protected $uriHostRegex;

	/**
	 *	@var string $uriRequestPathRegex URI request path regex.
	 */
	protected $uriRequestPathRegex;

	/**
	 *	@var string $uriQueryStringRegex URI query string regex.
	 */
	protected $uriQueryStringRegex;

	/**
	 *	setup
	 *
	 *	Compiles regular expressions used to validate URI segments.
	 *
	 *	@param bool $allowUnwise Specifies whether or not to allow unwise URI chars.
	 *
	 *	@return void
	 */
	protected function setup($allowUnwise = null) {

		if($allowUnwise === true) {

			$this->allowUnwise = true;

		}

		$this->compileRegularExpressions();

	}

	/**
	 *	compileRegularExpressions
	 *
	 *	Compiles all regular expressions used for parsing of URIs.
	 *
	 *	@return void
	 */
	protected function compileRegularExpressions() {

		$this->uriRegex = '`';
		$this->uriRegex .= "(?:([" . self::REGEX_URI_SCHEMA_CHARS . "]+)://)?";
		$this->uriRegex .= "(?:";
		$this->uriRegex .= "(?:((?:[" . self::REGEX_URI_CHARS . ":]|%[" . self::REGEX_HEX_CHARS . "]{2})*)@)?";
		$this->uriRegex .= "(?:\[((?:[" . self::REGEX_ALNUM_CHARS . ":])*)\])?";
		$this->uriRegex .= "((?:[" . self::REGEX_URI_CHARS . "]|%[" . self::REGEX_HEX_CHARS . "]{2})*)";
		$this->uriRegex .= "(?::(\d*))?";
		$this->uriRegex .= "(/(?:[" . self::REGEX_URI_CHARS . ":@/]|%[" . self::REGEX_HEX_CHARS . "]{2})*)?";
		$this->uriRegex .= "|(/?";
		$this->uriRegex .= "(?:[" . self::REGEX_URI_CHARS . ":@]|%[" . self::REGEX_HEX_CHARS . "]{2})+";
		$this->uriRegex .= "(?:[" . self::REGEX_URI_CHARS . ":@\/]|%[" . self::REGEX_HEX_CHARS . "]{2})*";
		$this->uriRegex .= ")?)";
		$this->uriRegex .= "(?:\?((?:[" . self::REGEX_URI_CHARS . ":\/?@]|%[" . self::REGEX_HEX_CHARS . "]{2})*))?";
		$this->uriRegex .= "(?:#((?:[" . self::REGEX_URI_CHARS . ":\/?@]|%[" . self::REGEX_HEX_CHARS . "]{2})*))?";
		$this->uriRegex .= '`i';

		// Set URI schema regex
		$this->uriSchemeRegex = '/^[a-z][' . self::REGEX_ALNUM_CHARS . ']+\:\/\/$/i';

		// Set URI host regex
		$this->uriHostRegex = '/^[' . self::REGEX_URI_SCHEMA_CHARS . ']+$/i';

		// Get regex for unwise chars if option is set to true
		$uriRegexUnwise = ($this->allowUnwise === true) ? self::REGEX_URI_UNWISE_CHARS : '';

		// Set URI request path regex
		$this->uriRequestPathRegex = '/^[' . self::REGEX_URI_CHARS . $uriRegexUnwise . '\/#@\:\/]+$/i';

		// Set URI query string regex
		$this->uriQueryStringRegex = '/^[' . self::REGEX_URI_CHARS . $uriRegexUnwise . '#@\:\/]+$/i';

	}

	/**
	 *	isValidScheme
	 *
	 *	Verifies validity of input URI schema.
	 *
	 *	@param string $schema URI schema, including trailing semi-colon and double forward slash.
	 *
	 *	@return bool
	 */
	public function isValidScheme($scheme) {

		return (preg_match($this->uriSchemeRegex, $scheme) === 1) ? true : false;

	}

	/**
	 *	isValidHost
	 *
	 *	Verifies validity of input URI host.
	 *
	 *	@param string $host URI host.
	 *
	 *	@return bool
	 */
	public function isValidHost($host) {

		return (preg_match($this->uriHostRegex, $host) === 1) ? true : false;

	}

	/**
	 *	isValidRequestPath
	 *
	 *	Verifies validity of input URI request path.
	 *
	 *	@param string $requestPath URI request path.
	 *
	 *	@return bool
	 */
	public function isValidRequestPath($requestPath) {

		return (preg_match($this->uriRequestPathRegex, $requestPath) === 1) ? true : false;

	}

	/**
	 *	isValidQueryString
	 *
	 *	Verifies validity of input URI query string.
	 *
	 *	@param string $queryString URI query string.
	 *
	 *	@return bool
	 */
	public function isValidQueryString($queryString) {

		return (preg_match($this->uriQueryStringRegex, $queryString) === 1) ? true : false;

	}

	/**
	 *	parse
	 *
	 *	Parses input URI and returns a new {@see \Brewery\Uri\Object} object populated with parsed data.
	 *
	 *	@param string $uri URI to parse.
	 *	@param bool $isLocalOrigin Specified whether or not URI is of local origin.
	 *	@param string $populateSelf If set to true, parsed data is set to current object.
	 *
	 *	@return \Brewery\Uri\Object
	 */
	public function parse($uri, $isLocalOrigin = null) {

		$uriObject = new Object($isLocalOrigin, $this->allowUnwise);

		$requestPath = null;

		preg_match($this->uriRegex, $uri, $match);

		switch(count($match)) {

			case 10 :

				$uriObject->setFragment($match[9]);

			case 9 :

				$uriObject->setQueryString($match[8]);

			case 8 :

				$requestPath = $match[7];

			case 7 :

				$requestPath = $match[6] . $requestPath;

			case 6 :

				$uriObject->setPort(intval($match[5]));

			case 5 :

				$uriObject->setHost(($match[3]) ? "[" . $match[3] . "]" : $match[4]);

			case 4 :

				$credentials = explode(':', $match[2]);
				$username = (isset($credentials[0]) === true) ? $credentials[0] : null;
				$password = (isset($credentials[1]) === true) ? $credentials[1] : null;

				if(is_null($username) === false && is_null($password) === false) {

					$uriObject->setCredentials($username, $password);

				}

			case 3 :

				$uriObject->setScheme($match[1]);

		}

		$uriObject->setRequestPath(trim($requestPath, '/'));

		if($isLocalOrigin === true) {

			$uriObject->setScriptPath(trim(dirname($_SERVER['SCRIPT_NAME']), '/'));

			$uriObject->setScriptName(trim(basename($_SERVER['SCRIPT_NAME']), '/'));

		}

		$path = preg_split('/([a-z0-9]+\.[a-z]{3,4})/i', trim($requestPath, '/'), null, PREG_SPLIT_DELIM_CAPTURE);

		switch(count($path)) {

			case 3 :

				if(isset($path[0]) === true) {

					$uriObject->setScriptPath(trim($path[0], '/'));

				}

				if(isset($path[1]) === true) {

					$uriObject->setScriptName(trim($path[1], '/'));

				}

				if(isset($path[2]) === true) {

					$uriObject->setRequestPath(trim($path[2], '/'));

				}

			break;
			case 1 :

				$uriObject->setRequestPath(trim($path[0], '/'));

			break;
		}

		if(($uriObject->getRequestPath() === $uriObject->getScriptPath()) ||($uriObject->getRequestPath() === $uriObject->getScriptPath() . '/' .  $uriObject->getScriptName())) {

			$uriObject->setRequestPath('/');

		}

		return $uriObject;

	}

	/**
	 *	setUri
	 *
	 *	Parses and sets URI object properties.
	 *
	 *	@param string $unparsedUri Unparsed URI.
	 *
	 *	@return void
	 */
	public function setUri($unparsedUri) {

		$uriObject = $this->parse($unparsedUri);

		$this->setScheme($uriObject->getScheme());

		$this->setHost($uriObject->getHost());

		$this->setPort($uriObject->getPort());

		$this->setRequestPath($uriObject->getRequestPath());

		$this->setQueryString($uriObject->getQueryString());

		$this->setScriptPath($uriObject->getScriptPath());

		$this->setScriptName($uriObject->getScriptName());

	}

}