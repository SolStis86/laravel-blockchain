<?php
namespace Solstis86\Blockchain\Concerns;


use Solstis86\Blockchain\Models\BlockchainWallet;

trait HasABlockchainWallet
{
    public function wallet()
    {
        return $this->hasOne(BlockchainWallet::class);
    }
}