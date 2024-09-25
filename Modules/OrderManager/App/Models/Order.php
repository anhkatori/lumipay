<?php

namespace Modules\OrderManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\OrderManager\Database\factories\OrderFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'request_id',
        'amount',
        'email',
        'ip',
        'description',
        'cancel_url',
        'return_url',
        'notify_url',
        'method',
        'status',
        'method_account',
        'client_id',
        'canceled'
    ];

    protected static function factory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
