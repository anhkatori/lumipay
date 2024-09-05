<?php

namespace Modules\ClientManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\ClientManager\Database\Factories\ClientFactory;
use Modules\AirwalletManager\App\Models\AirwalletAccount;
use Modules\StripeManager\App\Models\StripeAccount;
use Laravel\Sanctum\HasApiTokens;


class Client extends Model
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'address',
        'merchant_id',
        'public_key',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate unique merchant_id and public_key on create
        static::creating(function ($client) {
            $client->merchant_id = $client->merchant_id ?? Str::uuid();
            $client->public_key = $client->public_key ?? Str::random(40);
        });
    }

    public function paypalAccounts(){
        return $this->hasMany(PaypalAccount::class, 'client_id');
    }

    public function stripeAccounts(){
        return $this->hasMany(StripeAccount::class, 'client_id');
    }

    public function airwalletAccounts(){
        return $this->hasMany(AirwalletAccount::class, 'client_id');
    }

    protected static function factory(): ClientFactory
    {
        return ClientFactory::new();
    }
}
