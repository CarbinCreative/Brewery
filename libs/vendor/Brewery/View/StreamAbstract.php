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

/* @namespace View */
namespace Brewery\View;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	StreamAbstract
 *
 *	Abstract used to create stream handlers.
 *
 *	@vendor Brewery
 *	@package View
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class StreamAbstract {

	/**
	 *	@var int $position Stream data position.
	 */
	protected $position = 0;

	/**
	 *	@var string $data Stream data.
	 */
	protected $data;

	/**
	 *	@var mixed $stat Stream data stat.
	 */
	protected $stat;

	/**
	 *	open
	 *
	 *	Pretty alias for {@see \Brewery\View\StreamAbstract::stream_open}.
	 *
	 *	@param string $path Stream path.
	 *	@param string $mode Stream mode.
	 *	@param string $options Stream options.
	 *	@param string $openedPath Stream opened path.
	 *
	 *	@return bool
	 */
	abstract public function open($path, $mode, $options, $openedPath);

	/**
	 *	stream_open
	 *
	 *	Called when a stream is opened.
	 *
	 *	@param string $path Stream path.
	 *	@param string $mode Stream mode.
	 *	@param string $options Stream options.
	 *	@param string $openedPath Stream opened path.
	 *
	 *	@return bool
	 */
	public function stream_open($path, $mode, $options, $openedPath) {

		return $this->open($path, $mode, $options, $openedPath);

	}

	/**
	 *	stream_seek
	 *
	 *	Seeks in stream.
	 *
	 *	@param string $offset Stream offset.
	 *	@param string $mode Stream mode.
	 *
	 *	@return bool
	 */
	public function stream_seek($offset, $mode) {

		switch($mode) {

			case SEEK_SET :

				if($offset < strlen($this->data) && $offset >= 0) {

					$this->position = $offset;

				} else {

					return false;

				}

				return true;

			break;
			case SEEK_CUR :

				if($offset >= 0) {

					$this->position += $offset;

				} else {

					return false;

				}

				return true;

			break;
			case SEEK_END :

				if(strlen($this->data) + $offset >= 0) {

					$this->position = strlen($this->data) + $offset;

				} else {

					return false;

				}

				return true;

			break;
			default :

				return false;

			break;

		}

	}

	/**
	 *	stream_read
	 *
	 *	Reads stream.
	 *
	 *	@param int $count
	 *
	 *	@return string
	 */
	public function stream_read($count) {

		$data = substr($this->data, $this->position, $count);

		$this->position += strlen($data);

		return $data;

	}

	/**
	 *	tell
	 *
	 *	Returns current stream position.
	 *
	 *	@return int
	 */
	public function stream_tell() {

		return $this->position;

	}

	/**
	 *	stream_stat
	 *
	 *	Returns stat.
	 *
	 *	@return array
	 */
	public function stream_stat() {

		return $this->stat;

	}

	/**
	 *	url_stat
	 *
	 *	Returns stat.
	 *
	 *	@return array
	 */
	public function url_stat() {

		return $this->stat;

	}

	/**
	 *	stream_eof
	 *
	 *	Returns boolean whether stream has reach end of file or not.
	 *
	 *	@return bool
	 */
	public function stream_eof() {

		return $this->position >= strlen($this->data);

	}

}