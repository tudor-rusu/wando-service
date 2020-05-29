<?php

namespace app\traits;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use function Couchbase\defaultDecoder;

trait ApiTrait
{

    /**
     * Connect to API by Guzzle Http
     *
     * @param string $requestMethod
     * @param string $apiUrl
     * @param array  $params
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function connectApiGuzzle(string $requestMethod, string $apiUrl, array $params)
    {
        $client = new Client();

        if ($requestMethod === 'GET') {
            $response = $client->request('GET', $apiUrl, [
                'query' => $params
            ]);
        } else {
            $response = $client->request('POST', $apiUrl, [
                'form_params' => $params
            ]);
        }

        return $response;
    }

    public static function connectApiXml(array $params)
    {
        try {
            $buildApiCall = $params['apiPath'] . '?';
            foreach ($params['params'] as $key => $value) {
                $buildApiCall .= "$key=$value&";
            }
            $buildApiCall = substr($buildApiCall, 0, -1);
            $buildApiCall .= '&keywords=' . $params['keywords'];
            if ($params['filters'] && count($params['filters']) > 0) {
                $count = 0;
                foreach ($params['filters'] as $filter => $value) {
                    $buildApiCall .= "&itemFilter($count).name=$filter";
                    $buildApiCall .= "&itemFilter($count).value=$value";
                    $count++;
                }
            }
            if (!empty($params['sorting'])) {
                $buildApiCall .= '&sorting=' . $params['sorting'];
            }
            if (!empty($params['pagination']['pageNumber'])) {
                $buildApiCall .= '&paginationInput.pageNumber=' . $params['pagination']['pageNumber'];
                $buildApiCall .= '&paginationInput.entriesPerPage=' . $params['pagination']['itemsPerPage'];
            }

            $apiResponse = simplexml_load_file($buildApiCall);

            if ($apiResponse->ack != "Success") {
                throw new Exception('The request was not successful.');
            }

            // set currency
            foreach ($apiResponse->searchResult->item as $key => $item) {
                $currency = $item->sellingStatus->currentPrice['currencyId'];
                $item->addChild('currency', $currency);
            }

            return [
                'resolution' => 'success',
                'data'       => json_decode(json_encode((array)$apiResponse), true)
            ];
        } catch (Exception $exception) {
            return [
                'resolution' => 'error',
                'data'       => ['exception' => $exception->getMessage()]
            ];
        }
    }

    /**
     * Return list with all APIs from config
     *
     * @return array
     */
    public static function getAllApi()
    {
        try {
            if (is_null(self::configExist())) {
                throw new Exception('List of configured feeds does not exit on server.');
            }

            $apiConfig = self::configExist();

            return [
                'resolution' => 'success',
                'data'       => array_keys($apiConfig)
            ];
        } catch (Exception $exception) {
            return [
                'resolution' => 'error',
                'data'       => ['exception' => $exception->getMessage()]
            ];
        }
    }

    /**
     * Check if feeds config exist and return configuration
     *
     * @return mixed|null
     */
    public static function configExist()
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/app/config/feeds.json')) {
            return json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/app/config/feeds.json'), true);
        }

        return null;
    }

    /**
     * Return all params form a specific feed
     *
     * @param $apiName
     *
     * @return array
     */
    public static function getApiParams($apiName)
    {
        try {
            if (is_null(self::configExist())) {
                throw new Exception('List of configured feeds does not exit on server.');
            }

            $apiConfig = self::configExist();

            if (is_null(self::feedExist($apiName, $apiConfig))) {
                throw new Exception(sprintf('Feed %s is not configured.', $apiName));
            }

            return [
                'resolution' => 'success',
                'data'       => $apiConfig[$apiName]
            ];
        } catch (Exception $exception) {
            return [
                'resolution' => 'error',
                'data'       => ['exception' => $exception->getMessage()]
            ];
        }
    }

    /**
     * Check if feed exist in configuration
     *
     * @param string $feedName
     * @param array  $feedConfig
     *
     * @return bool|null
     */
    private static function feedExist(string $feedName, array $feedConfig)
    {
        if (array_key_exists($feedName, $feedConfig)) {
            return true;
        }

        return null;
    }
}
