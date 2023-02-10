<?php

namespace App\Repositories;;

use App\Interfaces\XenditInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Traits\BugsnagTrait;
use Throwable;

class XenditRepository implements XenditInterface
{
    use BugsnagTrait;

    public static function reports()
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/report/?account_id='.env('XENDIT_ACCOUNT_ID'));

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function createReport(string $type, string $from, string $to)
    {
        $params = [
            'json' => [
                'account_id' => env('XENDIT_ACCOUNT_ID'),
                'type' => $type,
                'from' => $from,
                'to' => $to
            ]
        ];

        try {
            $res  = self::requestor('POST', '/service/xendit/api/v1/report', $params);

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function getReport(string $id)
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/report/'.$id.'?account_id='.env('XENDIT_ACCOUNT_ID'));

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function getBalance(string $type)
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/general/balance/'.env('XENDIT_ACCOUNT_ID').'/'.$type);

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function getDisbursement(string $id)
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/disbursement/get-by-id/'.$id.'?account_id='.env('XENDIT_ACCOUNT_ID'));

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function getInvoice(string $id)
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/invoice/'.$id.'?account_id='.env('XENDIT_ACCOUNT_ID'));

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function banks()
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/general/available-banks');

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function paymentChannels()
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/general/payment-channels');

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function account()
    {
        try {
            $res  = self::requestor('GET', '/service/xendit/api/v1/platform/accounts/'.env('XENDIT_ACCOUNT_ID'));

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function createInvoice(array $params)
    {
        try {
            if (isset($params['json']['payment_methods'])) {
                $value = reset($params['json']['payment_methods']);
                if ($value === 'VISA' || $value === 'MASTERCARD' || $value === 'JCB') {
                    unset($params['json']['payment_methods']);
                    $params['json']['payment_methods'] = ['CREDIT_CARD'];
                }
            }
            $res  = self::requestor('POST', '/service/xendit/api/v1/invoice', $params);

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    public static function createFeeRule(array $params)
    {
        try {
            $res  = self::requestor('POST', '/service/xendit/api/v1/platform/fee_rules', $params);

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }
    
    public static function createDisbursement(array $params)
    {
        try {
            $res  = self::requestor('POST', '/service/xendit/api/v1/disbursement', $params);

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    protected static function requestor(string $verb, string $endpoint, array $payload = [])
    {
        try {
            $defaultClient = [
                'base_uri' => env('SEMAI_API_GATEWAY_ENDPOINT'),
                'headers' => [
                    'x-api-version' => 'v1',
                    'Authorization' => 'Bearer '.env('SEMAI_API_GATEWAY_ENDPOINT_TOKEN')
                ]
            ];

            $client = new Client($defaultClient);

            $res = $client->request($verb, $endpoint, $payload);

            $data = (string) $res->getBody();
            $data = json_decode($data);

            return $data;
        } catch (RequestException $e) {
            throw $e;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }
}
