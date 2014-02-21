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
 *	Connection
 *
 *	Database connection class, extends <a href="http://php.net/manual/en/class.pdo.php">PDOStatement</a>. Makes database connections with PDO easier to understand and manage.
 *
 *	@vendor Brewery
 *	@package Database
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Connection extends \PDO {

	/**
	 *	@var string $dsn Database source name.
	 */
	private $dsn;

	/**
	 *	@var array $drivers Array containing DSN pattern strings for different database types.
	 */
	protected $drivers = [
		'dblib' => 'host=%s:%d;dbname=%s',
		'firebird' => 'DataSource=%s;Port=%d;Database=%s;',
		'mysql' => 'host=%s;port=%d;dbname=%s;',
		'pgsql' => 'host=%s port=%d dbname=%s'
	];

	/**
	 *	@var array $driverOptions Additional database driver options.
	 */
	protected $driverOptions;

	/**
	 *	@var bool $isConnected Boolean whether a database connection exists, or not.
	 */
	protected $isConnected = false;

	/**
	 *	@var bool $inTransaction Boolean whether an transaction is in effect.
	 */
	protected $inTransaction = false;

	/**
	 *	Constructor
	 *
	 *	Validates database driver and sets DSN.
	 *
	 *	@param string $driver Database driver.
	 *	@param string $database Database name.
	 *	@param string $host Database host name.
	 *	@param int $port Database port.
	 *
	 *	@throws \Brewery\Database\Exceptions\DatabaseException
	 *
	 *	@return void
	 */
	public function __construct($driver, $database, $host = null, $port = null) {

		// Throw exception if database driver does not exist
		if(array_key_exists(strtolower($driver), $this->drivers) === false) {

			throw new Exceptions\DatabaseException(
				'Could not establish database connection.', "Database driver \"{$driver}\" does not exist.",
				Exceptions\DatabaseException::INVALID_ARGUMENT, __METHOD__
			);

		}

		// Throw exception if driver 'dblib' does not have a port specified
		if(strtolower($driver) === 'dblib' && is_int($port) === false) {

			throw new Exceptions\DatabaseException(
				'Could not establish database connection.', "Database driver \"{$driver}\" requires that a port is specified.",
				Exceptions\DatabaseException::INVALID_ARGUMENT, __METHOD__
			);

		}

		// Set default hostname
		$host = (is_null($host) === true) ? 'localhost' : $host;

		// Set default port
		$port = (is_null($port) === true) ? null : $port;

		// Set database source name
		$this->setDatabaseSourceName(strtolower($driver), $database, $host, $port);

		// Set default database driver options
		$this->driverOptions = [
			\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_CLASS,
			\PDO::ATTR_STATEMENT_CLASS => [
				'\Brewery\Database\Statement',
				[$this, '\Brewery\Database\Result']
			]
		];

	}

	/**
	 *	setDataSourceName
	 *
	 *	Sets database source name based on database driver option.
	 *
	 *	@param string $driver Database driver name.
	 *	@param string $database Database name.
	 *	@param string $host Database host name.
	 *	@param int $port Database port.
	 *
	 *	@return void
	 */
	private function setDatabaseSourceName($driver, $database, $host = null, $port = null) {

		// Set default hostname
		$host = (is_null($host) === true) ? 'localhost' : $host;

		// Set default port
		$port = (is_null($port) === true) ? false : $port;

		// Get DSN string
		$dsn = $this->drivers[$driver];

		// Create DSN without port
		if($port === false) {

			$dsn = preg_replace('/(port=%d[;| ])/i', '', $dsn);

			$this->dsn = "{$driver}:" . sprintf($dsn, $host, $database);

		} else {

			// Create DSN with port
			$this->dsn = "{$driver}:" . sprintf($dsn, $host, $port, $database);

		}

	}

	/**
	 *	setDatabaseDriverOptions
	 *
	 *	Sets database driver specific options.
	 *
	 *	@param array $driverOptions Array containing key value pairs of driver specific options.
	 *
	 *	@throws \Brewery\Database\Exceptions\DatabaseException
	 *
	 *	@return void
	 */
	public function setDatabaseDriverOptions(Array $driverOptions) {

		if($this->isConnected() === true) {

			throw new Exceptions\DatabaseException(
				'Could not set database driver options.',
				'A database connection with driver options has already been established.',
				__METHOD__, Exceptions\DatabaseException::BAD_CALL_EXCEPTION
			);

		}

		$this->driverOptions = array_merge($this->driverOptions, $driverOptions);

	}

	/**
	 *	connect
	 *
	 *	Connects to specified database.
	 *
	 *	@param string $username Database username.
	 *	@param string $password Database password.
	 *
	 *	@return void
	 */
	public function connect($username, $password) {

		// Invoke class parent
		parent::__construct($this->dsn, $username, $password, $this->driverOptions);

		$this->isConnected = true;

	}

	/**
	 *	isConnected
	 *
	 *	Returns boolean whether a database connection exists, or not.
	 *
	 *	@return bool
	 */
	public function isConnected() {

		return $this->isConnected;

	}

	/**
	 *	inTransaction
	 *
	 *	Boolean whether an transaction is in effect.
	 *
	 *	@return bool
	 */
	public function inTransaction() {

		return $this->inTransaction;

	}

	/**
	 *	beginTransaction
	 *
	 *	Begins transaction mode and sets internal transaction flag, if no active transaction exists.
	 *
	 *	@return bool
	 */
	public function beginTransaction() {

		if($this->inTransaction() === false) {

			$this->inTransaction = parent::beginTransaction();

			return $this->inTransaction;

		}

		return false;

	}

	/**
	 *	commit
	 *
	 *	Commits current transaction.
	 *
	 *	@return void
	 */
	public function commit() {

		if($this->inTransaction() === true) {

			parent::commit();

			$this->inTransaction = false;

		}

	}

	/**
	 *	rollback
	 *
	 *	Reverts current transaction.
	 *
	 *	@return void
	 */
	public function rollback() {

		if($this->inTransaction() === true) {

			parent::rollBack();

			$this->inTransaction = false;

		}

	}

}