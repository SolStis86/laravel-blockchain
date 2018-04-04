<?php
namespace Solstis86\Blockchain\Models;


use Illuminate\Database\Eloquent\Model;
use Solstis86\Blockchain\Contracts\BlockchainTransactionTypes;

class BlockchainTransaction extends Model implements BlockchainTransactionTypes
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('blockchain.transaction_table_name'));
    }

    public static function boot()
    {
        parent::boot();

        static::created(function (BlockchainTransaction $model) {

        });
    }

    public function wallet()
    {
        return $this->belongsTo(BlockchainWallet::class);
    }

    public function getCallbackKeyAttribute()
    {
        return $this->wallet->callback_key;
    }

    public function validateCallbackKey($callbackKey)
    {
        return $callbackKey == $this->callback_key;
    }

    public function scopeFindByTransactionHash($query, $transactionHash)
    {
        return $query->where('transaction_hash', $transactionHash)->firstOrFail();
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('transaction_type', self::SPEND);
    }

    public function registerCallbacks()
    {

    }
}