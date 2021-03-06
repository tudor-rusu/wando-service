<?php

namespace app\components\abstractions;

use Exception;

/**
 * Class that instantiates the model class
 */
class ModelFactory
{

    /**
     * Create the model based on type
     *
     * @param $type
     *
     * @return mixed
     * @throws Exception
     */
    public static function create($type = '')
    {
        if ($type == '') {
            throw new Exception('Invalid Model Type.');
        } else {

            $className = 'app\models\\' . ucfirst($type);
            if (class_exists($className)) {
                return new $className();
            } else {
                throw new Exception("Model type {$className} not found.");
            }
        }
    }

}
