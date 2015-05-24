<?php

use \libAllure\Session;

abstract class Controller {
	public function getAdminLinks() {}
	public function getNavigationMain() {}

	/*
	@return An array of instanciated controllers.
	*/
	function controllerList() {
		$objects = scandir(CONTROLLERS_DIR);

		$controllersList = array();
		foreach ($objects as $o) {
			if (is_file(CONTROLLERS_DIR . $o) && (substr($o, -4) == '.php')) {
				require_once CONTROLLERS_DIR . $o;

				$o = str_replace('.php', null, $o);

				if (classHasParent($o, 'Module')) {
					eval('$inst = new ' . $o . ';');

					$controllersList[] = $inst;
				}
			}
		}

		return $controllersList;
	}

	function classHasParent($class, $parent) {
		$parents = class_parents($class);

		foreach ($parents as $mybottom) {
			if ($mybottom == $parent) {
				return true;
			}
		}

		return false;
	}

	public function getTitle() {
		return 'Untitled controller';
	}
}

abstract class ViewableController extends Controller {
	public $navigation;
	public $widgetId;
	public $displayEdit = true;

	public function __construct($principle = null) {
		global $user, $tpl;

		$this->principle = $principle;
		$this->navigation = new \libAllure\HtmlLinksCollection();
		$tpl->assign('title', get_class($this));
		$tpl->assign('base', 'http://www.tydus.net/Sicroc/src/public/');

		$this->assignUser();
	}

	public function widgetSetupCompleted() {

	}
	
	private function assignUser() {
		global $tpl;

		$user = array (
		
		);

		if (!Session::isLoggedIn()) {
			$user['username'] = 'Guest';
		} else {
			$user['username'] = Session::getUser()->getUsername();
		}

		$tpl->assign('user', $user);
	}

	public function index() {
	}

	public function render() {
		global $tpl;

		$tpl->assign('message', 'This is a simple widget (widget has not overridden Controller::render(). )');
		$tpl->display('simple.tpl');
	}

	public static function getLink($caption, $controller, $method = 'index', $params = array()) {
		return '<a href = "' . ViewableController::getUrl($controller, $method, $params) . '">' . $caption . '</a>';
	}

	public static function getUrl($controller, $method = 'index', $params = array()) {
		if (is_object($controller)) {
			$controller = get_class($controller);
		} else {
			$controller = strval($controller);
		}

		$query = http_build_query($params);

		//return $controller . '/' . $method . '/' . $query;
		// If not mod rw
		return 'dispatcher.php?controller=' . $controller . '&amp;method=' . $method . '&amp;page=' . $params['page'];
	}


	protected $argValues = null;

	public function getArgumentValues() {
		if ($this->argValues == null) {
			$sql = 'SELECT v.`key`, v.value FROM widget_argument_values v WHERE v.widget = :widget';
			$stmt = stmt($sql);
			$stmt->bindValue(':widget', $this->widgetId);
			$stmt->execute();

			$this->argValues = array();
			
			foreach ($stmt->fetchAll() as $arg) {
				$this->argValues[$arg['key']] = $arg['value'];	
			}
		}
		return $this->argValues;
	}

	public function getArgumentValue($name) {
		$this->getArgumentValues();

		if (isset($this->argValues[$name])) {
			return $this->argValues[$name];
		} else {
			return null;
		}
	}


	public function getArguments() {
		return array();
	}

	public function hasArguments() {
		return sizeof($this->getArguments()) > 0;
	}

	public function getArgumentElement($name, $val = 0) {
		$el = new \libAllure\ElementInput($name, $name, $val);
		return $el;
	}
}

?>
