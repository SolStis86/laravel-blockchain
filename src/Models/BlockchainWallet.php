<?php
namespace Solstis86\Blockchain\Models;


use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Solstis86\Blockchain\Facades\Payments;

class BlockchainWallet extends Model
{
    protected $hidden = ['callback_key', 'password'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('blockchain.wallet_table_name'));
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->callback_key = Uuid::uuid4();
        });

        static::created(function ($wallet) {
            Payments::setupBalanceReceiveCallbacks($wallet);
        });
    }

    /**
     * Relation to the wallets transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(BlockchainTransaction::class);
    }

    /**
     * Relation to model the wallet belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(config('wallet_parent_model'));
    }

    /**
     * Password value accessor (decrypt)
     * @param $value
     * @return string
     */
    public function getPasswordAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Password value mutator (encrypt)
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }

    public function scopeFindByCallbackKey($query, $callbackKey)
    {
        return $query->whereCallbackKey($callbackKey)->firstOrFail();
    }
}