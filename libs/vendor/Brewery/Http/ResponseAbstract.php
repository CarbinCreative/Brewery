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

/* @namespace Http */
namespace Brewery\Http;

/* @imports */
use Brewery\Application\Response as ResponseInterface;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ResponseAbstract
 *
 *	HTTP response abstract.
 *
 *	@vendor Brewery
 *	@package Http
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class ResponseAbstract implements ResponseInterface {

	/**
	 *	@var string $data Network response data.
	 */
	protected $data;

	/**
	 *	@var array $info Network response info object.
	 */
	protected $info = [];

	/**
	 *	interpret
	 *
	 *	Must intepret response data and info recieved from network request object.
	 *
	 *	@param mixed $data Response data to intepret.
	 *
	 *	@return void
	 */
	abstract protected function interpret($data);

	/**
	 *	setData
	 *
	 *	Sets response data.
	 *
	 *	@param string $responseData Response data.
	 *
	 *	@return void
	 */
	public function setData($responseData) {

		$this->data = $responseData;

	}

	/**
	 *	getData
	 *
	 *	Returns response data.
	 *
	 *	@return string
	 */
	public function getData() {

		return $this->data;

	}

	/**
	 *	setInfo
	 *
	 *	Sets response info.
	 *
	 *	@param array $responseInfo
	 *
	 *	@return void
	 */
	public function setInfo(Array $responseInfo) {

		$this->info = $responseInfo;

	}

	/**
	 *	getInfo
	 *
	 *	Returns response info.
	 *
	 *	@return array
	 */
	public function getInfo() {

		return $this->info;

	}

}