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
     * Add model validations
     */
    public function validate()
    {
        // TODO Validate this model attributes
    }
}