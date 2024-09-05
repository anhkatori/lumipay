<?php

namespace Modules\StripeManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\StripeManager\Database\Factories\StripeAccountFactory;
use Modules\ClientManager\App\Models\Client;

class StripeAccount extends Model
{
    use HasFactory;

    const STATUSES = [
        0 => 'Pause',
        1 => 'Active'
    ];

    protected $fillable = [
        'domain', 
        'max_receive_amount', 
        'current_amount', 
        'max_order_receive_amount', 
        'status',
        'client_id',
    ];

    public function getStatus(){
        return self::STATUSES[$this->status];
    }

    public static function factory()
    {
        return new StripeAccountFactory();
    }
    
    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getRouteName(){
        return 'stripe-accounts';
    }
}
