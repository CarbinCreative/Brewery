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

/* @namespace Container */
namespace Brewery\Container;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	FragmentCoordinates
 *
 *	Collection fragment coordinates object.
 *
 *	@vendor Brewery
 *	@package Container
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class FragmentCoordinates {

	/**
	 *	@var int $length Collection length.
	 */
	protected $length;

	/**
	 *	@var int $pointer Collection pointer.
	 */
	protected $pointer;

	/**
	 *	@var int $limit Data segment limit.
	 */
	protected $limit;

	/**
	 *	@var int $offset Collection offset.
	 */
	protected $offset;

	/**
	 *	@var int $segments Number of segments.
	 */
	protected $segments;

	/**
	 *	Constructor
	 *
	 *	Sets length and limit and points to first segment in collection.
	 *
	 *	@param int $length Fragment length.
	 *	@param int $limit Fragment limit.
	 *
	 *	@return void
	 */
	public function __construct($length, $limit) {

		$this->setLength($length);

		$this->setLimit($limit);

		$this->point(0);

	}

	/**
	 *	setLength
	 *
	 *	Sets collection length.
	 *
	 *	@param int $length Fragment length.
	 *
	 *	@return void
	 */
	public function setLength($length) {

		$this->length = $length;

	}

	/**
	 *	getLength
	 *
	 *	Returns collection length.
	 *
	 *	@return int
	 */
	public function getLength() {

		return $this->length;

	}

	/**
	 *	setPointer
	 *
	 *	Sets collection pointer.
	 *
	 *	@param int $pointer Fragment pointer.
	 *
	 *	@return void
	 */
	public function setPointer($pointer) {

		$this->pointer = $pointer;

	}

	/**
	 *	getPointer
	 *
	 *	Returns collection pointer.
	 *
	 *	@return void
	 */
	public function getPointer() {

		return $this->pointer;

	}

	/**
	 *	setLimit
	 *
	 *	Sets collection limit.
	 *
	 *	@param int $limt Fragment limit.
	 *
	 *	@return void
	 */
	public function setLimit($limit) {

		$this->limit = $limit;

	}

	/**
	 *	getLimit
	 *
	 *	Returns collection limit.
	 *
	 *	@return int
	 */
	public function getLimit() {

		return $this->limit;

	}

	/**
	 *	setOffset
	 *
	 *	Sets collection offset.
	 *
	 *	@param int $offset Fragment offset.
	 *
	 *	@return void
	 */
	public function setOffset($offset) {

		$this->offset = $offset;

	}

	/**
	 *	getOffset
	 *
	 *	Returns collection offset.
	 *
	 *	@return int
	 */
	public function getOffset() {

		return $this->offset;

	}

	/**
	 *	setSegments
	 *
	 *	Sets collection segments.
	 *
	 *	@return void
	 */
	public function setSegments($segments) {

		$this->segments = $segments;

	}

	/**
	 *	getSegments
	 *
	 *	Returns collection segments.
	 *
	 *	@return int
	 */
	public function getSegments() {

		return $this->segments;

	}

	/**
	 *	point
	 *
	 *	Points to a segment in a data collection.
	 *
	 *	@param int $pointer Fragment pointer offset.
	 *
	 *	@return void
	 */
	public function point($pointer = null) {

		if(is_null($pointer) === true) {

			$pointer = $this->getPointer();

		}

		$this->setSegments(intval(ceil($this->getLength() / $this->getLimit())));

		$this->setPointer(min(max($pointer, 1), $this->getLength()));

		$this->setOffset(($this->getPointer() - 1) * $this->getLimit());

	}

	/**
	 *	prev
	 *
	 *	Sets pointer to previous segment.
	 *
	 *	@return void
	 */
	public function prev() {

		$this->point($this->getPointer() - 1);

	}

	/**
	 *	next
	 *
	 *	Sets pointer to next segment.
	 *
	 *	@return void
	 */
	public function next() {

		$this->point($this->getPointer() + 1);

	}

	/**
	 *	getCoordinates
	 *
	 *	Returns an object containing segment location in collection.
	 *
	 *	@return \Brewery\Container\Coordinates
	 */
	public function getCoordinates() {

		return new Coordinates([
			'length' => $this->getLength(),
			'segments' => $this->getSegments(),
			'limit' => $this->getLimit(),
			'offset' => $this->getOffset(),
			'pointer' => $this->getPointer()
		]);

	}

}