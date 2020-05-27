<?php

namespace app\controllers;

use app\components\abstractions\Controller as BaseController;

/**
 * Main class responsible to resolve the service requests
 */
class ServiceController extends BaseController
{

    /**
     * Main action for the service
     */
    public function actionIndex()
    {
        echo 'service developing ...';
    }

}
