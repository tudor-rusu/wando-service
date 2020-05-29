<?php

namespace app\models;

use app\components\abstractions\Model as ModelBase;
use app\components\abstractions\ModelFactory;
use app\traits\ApiTrait;
use Exception;

class Ebay extends ModelBase
{
    use ApiTrait;

    /**
     * @var array
     */
    public $attributes = [
        'provider'          => null,
        'merchant_id'       => null,
        'merchant_logo_url' => null,
        'item_id'           => null,
        'click_out_link'    => null,
        'main_photo_url'    => null,
        'price'             => null,
        'price_currency'    => null,
        'shipping_price'    => null,
        'title'             => null,
        'valid_until'       => null,
        'brand'             => null
    ];

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
     * @param $params
     *
     * @return array
     * @throws Exception
     */
    public static function find($params)
    {
        $myName             = explode('\\', __CLASS__);
        $params['provider'] = strtolower(array_pop($myName));

        try {
            if ($params['credentials'] && count($params['credentials']) > 0) {
                foreach ($params['credentials'] as $credential => $value) {
                    $params['params'][$credential] = $value;
                }
            }

            $apiRequest = self::connectApiXml($params);
            if ($apiRequest['resolution'] !== 'success') {
                throw new Exception($apiRequest['data']);
            }

            $dataCollection = [];
            if ($apiRequest['data']['paginationOutput']['totalEntries'] > 0) {
                $model = ModelFactory::create('Ebay');
                $model->loadAttributes($model->attributes);

                $dataCollection['paginationOutput'] = $apiRequest['data']['paginationOutput'];
                foreach ($apiRequest['data']['searchResult']['item'] as $item) {
                    $model->provider          = $params['provider'];
                    $model->merchant_id       = $item['sellerInfo']['sellerUserName'];
                    $model->merchant_logo_url = ($item['sellerInfo']['sellerUserName']) ?
                        '/usr/' . $item['sellerInfo']['sellerUserName'] : '';
                    $model->item_id           = $item['itemId'];
                    $model->click_out_link    = $item['viewItemURL'];
                    $model->main_photo_url    = $item['galleryURL'];
                    $model->price             = $item['sellingStatus']['currentPrice'];
                    $model->price_currency    = $item['currency'];
                    $model->shipping_price    = $item['shippingInfo']['shippingServiceCost'];
                    $model->title             = $item['title'];
                    $model->valid_until       = $item['listingInfo']['endTime'];
                    $model->brand             = $item['brand'];
                    $dataCollection['data'][] = $model->data;
                }
            }

            return [
                'resolution' => 'success',
                'data'       => $dataCollection
            ];
        } catch (Exception $exception) {
            return [
                'resolution' => 'error',
                'data'       => ['exception' => $exception->getMessage()]
            ];
        }
    }

    /**
     * Add model validations
     */
    public function validate()
    {
        // TODO Validate this model attributes
    }
}