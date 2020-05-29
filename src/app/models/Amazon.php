<?php

namespace app\models;

use app\components\abstractions\Model as ModelBase;
use app\components\abstractions\ModelFactory;
use Exception;

class Amazon extends ModelBase
{
    /**
     * Create Amazon model
     *
     * @return mixed
     * @throws Exception
     */
    public static function create()
    {
        return ModelFactory::create('Amazon');
    }

    /**
     * Find and return results from feed
     *
     * @param $params
     *
     * @return array
     */
    public static function find($params)
    {
        $myName             = explode('\\', __CLASS__);
        $params['provider'] = strtolower(array_pop($myName));

        // TODO connect to API
        // TODO get collection

        return [];
    }

    /**
     * Add model validations
     */
    public function validate()
    {
        // TODO Validate this model attributes
    }
}