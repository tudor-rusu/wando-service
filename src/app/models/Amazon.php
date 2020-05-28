<?php

namespace app\models;

use app\components\abstractions\Model as ModelBase;
use app\components\abstractions\ModelFactory;
use Exception;

class Amazon extends ModelBase
{
    /**
     * Create Ebay model
     *
     * @return mixed
     * @throws Exception
     */
    public static function create()
    {
        return ModelFactory::create('Amazon');
    }

    /**
     * Add model validations
     */
    public function validate()
    {
        // TODO Validate this model attributes
    }
}