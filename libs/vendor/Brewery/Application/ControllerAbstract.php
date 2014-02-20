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

/* @namespace Application */
namespace Brewery\Application;

/* @aliases */
use Brewery\Http\ResourceAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ControllerAbstract
 *
 *	Abstract used to create application controllers.
 *
 *	@vendor Brewery
 *	@package Application
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
abstract class ControllerAbstract extends ResourceAbstract implements ControllerInterface {

	/**
	 *	@var Brewery\View\Adapters\PistachioAdapter $view
	 */
	protected $view;

	/**
	 *	Constructor
	 *
	 *	Ivokes parent constructor.
	 *
	 *	@return void
	 */
	public function __construct() {

		$brewery = \Brewery::getInstance();

		parent::__construct($brewery->httpClient);

		$this->makeView();

	}

	/**
	 *	makeView
	 *
	 *	Creates controller view.
	 *
	 *	@param string $viewContext
	 *
	 *	@return void
	 */
	public function makeView($viewContext = null) {

		$brewery = \Brewery::getInstance();

		$currentRoute = $brewery->currentRoute;

		if(is_null($viewContext) === true) {

			$viewContext = 'Pistachio';

		}

		$viewClassName = sprintf('\Brewery\View\Adapters\%sAdapter', $viewContext);

		$this->view = call_user_func_array([new \ReflectionClass($viewClassName), 'newInstance'], []);

		$this->view->setIncludePath($currentRoute->includePath);

		$blueprintIncludePath = BREWERY_BLUEPRINTS_PATH . $viewContext . '.php';

		if(file_exists($blueprintIncludePath) === true) {

			$blueprintClassName = sprintf('\app\View\Blueprints\%s', $viewContext);

			$blueprint = call_user_func_array([new \ReflectionClass($blueprintClassName), 'newInstance'], []);

			$this->view->setBlueprint($blueprint);

		}

	}

	/**
	 *	render
	 *
	 *	Renders view.
	 *
	 *	@param array $variables
	 *
	 *	@return string
	 */
	public function render(Array $variables = []) {

		$brewery = \Brewery::getInstance();

		return $this->view->render($brewery->currentRoute->action, $variables);

	}

}