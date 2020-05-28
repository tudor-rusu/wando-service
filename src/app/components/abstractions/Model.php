<?php

namespace app\components\abstractions;

use \app\components\ModelError;

/**
 * Abstract class that defines a model representation
 */
abstract class Model extends Base
{

    /**
     * @var array
     */
    public $errors = [];

    /**
     * Create the model
     */
    abstract static function create();

    /**
     * Find and return all model data
     *
     * @param array $params
     */
    abstract static function find(array $params);

    /**
     * Validate the model
     */
    abstract function validate();

    /**
     * Add errors to the model
     *
     * @param ModelError $error
     */
    public function addError(ModelError $error)
    {
        $this->errors[] = $error;
    }

    /**
     * Check if the model has errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        if (count($this->errors) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Return the errors of model
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
