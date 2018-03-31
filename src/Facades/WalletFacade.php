<?php
/**
 * Created by PhpStorm.
 * User: jamesf
 * Date: 31/03/2018
 * Time: 17:48
 */

namespace Solstis86\Blockchain\Facades;


use Illuminate\Support\Facades\Facade;

class WalletFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'blockchain.wallet';
    }
}