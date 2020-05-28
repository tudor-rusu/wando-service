<?php

namespace app\models;

use app\components\abstractions\Model as ModelBase;
use app\components\abstractions\ModelFactory;
use Exception;

class Ebay extends ModelBase
{

    /**
     * Create Ebay model
     *
     * @return mixed
     * @throws Exception
     */
    public static function create()
    {
        return ModelFactory::create('Ebay');
    }

    /**
     * Find and return results from feed
     *
     * @param array $params
     *
     * @return string
     */
    public static function find(array $params)
    {
        $myName = explode('\\', __CLASS__);
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