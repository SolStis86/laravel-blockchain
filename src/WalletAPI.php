<?php
namespace Solstis86\Blockchain;


use GuzzleHttp\Client;
use Solstis86\Blockchain\Exceptions\UnableToCreateWalletException;
use Solstis86\Blockchain\Exceptions\WithdrawalFailureException;
use Solstis86\Blockchain\Models\BlockchainTransaction;
use Solstis86\Blockchain\Models\BlockchainWallet;

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

    public function createWallet($password, $private_key = null, $label = null, $email = null): BlockchainWallet
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

        $args = array_merge(json_decode($response->getBody(), true), [
            'password' => $password,
        ]);

        return BlockchainWallet::create($args);
    }

    public function withdraw(BlockchainWallet $wallet, $to, $amountBTC)
    {
        $response = $this->http->get('merchant/' . $wallet->guid . '/payment', [
            'query' => [
                'password' => $wallet->password,
                'to' => $to,
                'amount' => $amountBTC * 100000000,
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            throw new WithdrawalFailureException($response->getBody());
        }

        $args = array_merge(json_decode($response->getBody(), true), [
            'to_address' => $to,
        ]);

        return $wallet->transactions()->save(
            new BlockchainTransaction($args)
        );
    }
}