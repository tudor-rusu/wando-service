<?php

namespace app\traits;

use Exception;

trait ApiTrait
{

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
