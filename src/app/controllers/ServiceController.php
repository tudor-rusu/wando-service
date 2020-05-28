<?php

namespace app\controllers;

use app\components\abstractions\Controller as BaseController;
use app\components\abstractions\ModelFactory;
use app\traits\ApiTrait;
use Exception;

/**
 * Main class responsible to resolve the service requests
 */
class ServiceController extends BaseController
{
    use ApiTrait;

    public $feed = 'all';

    /**
     * Main action for the service
     *
     * @throws Exception
     */
    public function actionIndex()
    {
        $feed = $_REQUEST['feed'] ?
            strtolower(filter_var(trim($_REQUEST['feed']), FILTER_SANITIZE_STRING)) :
            $this->feed;

        try {
            if ($feed !== $this->feed) {
                $apiParams = self::getApiParams($feed);
                if ($apiParams['resolution'] === 'error') {
                    throw new Exception($apiParams['data']['exception']);
                }
                $feedModel = ModelFactory::create($feed);
//                $feedModel::find(self::getApiParams($feed));
                http_response_code(200);
                echo json_encode([
                    'resolution' => 'success',
                    'message'    => '',
                    'data'       => $feedModel::find($apiParams['data'])
                ]);
            } else {
                $allApiList = self::getAllApi();
                if ($allApiList['resolution'] === 'error') {
                    throw new Exception($allApiList['data']['exception']);
                } else {
                    $data = [];
                    foreach ($allApiList['data'] as $apiName) {
                        $apiParams = self::getApiParams($apiName);
                        if ($apiParams['resolution'] === 'error') {
                            throw new Exception($apiParams['data']['exception']);
                        }
                        $feedModel = ModelFactory::create($apiName);
                        array_push($data, $feedModel::find($apiParams['data']));
                    }
                    http_response_code(200);
                    echo json_encode([
                        'resolution' => 'success',
                        'message'    => '',
                        'data'       => $data
                    ]);
                }
            }
        } catch (Exception $exception) {
            echo json_encode([
                'resolution' => 'error',
                'message'    => $exception->getMessage(),
                'data'       => []
            ]);
            exit;
        }
    }

    /**
     * Actions before call action
     *
     * @param $actionName
     */
    protected function beforeAction($actionName)
    {
        parent::beforeAction($actionName);

        if ($_SERVER['REQUEST_METHOD'] !== $this->config['REQUEST_METHOD']) {
            http_response_code(405);
            echo json_encode([
                'resolution' => 'Error',
                'message'    => 'This service accept only ' . $this->config['REQUEST_METHOD'] . ' request method.',
                'data'       => []
            ]);
            exit;
        }
    }

}
