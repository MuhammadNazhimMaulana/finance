<?php

namespace App\Repositories\Webhook;;

use App\Interfaces\Webhook\DonationWebhookInterface;
use App\Traits\BugsnagTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\{Config};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class DonationWebhookRepository implements DonationWebhookInterface
{
    use BugsnagTrait;
    const URL = 'WEBHOOK_URL';
    const AUTH = 'thisissecret';

    public function index()
    {
        try {
            $res = Config::query();

            $data = $res->where('name', self::URL)->first();

            return view('webhook.index', [
                'data' => $data ? $data->value : 'URL has not set',
            ]);
        } catch (Throwable $e) {
            $this->report($e);
            abort(400, $e->getMessage());
        }
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            // Checking if the url is exist or not
            $url_check = Config::where('name', self::URL)->first();

            if(!$url_check)
            {
                $res = new Config();
                $res->name = self::URL;
                $res->value = $request->value;
                $res->save();
            }else{
                $url_check->value = $request->value;
                $url_check->save();
            }

            DB::commit();

            return back()->with('successMsg', __('Webhook URL Saved'));
        } catch (Throwable $e) {
            $this->report($e);
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    public static function sendWebhook(array $params)
    {
        try {
            $url = Config::where('name', self::URL)->first();

            $res  = self::requestor($url->value, 'POST', '', $params);

            return $res;
        } catch (Throwable $e) {
            self::staticReport($e);
            throw $e;
        }
    }

    protected static function requestor(string $url, string $verb, string $endpoint, array $payload = [])
    {
        try {
            $defaultClient = [
                'base_uri' => $url,
                'headers' => [
                    'x-api-version' => 'v1',
                    'Authorization' => md5(self::AUTH)
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
