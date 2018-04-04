<?php
/**
 * User: jamesf
 * Date: 31/03/2018
 * Time: 16:32
 */

namespace Solstis86\Blockchain;


use Illuminate\Support\ServiceProvider;

class BlockchainServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/blockchain.php' => config_path('blockchain.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'../database/migrations');
    }

    public function register()
    {
        $this->app->bind('blockchain.wallet', function($app) {
            return new WalletAPI($app);
        });

        $this->app->bind('blockchain.payments', function($app) {
            return new PaymentsAPI($app);
        });
    }

    public function provides()
    {
        return ['blockchain.wallet', 'blockchain.payments'];
    }
}