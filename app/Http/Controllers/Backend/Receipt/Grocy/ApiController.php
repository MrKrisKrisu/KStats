<?php

namespace App\Http\Controllers\Backend\Receipt\Grocy;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\User;
use App\Exceptions\NotConnectedException;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

abstract class ApiController extends Controller {

    /**
     * @param User $user
     *
     * @return string
     * @throws NotConnectedException
     */
    private static function getGrocyHost(User $user): string {
        if($user->socialProfile->grocy_host == null || $user->socialProfile->grocy_key == null) {
            throw new NotConnectedException();
        }
        //TODO: Check and parse to return in valid schema
        return $user->socialProfile->grocy_host;
    }

    /**
     * @param User $user
     *
     * @return string
     * @throws NotConnectedException
     */
    private static function getApiKey(User $user): string {
        if($user->socialProfile->grocy_host == null || $user->socialProfile->grocy_key == null) {
            throw new NotConnectedException();
        }
        return $user->socialProfile->grocy_key;
    }

    /**
     * @param User $user
     *
     * @return mixed
     * @throws NotConnectedException
     * @throws GuzzleException
     */
    public static function getSystemInfo(User $user): stdClass {
        return self::getSystemInfoWithAuth(self::getGrocyHost($user), self::getApiKey($user));
    }

    /**
     * @param string $hostname
     * @param string $apiKey
     *
     * @return mixed
     * @throws GuzzleException
     */
    public static function getSystemInfoWithAuth(string $hostname, string $apiKey): stdClass {
        $client = new Client();
        $url    = strtr(':host/api/system/info', [
            ':host' => $hostname
        ]);
        $result = $client->get($url, [
            'headers' => [
                'GROCY-API-KEY' => $apiKey,
            ]
        ]);
        return json_decode($result->getBody()->getContents());
    }

    public static function checkConnection(User $user): bool {
        try {
            $systemInfo = self::getSystemInfo($user);
            if(!isset($systemInfo->grocy_version->Version)) {
                return false;
            }
            return true;
        } catch(NotConnectedException | GuzzleException) {
            return false;
        }
    }

    /**
     * @param User   $user
     * @param int    $amount
     * @param float  $price
     * @param string $barcode
     *
     * @return stdClass|null
     * @throws NotConnectedException|GuzzleException
     * @todo Waiting until https://github.com/grocy/grocy/pull/1565 is released...
     */
    public static function addToStockByBarcode(User $user, int $amount, float $price, string $barcode): ?stdClass {
        $client = new Client();
        $url    = strtr(':host/api/stock/products/by-barcode/:barcode/add', [
            ':host'    => self::getGrocyHost($user),
            ':barcode' => urlencode($barcode),
        ]);
        $result = $client->post($url, [
            'headers' => [
                'GROCY-API-KEY' => self::getApiKey($user),
            ],
            'json'    => [
                'amount'           => $amount,
                'transaction_type' => 'purchase',
                'price'            => $price,
            ],
        ]);
        $data   = json_decode($result->getBody()->getContents());
        return $data[0] ?? null;
    }

}
