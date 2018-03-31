<?php
/**
 * Created by PhpStorm.
 * User: jamesf
 * Date: 31/03/2018
 * Time: 18:35
 */

namespace Solstis86\Blockchain;


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
}
