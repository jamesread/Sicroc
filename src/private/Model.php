<?php

abstract class Model {
	public static function factory($model, $title, $principle) {
		switch ($model) {
			case 'Table';
				return new Table($principle);
				break;
			default:
				throw new ModelNotFoundException($model);
		}
	}

	//FIXME make this abstract
	public function render() {
		return '<p>I am a default model.</p>';
	}
}

class ModelNotFoundException extends Exception {

}

?>
