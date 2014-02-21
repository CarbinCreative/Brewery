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

/* @namespace Application */
namespace Brewery\Application;

/* @imports */
use Brewery\Http\ResourceAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	ControllerAbstract
 *
 *	Abstract used to create route controllers.
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
	 *	Invokes {@see ResourceAbstract::__construct} and sets up default view object.
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
	 *	Creates controller view, if not context is set Pistacio ({@see \Brewery\View\Adapters\PistachioAdapter}) is used..
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
	 *	Function used to render view from registered view adapter.
	 *
	 *	@param array $variables
	 *	@param string $action
	 *
	 *	@return string
	 */
	public function render(Array $variables = [], $action = null) {

		$brewery = \Brewery::getInstance();

		if($action === null) {

			$action = $brewery->currentRoute->action;

		}

		$classNames = [
			'brewery',
			$brewery->currentRoute->resource,
			$brewery->currentRoute->action,
			$action,
			'y' . date('Y'),
			'm' . date('m'),
			'd' . date('d')
		];

		$classNames = array_unique($classNames);

		$variables['bodyClassNames'] = implode(' ', $classNames);

		return $this->view->render($action, $variables);

	}

}