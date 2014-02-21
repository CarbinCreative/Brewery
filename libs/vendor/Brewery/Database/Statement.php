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

/* @namespace Database */
namespace Brewery\Database;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Statement
 *
 *	Database statement class, extends <a href="http://php.net/manual/en/class.pdostatement.php">PDOStatement</a>.
 *
 *	@vendor Brewery
 *	@package Database
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Statement extends \PDOStatement {

	/**
	 *	@var \Brewery\Database\Connection $connection Database connection object (PDO object).
	 */
	protected $connection;

	/**
	 *	@var string $fetchClass Statement fetch class.
	 */
	private $fetchClass;

	/**
	 *	Constructor
	 *
	 *	Sets fetch class for SQL statement object.
	 *
	 *	@return void
	 */
	protected function __construct(Connection $connection, $fetchClass = null) {

		$this->connection = $connection;

		if(is_null($fetchClass) === false) {

			$this->fetchClass = $fetchClass;

		} else {

			$this->fetchClass = 'stdClass';

		}

	}

	/**
	 *	fetch
	 *
	 *	Sets fetch mode if custom fetch class is present, and then returns fetched data.
	 *
	 *	@param int $fetchMode PDO fetch mode.
	 *	@param int $cursorOrientation PDO fetch mode cursor orientation.
	 *	@param int $cursorOffset Cursor offset.
	 *
	 *	@return object|array
	 */
	public function fetch($fetchMode = \PDO::FETCH_CLASS, $cursorOrientation = \PDO::FETCH_ORI_NEXT, $cursorOffset = 0) {

		if($fetchMode === \PDO::FETCH_CLASS) {

			parent::setFetchMode($fetchMode, $this->fetchClass, [$this->connection, $this]);

		}

		return parent::fetch($fetchMode, $cursorOrientation, $cursorOffset);

	}

	/**
	 *	fetchAll
	 *
	 *	Sets fetch mode if custom fetch class is present, and then returns fetched data.
	 *
	 *	@param int $fetchMode PDO fetch mode.
	 *	@param mixed $fetchArgument PDO fetch argument.
	 *	@param array $arguments Class arguments if second argument is class name.
	 *
	 *	@return object|array
	 */
	public function fetchAll($fetchMode = \PDO::FETCH_CLASS, $fetchArgument = \PDO::FETCH_CLASS, $arguments = null) {

		// Set fetch mode
		if($fetchMode === \PDO::FETCH_CLASS) {

			parent::setFetchMode($fetchMode, $this->fetchClass, [$this->connection, $this]);

		}

		// Set fetch class
		if($fetchArgument === \PDO::FETCH_CLASS) {

			$fetchArgument = $this->fetchClass;

		}

		// Fetch all
		return parent::fetchAll($fetchMode, $fetchArgument, [$this->connection, $this]);

	}

	/**
	 *	fetchLarge
	 *
	 *	Memory usage efficient fetch method on large data resultsets, since <a href="http://php.net/manual/en/pdostatement.fetchall.php">PDOStatement::fetchAll</a> may be highly memory inefficient.
	 *
	 *	@return array
	 */
	public function fetchLarge() {

		$resultset = [];

		do {

			if(is_a($row, 'Brewery\Database\Result') === true) {

				$resultset[] = $row;

			}

		} while($row = $this->fetch());

		return $resultset;

	}

}