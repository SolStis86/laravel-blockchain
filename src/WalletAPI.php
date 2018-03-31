<?php
/**
 * Created by PhpStorm.
 * User: jamesf
 * Date: 31/03/2018
 * Time: 16:44
 */

namespace Solstis86\Blockchain;


use GuzzleHttp\Client;
use Solstis86\Blockchain\Exceptions\UnableToCreateWalletException;

class WalletAPI
{
    private $http;

    private $app;

    public function __construct($app)
    {
        $this->http = new Client([
            'base_uri' => 'http://localhost:' . config('blockchain.local_service_port'),
        ]);

        $this->app = $app;
    }

    public function createWallet($password, $private_key = null, $label = null, $email = null)
    {
        $response = $this->http->post('api/v2/create', [
            'json' => [
                'password' => $password,
                'api_code' => config('blockchain.api_key'),
                'priv' => $private_key,
                'label' => $label,
                'email' => $email,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            throw new UnableToCreateWalletException($response->getBody());
        }

        return $response->getBody();
    }
}