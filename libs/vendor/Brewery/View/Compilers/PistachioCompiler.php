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

/* @namespace Compilers */
namespace Brewery\View\Compilers;

/* @imports */
use Brewery\View\CompilerAbstract;

/* Deny direct file access */
if(!defined('BREWERY_ROOT_PATH')) exit;



/**
 *	PistachioCompiler
 *
 *	View compiler for PHP + Mustasch (pistachio!) files.
 *
 *	@vendor Brewery
 *	@package View\Compilers
 *
 *	@version 1.0
 *
 *	@author Robin Grass <hej@carbin.se>
 */
class PistachioCompiler extends CompilerAbstract {

	/**
	 *	@const string DELIMITER_OPEN
	 */
	const DELIMITER_OPEN = '{{';

	/**
	 *	@const string DELIMITER_CLOSE
	 */
	const DELIMITER_CLOSE = '}}';

	/**
	 *	@const string AT_FUNCTION
	 */
	const AT_FUNCTION = '/(?<!\w)(\s*)@%s(\s*)/';

	/**
	 *	@const string AT_STRUCTURE
	 */
	const AT_STRUCTURE = '/(\s*)?@%s\s?([\'\"]([a-z\:.]+)[\'\"])\s?/i';

	/**
	 *	@var string $viewFileExtension View file extension.
	 */
	protected $viewFileExtension = 'php';

	/**
	 *	@var string $viewFileExtensionPrefix View file extension prefix.
	 */
	protected $viewFileExtensionPrefix = 'io';

	/**
	 *	Constructor
	 *
	 *	Registers Pistachio token callbacks.
	 *
	 *	@return void
	 */
	public function __construct() {

		$this->registerCallback('compileVariableOutput');

		$this->registerCallback('compileStatementBlockOpening');
		$this->registerCallback('compileStatementBlockClosing');
		$this->registerCallback('compileStatementBlockElse');

		$this->registerCallback('compileRenderStructure');
		$this->registerCallback('compilePartialStructure');
		$this->registerCallback('compileYieldStructure');

	}

	/**
	 *	compileVariableOutput
	 *
	 *	Compiles variable output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compileVariableOutput($output) {

		$self = $this;

		$pattern = sprintf('/%s\s*(.+?)\s*%s/s', self::DELIMITER_OPEN, self::DELIMITER_CLOSE);

		return preg_replace_callback($pattern, function($matches) use ($output, $self) {

			return sprintf('<?php echo %s; ?>', $self->compileTernaryVariableOutput($matches[1]));

		}, $output);

	}

	/**
	 *	compileTernaryVariableOutput
	 *
	 *	Compiles ternary statement output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compileTernaryVariableOutput($output) {

		return preg_replace('/^(?=\$)(.+?)(?:\s+or\s+)(.+?)$/s', 'isset($1) ? $1 : $2', $output);

	}

	/**
	 *	compileStatementBlockOpening
	 *
	 *	Compiles statement block output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compileStatementBlockOpening($output) {

		$pattern = '/(?(R)\((?:[^\(\)]|(?R))*\)|(?<!\w)(\s*)@(if|elseif|foreach|for|while)(\s*(?R)+))/';

		return preg_replace($pattern, '$1<?php $2$3: ?>', $output);

	}

	/**
	 *	compileStatementBlockClosing
	 *
	 *	Compiles closing statement block output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compileStatementBlockClosing($output) {

		$pattern = '/(\s*)@(endif|endforeach|endfor|endwhile)(\s*)/';

		return preg_replace($pattern, '$1<?php $2; ?>$3', $output);

	}

	/**
	 *	compileStatementBlockElse
	 *
	 *	Compiles else statement block output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compileStatementBlockElse($output) {

		$pattern = sprintf(self::AT_FUNCTION, 'else');

		return preg_replace($pattern, '$1<?php else: ?>$2', $output);

	}

	/**
	 *	compileRenderStructure
	 *
	 *	Compiles "render" structure output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compileRenderStructure($output) {

		$pattern = sprintf(self::AT_STRUCTURE, 'render');

		return preg_replace($pattern, '$1<?php echo $__view->render(\'$3\'); ?>$4', $output);

	}

	/**
	 *	compilePartialStructure
	 *
	 *	Compiles "partial" structure output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compilePartialStructure($output) {

		$pattern = sprintf(self::AT_STRUCTURE, 'partial');

		return preg_replace($pattern, '$1<?php echo $__view->render(\'_$3\'); ?>$4', $output);

	}

	/**
	 *	compileYieldStructure
	 *
	 *	Compiles "yield" structure output.
	 *
	 *	@param string $output
	 *
	 *	@return string
	 */
	protected function compileYieldStructure($output) {

		$pattern = sprintf(self::AT_FUNCTION, 'yield');

		return preg_replace($pattern, '$1<?php if(isset($__viewFile) === true) { echo $__view->render($__viewFile); } ?>$3' . "\n\n\n", $output);

	}

}