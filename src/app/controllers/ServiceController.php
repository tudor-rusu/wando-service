<?php

namespace app\controllers;

use app\components\abstractions\Controller as BaseController;
use app\components\abstractions\ModelFactory;
use app\traits\ApiTrait;
use app\traits\ValidationTrait;
use Exception;

/**
 * Main class responsible to resolve the service requests
 */
class ServiceController extends BaseController
{
    use ApiTrait, ValidationTrait;

    public $feed = 'all';

    /**
     * Main action for the service
     *
     * @throws Exception
     */
    public function actionIndex()
    {
        $feed = $_REQUEST['feed'] ?
            strtolower(self::sanitizeString($_REQUEST['feed'])) :
            $this->feed;

        try {
            $dataCollection = [];
            if ($feed !== $this->feed) {
                $apiParams = self::getApiParams($feed);
                if ($apiParams['resolution'] === 'error') {
                    throw new Exception($apiParams['data']['exception']);
                }

                // set keywords
                $apiParams['data']['keywords'] = ($_REQUEST['keywords']) ?
                    strtolower(urlencode(self::sanitizeString($_REQUEST['keywords']))) : '';

                // set filters
                $apiParams['data']['filters']['MinPrice'] = ($_REQUEST['price_min']) ?
                    self::sanitizeString($_REQUEST['price_min']) : '';
                $apiParams['data']['filters']['MaxPrice'] = ($_REQUEST['price_max']) ?
                    self::sanitizeString($_REQUEST['price_max']) : '';

                // set sorting
                // applicable values https://developer.ebay.com/DevZone/finding/CallRef/extra/fnditmsbykywrds.rqst.srtordr.html
                $sanitizeSorting              = ($_REQUEST['sorting']) ?
                    self::sanitizeString($_REQUEST['sorting']) : '';
                $apiParams['data']['sorting'] = ($sanitizeSorting !== 'default') ?
                    $sanitizeSorting : '';

                // set pagination
                $apiParams['data']['pagination']['pageNumber']   = ($_REQUEST['page_number']) ?
                    self::sanitizeString($_REQUEST['page_number']) : '';
                $apiParams['data']['pagination']['itemsPerPage'] = ($_REQUEST['items_per_page']) ?
                    self::sanitizeString($_REQUEST['items_per_page']) : '';

                $feedModel      = ModelFactory::create($feed);
                $dataCollection = $feedModel::find($apiParams['data']);

                http_response_code(200);
                echo json_encode([
                    'resolution' => 'success',
                    'message'    => '',
                    'data'       => $dataCollection['data']
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
