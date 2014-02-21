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

/* @namespace Routing */
namespace Brewery\Routing;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	Router
 *
 *	Class used to delegate routes.
 *
 *	@vendor Brewery
 *	@package Routing
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class Router {

	/**
	 *	@var array $routeMapsWildcards Route map pattern wildcards.
	 */
	protected $routeMapsWildcards = [
		':num' => '(\d+)',
		':word' => '(\w+)',
		':alnum' => '([A-Za-z0-9]+)',
		':uri' => '([A-Za-z\_\-\+\#\?\&\.\@]+)'
	];

	/**
	 *	@var string $defaultController Default controller name.
	 */
	protected $defaultController = 'Application';

	/**
	 *	@var array $parsedRouteMaps Parsed route maps.
	 */
	public $parsedRouteMaps = [];

	/**
	 *	parseDefinedRouteMaps
	 *
	 *	Parses routes.json in current environment.
	 *
	 *	@return boid
	 */
	public function parseDefinedRouteMaps() {

		$routesPath = \Brewery\environment('routes.json', true);

		if(is_string($routesPath) === true) {

			$definedRoutes = json_decode(file_get_contents($routesPath), true);

			$parsedRouteMaps = [];

			foreach($definedRoutes as $routePath => $route) {

				if(is_string($route) === true) {

					$parsedRouteMaps[$routePath] = $this->parseDefinedCallbackRoute($route);

				} else if(is_array($route) === true) {

					$parsedRouteMaps[$routePath] = $this->parseRouteGroup($route);

				}

			}

		}

		$this->parsedRouteMaps = $parsedRouteMaps;

		$this->flattenRoutePatterns();

	}

	/**
	 *	flattenRoutePatterns
	 *
	 *	Flattens parsed route map groups.
	 *
	 *	@return void
	 */
	private function flattenRoutePatterns() {

		$flat = [];

		foreach($this->parsedRouteMaps as $routePath => $route) {

			if(is_array($route) === true) {

				foreach($route as $path => $object) {

					$key = trim("{$routePath}/{$path}", '/');

					$flat[$key] = $object;

				}

			} else {

				$flat[$routePath] = $route;

			}

		}

		$this->parsedRouteMaps = $flat;

	}

	/**
	 *	parseRouteGroup
	 *
	 *	Parses current route group.
	 *
	 *	@param array $routeGroup Route group.
	 *
	 *	@return array
	 */
	private function parseRouteGroup($routeGroup) {

		$component = null;

		$constraints = null;

		foreach($routeGroup as $routePath => $route) {

			if($routePath === 'component') {

				$component = $route;

				continue;

			} else if($routePath === 'constraints') {

				if(is_array($constraints) === true) {

					$route = array_merge($constraints, $route);

				}

				$constraints = $route;

				continue;

			}

			if(is_string($route) === true) {

				$parsedRouteMaps[$routePath] = $this->parseDefinedCallbackRoute($route);

			} else if(is_array($constraints) === true) {

				$parsedRouteMaps[$routePath] = $this->parseDefinedCallbackRoute($route['path']);

				$constraints = array_merge($constraints, $route['constraints']);

			}

			if(is_null($component) === false) {

				$parsedRouteMaps[$routePath]->component = $component;

			}

			if(is_null($constraints) === false) {

				$parsedRouteMaps[$routePath]->constraints = $constraints;

			}

		}

		return $parsedRouteMaps;

	}

	/**
	 *	parseDefinedCallbackRoute
	 *
	 *	Parses input callback, valid syntax is NamedController::method/param1/param2.
	 *
	 *	@param string $callbackPath
	 *
	 *	@throws \Brewery\Routing\Exceptions\RouterException
	 *
	 *	@return array|bool
	 */
	private function parseDefinedCallbackRoute($callbackPath) {

		$delimiter = '/';

		$callbackPath = preg_replace('/\:{2}/', $delimiter, $callbackPath);

		if(strpos($callbackPath, $delimiter) === false) {

			$callbackPath .= '/get';

		}

		if(strpos($callbackPath, $delimiter) > -1) {

			$segments = explode($delimiter, $callbackPath);

			list($controller, $controllerCallback) = $segments;

			$controllerCallbackParameters = array_slice($segments, 2);

			preg_match('/(?P<asyncMethod>(?:[a|p]jax)?)(?P<requestMethod>head|options|get|post|put|patch|delete|trace|connect)(?P<callbackName>(?:[a-z0-9]+)?)/i', $controllerCallback, $matches);

			extract($matches);

			if(isset($requestMethod) === false) {

				throw new Exceptions\RouterException(
					'Could not parse defined callback route.', "Route callback string is invalid.",
					Exceptions\RouterException::INVALID_ARGUMENT, __METHOD__
				);

			}

			$callback = [$asyncMethod];
			$callback[] = ucfirst(strtolower($requestMethod));
			$callback[] = (strlen($callbackName) > 0) ? $callbackName : ucfirst(strtolower($callbackName));

			$callback = array_filter($callback);
			$callbackName = lcfirst(implode('', $callback));

			return (object) [
				'isAsync' => !(is_null($asyncMethod) === false),
				'asyncMethod' => (empty($asyncMethod) === false) ? $asyncMethod : null,
				'allowedRequestMethod' => $requestMethod,
				'component' => null,
				'constraints' => null,
				'action' => str_ireplace('Controller', 'Action', ucfirst($controller)),
				'controller' => ucfirst($controller),
				'callback' => $callbackName,
				'parameters' => $controllerCallbackParameters
			];

		}

		return false;

	}

	/**
	 *	autoDetectRequest
	 *
	 *	Detects route based on URI and HTTP request.
	 *
	 *	@param bool $isComponentRule
	 *
	 *	@return object
	 */
	private function autoDetectRequest($isComponentRule = false) {

		$brewery = \Brewery::getInstance();

		$requestMethod = $brewery->httpClient->getMethod();
		$requestPath = \Brewery\uri();

		$defaultController = $this->defaultController;

		$regexBreakCharacters = '/([.|_|-])/';

		// Set path offset
		$controllerIndex = ($isComponentRule === false) ? 0 : 1;
		$controllerMethodIndex = ($isComponentRule === false) ? 1 : 2;
		$controllerMethodArgumentsIndex = ($isComponentRule === false) ? 2 : 3;

		$segments = array_filter(explode('/', $requestPath));
		$numSegments = count($segments);

		$component = ($isComponentRule === true && isset($segments[0]) === true) ? ucfirst(strtolower($segments[0])) : null;

		// Get controller callbacks
		$controller = (isset($segments[$controllerIndex])) ? ucfirst(strtolower($segments[$controllerIndex])) : $defaultController;
		$controllerCallback = (isset($segments[$controllerMethodIndex])) ? trim($segments[$controllerMethodIndex]) : null;
		$controllerCallbackParameters = array_splice($segments, $controllerMethodArgumentsIndex);

		$routeRequestMethod = strtoupper($controllerCallback);

		if($requestMethod === $routeRequestMethod) {

			$routeRequestMethod = null;

		}

		$asyncMethod = ($brewery->httpClient->isAsync()) ? ($brewery->httpClient->isPjax() ? 'pjax' : 'ajax') : null;

		// Normalize callback
		if(preg_match($regexBreakCharacters, $routeRequestMethod) > 0) {

			$routeRequestMethod = null;

			$controllerCallbackParameters = array_merge([$controllerCallback], $controllerCallbackParameters);

			if(count($controllerCallbackParameters) > 1) {

				$routeRequestMethod = $controllerCallbackParameters[1];

				unset($controllerCallbackParameters[1]);

			}

		}

		$callback = [$asyncMethod];
		$callback[] = ucfirst(strtolower($requestMethod));
		$callback[] = ucfirst(strtolower($routeRequestMethod));

		$callback = array_filter($callback);
		$callbackName = lcfirst(implode('', $callback));

		preg_match($regexBreakCharacters, $controller, $matches);

		if(count($matches) > 0) {

			$controllerCallbackParameters = array_merge($controllerCallbackParameters, [strtolower($controller)]);

			$controller = $defaultController;

		}

		return (object) [
			'isAsync' => (is_null($asyncMethod) === false),
			'asyncMethod' => $asyncMethod,
			'allowedRequestMethod' => strtolower($requestMethod),
			'component' => $component,
			'constraints' => null,
			'action' => "{$controller}Action",
			'controller' => "{$controller}Controller",
			'callback' => $callbackName,
			'parameters' => $controllerCallbackParameters
		];

	}

	/**
	 *	resolve
	 *
	 *	Resolves route object.
	 *
	 *	@reutrn array
	 */
	private function resolve() {

		$this->parseDefinedRouteMaps();

		$requestPath = \Brewery\uri();

		$resolvedRequest = null;

		$hasRouteMatch = false;

		if(count($this->parsedRouteMaps) > 0) {

			foreach($this->parsedRouteMaps as $routePath => $routeMap) {

				$hasRouteMatch = false;

				$routeMap = (object) $routeMap;

				$wildcards = array_keys($this->routeMapsWildcards);
				$wildcardsRegex = array_values($this->routeMapsWildcards);

				$routePath = str_ireplace($wildcards, $wildcardsRegex, $routePath);

				if(preg_match('#^' . $routePath . '$#', $requestPath, $matches) === 1) {

					$parameters = array_slice($matches, 1);

					$routeMap->parameters = $parameters;

					$resolvedRequest = $routeMap;

					$hasRouteMatch = true;

					break;

				}

			}

		}

		if($hasRouteMatch === false) {

			$resolvedRequest = $this->autoDetectRequest();

		}

		return $resolvedRequest;

	}

	/**
	 *	setCurrentRouteObject
	 *
	 *	Set curret route object.
	 *
	 *	@param \Brewery\Routing\Route $route
	 *
	 *	@return void
	 */
	protected function setCurrentRouteObject(Route $route) {

		$brewery = \Brewery::getInstance();

		$controller = $route->controller()->getName();
		$callback = $route->controller()->getCallback();

		$resource = strtolower(str_ireplace('Controller', '', $controller));
		$action = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1.', $callback));


		$includePath = BREWERY_VIEWS_PATH;

		if(defined('BREWERY_COMPONENT_PATH') === true) {

			$includePath = BREWERY_COMPONENT_PATH . 'views';

		}

		$includePath = implode(DIRECTORY_SEPARATOR, [rtrim($includePath, DIRECTORY_SEPARATOR), $resource]) . DIRECTORY_SEPARATOR;

		if(strpos($action, '.') !== false) {

			$action = trim(str_replace(['get', 'post', 'put', 'delete'], '', $_action), '.');

		}

		$currentRoute = (object) [
			'includePath' => $includePath,
			'controller' => $controller,
			'callback' => $callback,
			'resource' => $resource,
			'action' => $action
		];

		$brewery->currentRequest = $route;

		$brewery->currentRoute = $currentRoute;

	}

	/**
	 *	delegate
	 *
	 *	Sets up current matched route and invokes required resources.
	 *
	 *	@throws \Brewery\Routing\Exceptions\RouterException
	 *
	 *	@return string
	 */
	public function delegate() {

		$brewery = \Brewery::getInstance();

		$resolvedRequest = $this->resolve();

		$route = new Route();

		// Register route component
		if(is_null($resolvedRequest->component) === false) {

			$component = new Route\Component($resolvedRequest->component);

			$route->registerComponent($component);

		}

		// Register route action
		$route->registerAction(
			new Route\Action(
				$resolvedRequest->action,
				$resolvedRequest->callback,
				$resolvedRequest->parameters
			)
		);

		// Register route controller
		$route->registerController(
			new Route\Controller(
				$resolvedRequest->controller,
				$resolvedRequest->callback,
				$resolvedRequest->parameters,
				false
			)
		);

		// Register route constraints
		if(count($resolvedRequest->constraints) > 0) {

			foreach($resolvedRequest->constraints as $constraint) {

				$ignoreComponent = (substr($constraint, 0, 1) === '/') ? true : false;

				$route->registerConstraint(new Route\Constraint($constraint, 'validate', [], false, $ignoreComponent));

			}

		}

		if($resolvedRequest->allowedRequestMethod === strtolower($brewery->httpClient->getMethod())) {

			$route->importResources();

			$route->validate();

			$actionResponse = $route->action()->invoke();

			$route->controller()->setParameters(array_merge($resolvedRequest->parameters, [$actionResponse]));

			$this->setCurrentRouteObject($route);

			return $route->controller()->invoke();

		} else {

			\Brewery\httpStatus(405);

			throw new Exceptions\RouterException(
				'Could not invoke route.', 'Route callback method is not allowed.',
				Exceptions\RouterException::UNEXPECTED_RESULT, __METHOD__
			);

		}

	}

}