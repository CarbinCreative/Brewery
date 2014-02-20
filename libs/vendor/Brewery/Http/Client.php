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

/* @namespace Http */
namespace Brewery\Http;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Client
 *
 *	Client used to interact with HTTP parameters, headers and status codes. All valid HTTP status codes, as well as a few personal favorites from {@link https://github.com/joho/7XX-rfc}.
 *
 *	@vendor Brewery
 *	@package Http
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Client {

	/**
	 *	@const string HTTP_1_0 HTTP version 1.0.
	 */
	const HTTP_1_0 = 'HTTP/1.0';

	/**
	 *	@const string HTTP_1_1 HTTP version 1.1.
	 */
	const HTTP_1_1 = 'HTTP/1.1';

	/**
	 *	@var string $httpProtocol HTTP protocol version.
	 */
	protected $httpProtocol = self::HTTP_1_1;

	/**
	 *	@var int $httpStatusCode HTTP status code {@see \Nimbl\HTTP\Client::$httpStatusCodes}.
	 */
	protected $httpStatusCode;

	/**
	 *	@var string $httpStatusType HTTP status type {@see \Nimbl\HTTP\Client::$httpStatusTypes}.
	 */
	protected $httpStatusType;

	/**
	 *	@var array $httpStatusCodes HTTP status codes and names.
	 */
	protected $httpStatusCodes = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => "I'm A Teapot",
		429 => 'Too Many Requests',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		509 => 'Bandwidth Limit Exceeded',
		// @developers
		701 => 'Meh',
		719 => 'I Am Not A Teapot',
		732 => 'Fucking Unic💩de',
		740 => 'Computer Says No',
		748 => 'Confounded By Ponies',
		749 => 'Reserved For Chuck Norris',
		763 => 'Under-Caffeinated',
		764 => 'Over-Caffeinated',
		793 => 'Zombie Apocalypse'
	];

	/**
	 *	@var array $httpStatusTypes HTTP status code types.
	 */
	protected $httpStatusTypes = [
		1 => 'Informational',
		2 => 'Success',
		3 => 'Redirect',
		4 => 'Client Error',
		5 => 'Server Error',
		7 => 'Developer Error'
	];

	/**
	 *	@var array $requestMethods
	 */
	protected $requestMethods = [
		'HEAD',
		'OPTIONS',
		'GET',
		'POST',
		'PUT',
		'PATCH',
		'DELETE',
		'TRACE',
		'CONNECT'
	];

	/**
	 *	@var string $requestMethod
	 */
	protected $requestMethod;

	/**
	 *	@var array $httpHeaders Registered HTTP headers.
	 */
	protected $httpHeaders = [];

	/**
	 *	@var array $requestData Request data.
	 */
	protected $requestData = [];

	/**
	 *	prepare
	 *
	 *	Prepares cleans and merges request data.
	 *
	 *	@return void
	 */
	public function prepare() {

		$this->setMethod();

		$requestMethods = array_merge($this->requestMethods, ['RAW']);

		foreach($requestMethods as $requestMethod) {

			if(array_key_exists($requestMethod, $this->requestData) === false) {

				$this->requestData[$requestMethod] = [];

			}

			switch($requestMethod) {
				case 'RAW' :

					$this->requestData[$requestMethod] = array_merge($this->requestData[$requestMethod], $_REQUEST);

					break;
				case 'GET' :

					$this->requestData[$requestMethod] = array_merge($this->requestData[$requestMethod], $_GET);

					break;
				case 'POST' :

					$this->requestData[$requestMethod] = array_merge($this->requestData[$requestMethod], $_POST);

					break;
				default :

					parse_str(file_get_contents('php://input'), $requestData);
					$this->requestData[$requestMethod] = array_merge($this->requestData[$requestMethod], $requestData);

				break;
			}

		}

		$this->requestData = $this->cleanParameters($this->requestData);

	}

	/**
	 *	setProtocol
	 *
	 *	Sets HTTP protocol.
	 *
	 *	@param string $protocol HTTP protocol.
	 *
	 *	@return bool
	 */
	public function setProtocol($protocol) {

		$reflection = new \ReflectionClass($this);

		if(in_array($protocol, $reflection->getConstants()) === true) {

			$this->httpProtocol = $protocol;

			return true;

		}

		return false;

	}

	/**
	 *	getProtocol
	 *
	 *	Returns registered HTTP protocol.
	 *
	 *	@return string
	 */
	public function getProtocol() {

		return $this->httpProtocol;

	}

	/**
	 *	setStatusCode
	 *
	 *	Sets current HTTP status code.
	 *
	 *	@param int $statusCode
	 *
	 *	@return void
	 */
	public function setStatusCode($statusCode) {

		if(in_array($statusCode, array_keys($this->httpStatusCodes)) === true) {

			$this->httpStatusCode = $statusCode;

			return true;

		}

		return false;

	}

	/**
	 *	getStatusCode
	 *
	 *	Returns the HTTP status code registered to this instance.
	 *
	 *	@return int
	 */
	public function getStatusCode() {

		return $this->httpStatusCode;

	}

	/**
	 *	statusMessage
	 *
	 *	Returns status message to corresponding status code.
	 *
	 *	@return string|null
	 */
	public function statusMessage() {

		if(array_key_exists($this->getStatusCode(), $this->httpStatusCodes) === true) {

			return $this->httpStatusCodes[$this->httpStatusCode];

		}

		return null;

	}

	/**
	 *	statusType
	 *
	 *	Returns status type.
	 *
	 *	@return string|null
	 */
	public function statusType() {

		$statusCode = floor($this->httpStatusCode / 100);

		if(array_key_exists($statusCode, $this->httpStatusTypes) === true) {

			return $this->httpStatusTypes[$statusCode];

		}

		return null;

	}

	/**
	 *	status
	 *
	 *	Returns the full HTTP status including protocol, status code and status message.
	 *
	 *	@return string
	 */
	public function status() {

		return sprintf('%s %s %s', $this->httpProtocol, $this->getStatusCode(), $this->statusMessage());

	}

	/**
	 *	setMethod
	 *
	 *	Sets current request method.
	 *
	 *	@param string $requestMethod HTTP request method.
	 *
	 *	@return bool
	 */
	public function setMethod($requestMethod = null) {

		$requestMethod = strtoupper($requestMethod);

		if(is_null($requestMethod) === false && in_array($requestMethod, $this->requestMethods) === true) {

			$this->requestMethod = $requestMethod;

			return true;

		}

		$this->requestMethod = $_SERVER['REQUEST_METHOD'];

		return false;

	}

	/**
	 *	setMethod
	 *
	 *	Returns current request method.
	 *
	 *	@return string
	 */
	public function getMethod() {

		return $this->requestMethod;

	}

	/**
	 *	requestMethods
	 *
	 *	Returns HTTP request methods.
	 *
	 *	@return void
	 */
	public function requestMethods() {

		return $this->requestMethods;

	}

	/**
	 *	isInformational
	 *
	 *	Returns true if HTTP status code is 1xx {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.1}.
	 *
	 *	@return bool
	 */
	public function isInformational() {

		return (floor($this->httpStatusCode / 100) === 1);

	}

	/**
	 *	isSuccess
	 *
	 *	Returns true if HTTP status code is 2xx {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.2}.
	 *
	 *	@return bool
	 */
	public function isSuccess() {

		return (floor($this->httpStatusCode / 100) === 2);

	}

	/**
	 *	isRedirect
	 *
	 *	Returns true if HTTP status code is 3xx {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3}.
	 *
	 *	@return bool
	 */
	public function isRedirect() {

		return (floor($this->httpStatusCode / 100) === 3);

	}

	/**
	 *	isClientError
	 *
	 *	Returns true if HTTP status code is 4xx {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.4}.
	 *
	 *	@return bool
	 */
	public function isClientError() {

		return (floor($this->httpStatusCode / 100) === 4);

	}

	/**
	 *	isServerError
	 *
	 *	Returns true if HTTP status code is 5xx {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.5}.
	 *
	 *	@return bool
	 */
	public function isServerError() {

		return (floor($this->httpStatusCode / 100) === 5);

	}

	/**
	 *	isDeveloperError
	 *
	 *	Returns true if HTTP status code is 7xx {@link https://github.com/joho/7XX-rfc}.
	 *
	 *	@return bool
	 */
	public function isDeveloperError() {

		return (floor($this->httpStatusCode / 100) === 7);

	}

	/**
	 *	isError
	 *
	 *	Returns true if HTTP status is 4xx, 5xx or 7xx.
	 *
	 *	@return bool
	 */
	public function isError() {

		return ($this->isClientError() === true || $this->isServerError() === true || $this->isDeveloperError() === true);

	}

	/**
	 *	isHead
	 *
	 *	Returns true if HTTP request method is HEAD.
	 *
	 *	@return bool
	 */
	public function isHead() {

		return ($this->getMethod() === 'HEAD');

	}

	/**
	 *	isOptions
	 *
	 *	Returns true if HTTP request method is OPTIONS.
	 *
	 *	@return bool
	 */
	public function isOptions() {

		return ($this->getMethod() === 'OPTIONS');

	}

	/**
	 *	isGet
	 *
	 *	Returns true if HTTP request method is GET.
	 *
	 *	@return bool
	 */
	public function isGet() {

		return ($this->getMethod() === 'GET');

	}

	/**
	 *	isPost
	 *
	 *	Returns true if HTTP request method is POST.
	 *
	 *	@return bool
	 */
	public function isPost() {

		return ($this->getMethod() === 'POST');

	}

	/**
	 *	isPut
	 *
	 *	Returns true if HTTP request method is PUT.
	 *
	 *	@return bool
	 */
	public function isPut() {

		return ($this->getMethod() === 'PUT');

	}

	/**
	 *	isPatch
	 *
	 *	Returns true if HTTP request method is PATCH.
	 *
	 *	@return bool
	 */
	public function isPatch() {

		return ($this->getMethod() === 'PATCH');

	}

	/**
	 *	isDelete
	 *
	 *	Returns true if HTTP request method is DELETE.
	 *
	 *	@return bool
	 */
	public function isDelete() {

		return ($this->getMethod() === 'DELETE');

	}

	/**
	 *	isTrace
	 *
	 *	Returns true if HTTP request method is TRACE.
	 *
	 *	@return bool
	 */
	public function isTrace() {

		return ($this->getMethod() === 'TRACE');

	}

	/**
	 *	isConnect
	 *
	 *	Returns true if HTTP request method is CONNECT.
	 *
	 *	@return bool
	 */
	public function isConnect() {

		return ($this->getMethod() === 'CONNECT');

	}

	/**
	 *	isAjax
	 *
	 *	Returns true if X-Requested-With header is sent.
	 *
	 *	@return bool
	 */
	public function isAjax() {

		return (empty($_SERVER['HTTP_X_REQUESTED_WITH']) === false && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

	}

	/**
	 *	isPjax
	 *
	 *	Returns true if X-PJAX header is sent.
	 *
	 *	@return bool
	 */
	public function isPjax() {

		return (empty($_SERVER['HTTP_X_PJAX']) === false);

	}

	/**
	 *	isAsync
	 *
	 *	Returns true if request is asynchronous.
	 *
	 *	@return bool
	 */
	public function isAsync() {

		return ($this->isAjax() === true || $this->isPjax() === true);

	}

	/**
	 *	isTransfer
	 *
	 *	Returns true if request is file transfer.
	 *
	 *	@return bool
	 */
	public function isTransfer() {

		if(($this->isPost() === true || $this->isPut() === true) && isset($_FILES) === true && is_array($_FILES) === true) {

			return true;

		}

		return false;

	}

	/**
	 *	cleanParameter
	 *
	 *	Returns "clean" parameter value.
	 *
	 *	@param string $requestParameter Request parameter.
	 *
	 *	@return string
	 */
	public function cleanParameter($requestParameter) {

		if(is_array($requestParameter) === true) {

			return $this->cleanParameters($requestParameter);

		}

		return addslashes(strip_tags($requestParameter));

	}

	/**
	 *	cleanParameters
	 *
	 *	Returns "clean" parameter array.
	 *
	 *	@param array $requestParameters Request parameters.
	 *
	 *	@return array
	 */
	public function cleanParameters(Array $requestParameters) {

		return array_map(array($this, 'cleanParameter'), $requestParameters);

	}

	/**
	 *	getParameter
	 *
	 *	Returns request parameter, if it exists.
	 *
	 *	@param string $requestParameter Request parameter name.
	 *	@param mixed $parameterData Request parameter data.
	 *	@param string $requestMethod Request method.
	 *
	 *	@return bool
	 */
	public function setParameter($requestParameter, $parameterData, $requestMethod = null) {

		$requestMethod = strtoupper((is_string($requestMethod) === true) ? $requestMethod : $this->getMethod());

		if(in_array($requestMethod, $this->requestMethods) === false) {

			return false;

		}

		$this->requestData[$requestMethod][$requestParameter] = $this->cleanParameter($parameterData);

		return true;

	}

	/**
	 *	getParameter
	 *
	 *	Returns request parameter, if it exists.
	 *
	 *	@param string $requestParameter Request parameter name.
	 *	@param string $requestMethod Request method.
	 *
	 *	@return mixed
	 */
	public function getParameter($requestParameter, $requestMethod = null) {

		$requestMethod = strtoupper((is_string($requestMethod) === true) ? $requestMethod : $this->getMethod());

		if(in_array($requestMethod, $this->requestMethods) === false) {

			return null;

		}

		return $this->requestData[$requestMethod][$requestParameter];

	}

	/**
	 *	setHeader
	 *
	 *	Sets request header.
	 *
	 *	@param string $header Request header.
	 *	@param string $data Request header data.
	 *
	 *	@return bool
	 */
	public function setHeader($header, $data) {

		if(preg_match("/^[a-zA-Z0-9-]+$/", $header)) {

			$this->httpHeaders[$header] = $data;

			return true;

		}

		return false;

	}

	/**
	 *	getHeader
	 *
	 *	Returns a HTTP header (if registered).
	 *
	 *	@param string $header
	 *
	 *	@return string|bool
	 */
	public function getHeader($header) {

		if(array_key_exists($header, $this->httpHeaders) === true) {

			return $this->httpHeaders[$header];

		}

		return false;

	}

	/**
	 *	setHeaders
	 *
	 *	Sets headers from an associative array.
	 *
	 *	@param array $headers Array containing headers.
	 *
	 *	@return void
	 */
	public function setHeaders(Array $headers) {

		if(is_array($headers) === true) {

			foreach($headers as $header => $data) {

				$this->setHeader($header, $data);

			}

		}

	}

	/**
	 *	getHeaders
	 *
	 *	Returns all registered headers.
	 *
	 *	@param array
	 */
	public function getHeaders() {

		return $this->httpHeaders;

	}

	/**
	 *	outputHeaders
	 *
	 *	Returns an output of all registered headers as a string.
	 *
	 *	@param bool $outputStatus If set to true, HTTP status is prepended.
	 *
	 *	@return string
	 */
	public function outputHeaders($outputStatus = true) {

		$crlf = "\r\n";

		$output = null;

		if($outputStatus === true) {

			$output .= $this->status() . $crlf;

		}

		foreach($this->getHeaders() as $header => $data) {

			$output .= "{$header}: {$data}{$crlf}";

		}

		return $output;

	}

	/**
	 *	sendHeaders
	 *
	 *	Sends registered output headers.
	 *
	 *	@return void
	 */
	public function sendHeaders() {

		header($this->status(), true, $this->getStatusCode());

		foreach($this->getHeaders() as $header => $data) {

			header("{$header}: {$data}");

		}

	}

}