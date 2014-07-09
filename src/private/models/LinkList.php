<?php

class LinkList extends Model {
	private $items = array();

	function __construct($controller) {
		$this->controller = $controller;
	}

	function merge(LinkList $ll) {
		foreach ($ll->get() as $link) {
			$this->addHref($link['Url'], $link['Caption'], $link['UserLevel']);
		}
	}

	function add($caption, $controller, $method, $params) {
		$this->items[] = array(
			'caption' => $caption,
			'url' => ViewableController::getUrl($controller, $method, $params),
			'link' => ViewableController::getLink($caption, $controller, $method, $params),
		);
	}

	function addHref($url, $caption, $userLevel = 0, $category = null) {
		$this->items[] = array(
			'caption' => $caption,
			'url' => $url,
		);
	}

	function get() {
		global $user;

		if (sizeof($this->items) == 0) {
			return null;
		}

		return $this->items;
	}
}

?>
