<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Traits\BugsnagTrait;
use Throwable;

class CitiesHelper
{
    use BugsnagTrait;

    public static function list()
    {
        try {
            $client = new Client();

            $res = $client->request('GET', 'https://raw.githubusercontent.com/Semai-Intern-Team/countries-states-cities-database/master/cities.json');

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
