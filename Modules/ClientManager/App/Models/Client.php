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
        'private_key',
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
            $client->private_key = $client->private_key ?? Str::random(40);
        });
    }

    public function paypalAccounts()
    {
        return $this->hasManyThrough(
            PaypalAccount::class,
            Client::class,
            'id', 
            'client_ids', 
            'id', 
            'id' 
        )->whereRaw("FIND_IN_SET(clients.id, paypal_accounts.client_ids)");
    }

    public function stripeAccounts()
    {
        return $this->hasManyThrough(
            StripeAccount::class,
            Client::class,
            'id', 
            'client_ids', 
            'id', 
            'id' 
        )->whereRaw("FIND_IN_SET(clients.id, stripe_accounts.client_ids)");
    }

    public function airwalletAccounts()
    {
        return $this->hasManyThrough(
            AirwalletAccount::class,
            Client::class,
            'id', 
            'client_ids', 
            'id', 
            'id' 
        )->whereRaw("FIND_IN_SET(clients.id, airwallet_accounts.client_ids)");
    }

    protected static function factory(): ClientFactory
    {
        return ClientFactory::new();
    }
}
