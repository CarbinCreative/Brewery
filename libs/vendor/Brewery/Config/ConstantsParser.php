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

/* @namespace Config */
namespace Brewery\Config;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ConstantsParser
 *
 *	Parses and defines configuration constants.
 *
 *	@vendor Brewery
 *	@package Config
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class ConstantsParser {

	/**
	 *	@const string CONSTANT_PREFIX Constant prefix.
	 */
	const CONSTANT_PREFIX = 'BREWERY_';

	/**
	 *	@var \DOMDocument $dom Instance of {@man DOMDocument}.
	 */
	private $dom;

	/**
	 *	@var \DOMXPath $xpath Instance of {@man DOMXPath}.
	 */
	private $xpath;

	/**
	 *	@var array $constants Parsed constants from configuration file.
	 */
	private $constants = [];

	/**
	 *	Constructor
	 *
	 *	Prepares required objects and invokes {@see \Brewery\Config\ConstantsParser::setup}.
	 *
	 *	@return void
	 */
	public function __construct() {

		$this->dom = new \DOMDocument();
		$this->dom->load(\Brewery\path('config.xml', true));

		$this->xpath = new \DOMXPath($this->dom);
		$this->xpath->registerNamespace('brewery', 'http://brewphp.org/xmlns');

		$this->setup();

	}

	/**
	 *	setup
	 *
	 *	Parses and compiles constants if not already compiled and includes compiled file.
	 *
	 *	@return void
	 */
	private function setup() {

		$this->parse();

		$path = \Brewery\path('app/environment/constants.php', true);

		$environment = $this->constants[self::CONSTANT_PREFIX . 'ENVIRONMENT'];

		if($environment === 'DEVELOPMENT' && file_exists($path) === false) {

			foreach($this->constants as $constantName => $constantValue) {

				define($constantName, $constantValue);

			}

		} else {

			if(file_exists($path) === false) {

				$output = $this->compile();

				$file = fopen($path, 'w');

				fwrite($file, $output);

				fclose($file);

			}

			require_once $path;

		}

	}

	/**
	 *	parse
	 *
	 *	Parses constants from configuration file.
	 *
	 *	@return void
	 */
	private function parse() {

		$nodes = $this->xpath->query("//brewery:constants/brewery:constant");

		foreach($nodes as $node) {

			$constantName = self::CONSTANT_PREFIX . strtoupper($node->getAttribute('name'));

			$constantType = 'string';

			$constantValue = trim($node->nodeValue);

			if($node->hasAttribute('type') === true) {

				$constantType = strtolower($node->getAttribute('type'));

			}

			switch($constantType) {
				case 'path' :
				case 'r:path' :

					$isAbsolute = ($constantType === 'path');
					$constantValue = \Brewery\path($constantValue, $isAbsolute);

					break;
				case 'bool' :

					$constantValue = (bool) (strtolower($constantValue) === 'true');

					break;
				case 'int' :

					$constantValue = intval($constantValue);

					break;
				case 'float' :

					$constantValue = floatval($constantValue);

				break;
			}

			$this->constants[$constantName] = $constantValue;

		}

	}

	/**
	 *	compile
	 *
	 *	Compiles parsed constants into PHP output.
	 *
	 *	@return string
	 */
	private function compile() {

		$output = "<?php\n";

		foreach($this->constants as $constantName => $constantValue) {

			if(is_bool($constantValue) === true) {

				$constantValue = ($constantValue === true) ? 'true' : 'false';

			} else {

				$constantValue = "'{$constantValue}'";

			}

			$output .= sprintf("define('%s', %s);\n", $constantName, $constantValue);

		}

		return $output;

	}

}