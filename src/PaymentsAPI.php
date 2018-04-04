<?php
namespace Solstis86\Blockchain;


use Solstis86\Blockchain\Models\BlockchainWallet;

class PaymentsAPI
{
    private $http;

    private $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->http = new Client([
            'base_uri' => 'https://api.blockchain.info/v2/',
        ]);
    }

    public function setupBalanceReceiveCallbacks(BlockchainWallet $wallet)
    {
        $response = $this->http->post('receive/balance_update', [
            'json' => [
                'key' => config('blockchain.api_key'),
                'addr' => $wallet->address,
                'confs' => 1,
                'op' => 'RECEIVE',
                'onNotification' => 'KEEP',
                'callback' => config('blockchain.callback_url_base') . '/balance?callback_key=' . $wallet->callback_key
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        return true;
    }

    public function deleteBalanceUpdateRequest($id)
    {
        $response = $this->http->post('receive/balance_update/' . $id, [
            'json' => [
                'key' => config('blockchain.api_key'),
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        return true;
    }

}
