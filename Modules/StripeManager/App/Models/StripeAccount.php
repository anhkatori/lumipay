<?php

namespace Modules\StripeManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\StripeManager\Database\Factories\StripeAccountFactory;
use Modules\ClientManager\App\Models\Client;
use Modules\StripeManager\App\Models\StripeMoney;

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
        'client_ids',
    ];

    public function getStatus()
    {
        return self::STATUSES[$this->status];
    }

    public static function factory()
    {
        return new StripeAccountFactory();
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class)
            ->whereIn('clients.id', explode(',', $this->client_ids));
    }
    public function getClientsAttribute()
    {
        return Client::whereIn('id', explode(',', $this->client_ids))->get();
    }
    public function getRouteName()
    {
        return 'stripe-accounts';
    }

    public function getWithdrawn()
    {
        return $this->hasMany(StripeMoney::class, 'account_id', 'id')
            ->where('status', 0)
            ->sum('money');
    }
}
