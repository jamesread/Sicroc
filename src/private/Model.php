<?php

abstract class Model {
    public static function factory($model, $title, $principle) {
        switch ($model) {
            case 'Table';
            return new Table($principle);
            break;
        default:
            throw new Exception($model);
        }
    }

    //FIXME make this abstract
    public function render() {
        return '<p>I am a default model.</p>';
    }
}

?>
