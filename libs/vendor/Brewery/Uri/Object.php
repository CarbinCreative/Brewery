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
 *	Object
 *
 *	URI object representation.
 *
 *	@vendor Brewery
 *	@package Uri
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Object {

	/* @coalesce */
	use ParserTrait;

	/**
	 *	@const string URI_QUERY_STRING_AFFIX Query string affix.
	 */
	const URI_QUERY_STRING_AFFIX = '?';

	/**
	 *	@const string URI_FRAGMENT_AFFIX Fragment affix, pound sign.
	 */
	const URI_FRAGMENT_AFFIX = '#';

	/**
	 *	@const string URI_CREDENTIALS_AFFIX Credentials affix, at sign.
	 */
	const URI_CREDENTIALS_AFFIX = '@';

	/**
	 *	@const string REGEX_HEX_CHARS Hex characters.
	 */
	const REGEX_HEX_CHARS = 'a-f0-9';

	/**
	 *	@const string REGEX_ALNUM_CHARS Alphanumeric characters.
	 */
	const REGEX_ALNUM_CHARS = 'a-z0-9';

	/**
	 *	@const string REGEX_URI_CHARS Valid URI characters.
	 */
	const REGEX_URI_CHARS = "a-z0-9-._~!$&%'()*+,;=";

	/**
	 *	@const string REGEX_URI_SCHEMA_CHARS Valid URI schema characters.
	 */
	const REGEX_URI_SCHEMA_CHARS = 'a-z0-9+-._';

	/**
	 *	@const string REGEX_URI_UNWISE_CHARS Unwise, but not invalid URI characters.
	 */
	const REGEX_URI_UNWISE_CHARS = '{}|\\\\^`';

	/**
	 *	@var string $scheme URI scheme.
	 */
	protected $scheme;

	/**
	 *	@var string $host URI host name.
	 */
	protected $host = null;

	/**
	 *	@var string $port URI port.
	 */
	protected $port = null;

	/**
	 *	@var string $requestPath Request URI path.
	 */
	protected $requestPath = null;

	/**
	 *	@var string $rawRequestPath Raw request URI path.
	 */
	protected $rawRequestPath = null;

	/**
	 *	@var string $queryString Request query string.
	 */
	protected $queryString = null;

	/**
	 *	@var string $fragment URI fragment.
	 */
	protected $fragment = null;

	/**
	 *	@var object $credentials Credentials associated with current URI object.
	 */
	protected $credentials = null;

	/**
	 *	@var string $scriptFilePath Script file path, if present.
	 */
	protected $scriptFilePath = null;

	/**
	 *	@var string $scriptFileName Script file name, if present.
	 */
	protected $scriptFileName = null;

	/**
	 *	@var bool $isLocalOrigin Defines whether current URI object has a local or remote origin.
	 */
	protected $isLocalOrigin = false;

	/**
	 *	@var bool $isRewrite Is this URI rewritten.
	 */
	protected $isRewrite = false;

	/**
	 *	Constructor
	 *
	 *	Validates wheter or not URI representation has a local origin and whether or not it has been rewritten.
	 *
	 *	@param bool $isLocalOrigin Specified whether or not URI is of local origin.
	 *	@param bool $allowUnwise Specifies whether or not to allow unwise URI chars.
	 *
	 *	@return void
	 */
	public function __construct($isLocalOrigin = null, $allowUnwise = null) {

		if(is_bool($isLocalOrigin) === true && $isLocalOrigin === true) {

			$this->isLocalOrigin = true;

		}

		if(is_bool($allowUnwise) === true && $allowUnwise === true) {

			$this->allowUnwise = true;

		}

		if($isLocalOrigin === true) {

			$this->isRewrite = $this->validateRewriteCapabilities();

		}

		$this->setup($allowUnwise);

	}

	/**
	 *	validateRewriteCapabilities
	 *
	 *	Checks server configuration for URI rewriting modules.
	 *
	 *	@return bool
	 */
	protected function validateRewriteCapabilities() {

		$hasRewriteCapabilities = false;

		/**
		 *	Test case: Apache
		 */
		if(function_exists('apache_get_modules') === true) {

			$hasRewriteCapabilities = in_array('mod_rewrite', apache_get_modules());

		} else {

			$hasRewriteCapabilities = (strtolower(getenv('HTTP_MOD_REWRITE')) === 'on') ? true : false;

		}

		/**
		 *	Test case: CGI
		 */
		if($hasRewriteCapabilities === false) {

			$output = shell_exec('/usr/local/apache/bin/apachectl -l');

			if(is_null($output) === false && is_string($output) === true) {

				$hasRewriteCapabilities = (strpos($output, 'mod_rewrite') !== false);

			}

		}

		/**
		 *	Test case: IIS
		 */
		if(array_key_exists('HTTP_X_ORIGINAL_URL', $_SERVER) === true) {

			$hasRewriteCapabilities = true;

		}

		/**
		 *	Test case: SetEnv in .htaccess
		 *	Expects "SetEnv HTTP_MOD_REWRITE On" in .htaccess
		 */
		if(array_key_exists('HTTP_MOD_REWRITE', $_SERVER) === true) {

			$hasRewriteCapabilities = true;

		}

		return $hasRewriteCapabilities;

	}

	/**
	 *	setScheme
	 *
	 *	Sets URI object scheme.
	 *
	 *	@param string $scheme URI scheme.
	 *
	 *	@return void
	 */
	public function setScheme($scheme) {

		if($this->isValidScheme("{$scheme}://") === false) {

			throw new Exceptions\UriException(
				'Could not set scheme for current URI.', 'URI scheme is invalid or malformed.',
				Exceptions\UriException::MALFORMED_ARGUMENT, __METHOD__
			);

		}

		$this->scheme = $scheme;

	}

	/**
	 *	getScheme
	 *
	 *	Returns URI object scheme.
	 *
	 *	@return string
	 */
	public function getScheme() {

		return $this->scheme;

	}

	/**
	 *	setHost
	 *
	 *	Sets URI object host.
	 *
	 *	@param string $host URI host name.
	 *
	 *	@throws \Brewery\URI\Exceptions\UriException
	 *
	 *	@return void
	 */
	public function setHost($host) {

		if($this->isValidHost($host) === false) {

			throw new Exceptions\UriException(
				'Could not set host name for current URI.', 'URI host name is invalid or malformed.',
				Exceptions\UriException::MALFORMED_ARGUMENT, __METHOD__
			);

		}

		$this->host = $host;

	}

	/**
	 *	getHost
	 *
	 *	Returns URI object host.
	 *
	 *	@return string
	 */
	public function getHost() {

		return $this->host;

	}

	/**
	 *	setPort
	 *
	 *	Sets URI object host port.
	 *
	 *	@param integer $port URI host port.
	 *
	 *	@return void
	 */
	public function setPort($port = null) {

		$this->port = $port;

	}

	/**
	 *	getPort
	 *
	 *	Returns URI object port.
	 *
	 *	@return integer
	 */
	public function getPort() {

		return $this->port;

	}

	/**
	 *	setRequestPath
	 *
	 *	Sets URI object request path.
	 *
	 *	@param string $requestPath URI request path, not to be mistaken for script path, {@see \Brewery\URI\Object::$scriptFilePath}.
	 *
	 *	@throws \Brewery\URI\Exceptions\UriException
	 *
	 *	@return void
	 */
	public function setRequestPath($requestPath = null) {

		if(is_null($requestPath) === true || empty($requestPath) === true) {

			return;

		}

		if($this->isValidRequestPath($requestPath) === false) {

			throw new Exceptions\UriException(
				'Could not set request path for current URI.', 'URI request path is invalid or malformed.',
				Exceptions\UriException::MALFORMED_ARGUMENT, __METHOD__
			);

		}

		$requestPath = str_ireplace($this->getScriptName(), '', $requestPath);

		$this->requestPath = $requestPath;

	}

	/**
	 *	getRequestPath
	 *
	 *	Returns URI object request path.
	 *
	 *	@return string
	 */
	public function getRequestPath() {

		if($this->requestPath !== $this->scriptFileName) {

			return trim($this->requestPath, '/');

		}

		return null;

	}

	/**
	 *	setRawRequestPath
	 *
	 *	Sets raw URI object request path.
	 *
	 *	@param string $rawRequestPath URI request path, not to be mistaken for script path, {@see \Brewery\URI\Object::$scriptFilePath}.
	 *
	 *	@return void
	 */
	public function setRawRequestPath($rawRequestPath = null) {

		if(is_null($rawRequestPath) === true || empty($rawRequestPath) === true) {

			return;

		}

		$this->rawRequestPath = $rawRequestPath;

	}

	/**
	 *	getRawRequestPath
	 *
	 *	Returns raw URI object request path.
	 *
	 *	@return string
	 */
	public function getRawRequestPath() {

		return $this->rawRequestPath;

	}

	/**
	 *	setQueryString
	 *
	 *	Sets URI object scheme.
	 *
	 *	@param string $queryString URI query string.
	 *
	 *	@throws \Brewery\URI\Exceptions\UriException
	 *
	 *	@return void
	 */
	public function setQueryString($queryString = null) {

		if(is_null($queryString) === true) {

			return;

		}

		if($this->isValidQueryString($queryString) === false) {

			throw new Exceptions\UriException(
				'Could not set query string for current URI.', 'URI query is invalid or malformed.',
				Exceptions\UriException::MALFORMED_ARGUMENT, __METHOD__
			);

		}

		$this->queryString = $queryString;

	}

	/**
	 *	getQueryString
	 *
	 *	Returns URI object query string.
	 *
	 *	@return string
	 */
	public function getQueryString() {

		return $this->queryString;

	}

	/**
	 *	setFragment
	 *
	 *	Sets URI object fragment.
	 *
	 *	@param string $fragment URI Fragment link.
	 *
	 *	@return void
	 */
	public function setFragment($fragment = null) {

		$this->fragment = $fragment;

	}

	/**
	 *	getFragment
	 *
	 *	Returns URI object fragment.
	 *
	 *	@return string
	 */
	public function getFragment() {

		return $this->fragment;

	}

	/**
	 *	setCredentials
	 *
	 *	Sets URI object fragment.
	 *
	 *	@param string $fragment
	 *
	 *	@return void
	 */
	public function setCredentials($username, $password) {

		$this->credentials = (object) [
			'username' => $username,
			'password' => $password
		];

	}

	/**
	 *	getFragment
	 *
	 *	Returns URI object fragment.
	 *
	 *	@return string
	 */
	public function getCredentials() {

		return $this->credentials;

	}

	/**
	 *	isRewrite
	 *
	 *	Returns whether or not this URI (probably) has been rewritten or not.
	 */
	public function isRewrite() {

		return $this->isRewrite;

	}

	/**
	 *	setScriptPath
	 *
	 *	Sets URI object script file path to origin script.
	 *
	 *	@param string $scriptFilePath Script file path.
	 *
	 *	@return void
	 */
	public function setScriptPath($scriptFilePath = null) {

		$this->scriptFilePath = trim($scriptFilePath, '/');

	}

	/**
	 *	getScriptPath
	 *
	 *	Returns URI object script file path.
	 *
	 *	@return string
	 */
	public function getScriptPath() {

		return $this->scriptFilePath;

	}

	/**
	 *	setScriptName
	 *
	 *	Sets URI object script file name to origin script.
	 *
	 *	@param string $scriptFileName Script file name.
	 *
	 *	@return void
	 */
	public function setScriptName($scriptFileName = null) {

		$this->scriptFileName = $scriptFileName;

	}

	/**
	 *	getScriptName
	 *
	 *	Returns URI object script file name.
	 *
	 *	@return string
	 */
	public function getScriptName() {

		return $this->scriptFileName;

	}

	/**
	 *	getSegment
	 *
	 *	Returns request path segment.
	 *
	 *	@param int $offset Offset of URI request path segment.
	 *
	 *	@return string|null
	 */
	public function getSegment($offset) {

		$segments = explode('/', $this->getRequestPath());

		if($offset > 0 && $offset <= count($segments)) {

			return $segments[$offset - 1];

		}

		return null;

	}

	/**
	 *	getBaseURI
	 *
	 *	Returns current base URI, has an option to expose associated credentials, if present.
	 *
	 *	@param bool $exposeCredentials If set to true, associated credentials will be prepended to base URI string.
	 *
	 *	@return string
	 */
	public function getBaseURI($exposeCredentials = null) {

		$baseUri = $this->getScheme();
		$baseUri .= '://';

		if($exposeCredentials === true) {

			$credentials = $this->getCredentials();

			if(is_string($credentials->username) === true && is_string($credentials->password) === true) {

				$baseUri .= "{$credentials->username}:{$credentials->password}";
				$baseUri .= self::URI_CREDENTIALS_AFFIX;

			}

		}

		$baseUri .= $this->getHost() . '/';

		$baseUri = preg_replace('%([^:])([/]{2,})%', '\\1/', $baseUri);

		return $baseUri;

	}

	/**
	 *	getOriginURI
	 *
	 *	Returns base URI with script path appended.
	 *
	 *	@param bool $exposeCredentials If set to true, associated credentials will be prepended to base URI string.
	 *
	 *	@return string
	 */
	public function getOriginURI($exposeCredentials = null) {

		$base_uri = $this->getBaseURI($exposeCredentials);

		if(is_string($this->getScriptPath()) === true && $this->getScriptPath() !== '') {

			$base_uri .= $this->getScriptPath() . '/';

		}

		$base_uri = preg_replace('%([^:])([/]{2,})%', '\\1/', $base_uri);

		return $base_uri;

	}

	/**
	 *	getRequestURI
	 *
	 *	Returns full request URI, has an option to append URI fragment link.
	 *
	 *	@param bool $appendQueryString If set to true, URI query string is appended to request URI.
	 *	@param bool $appendFragment If set to true, URI fragment link is appended to request URI.
	 *	@param bool $exposeCredentials If set to true, associated credentials will be prepended to base URI string.
	 *
	 *	@return string
	 */
	public function getRequestURI($appendQueryString = null, $appendFragment = null, $exposeCredentials = null) {

		$requestUri = $this->getBaseURI($exposeCredentials);

		if(preg_match('/([a-z0-9]+\.[a-z]{3,4})/i', $this->getRawRequestPath()) === 1) {

			$requestUri .= $this->getScriptName();

		}

		if(is_string($this->getRequestPath()) === true) {

			$requestUri .= '/' . trim(implode('', ['/', $this->getRequestPath(), '/']), '/') . '/';

		}

		if($appendQueryString === true && is_string($this->getQueryString()) === true) {

			$requestUri .= self::URI_QUERY_STRING_AFFIX . $this->getQueryString();

		}

		if($appendFragment === true && is_string($this->getFragment()) === true) {

			$requestUri .= self::URI_FRAGMENT_AFFIX . $this->getFragment();

		}

		$requestUri = preg_replace('%([^:])([/]{2,})%', '\\1/', $requestUri);

		return $requestUri;

	}

}