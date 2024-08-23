<?php

namespace Modules\ClientManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Modules\PayPalManager\App\Models\PaypalAccount;
use Modules\ClientManager\Database\Factories\ClientFactory;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
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

    public function PayPalAccounts(){
        return $this->belongsTo(PaypalAccount::class, 'client_id');
    }

    protected static function factory(): ClientFactory
    {
        return ClientFactory::new();
    }
}
