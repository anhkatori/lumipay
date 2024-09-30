<?php

namespace Modules\AirwalletManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AirwalletManager\Database\Factories\AirwalletAccountFactory;
use Modules\ClientManager\App\Models\Client;
use Modules\AirwalletManager\App\Models\AirwalletMoney;

class AirwalletAccount extends Model
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

    public function getStatus(){
        return self::STATUSES[$this->status];
    }

    protected static function factory(): AirwalletAccountFactory
    {
        return AirwalletAccountFactory::new();
    }

    public function clients(){
        return $this->belongsToMany(Client::class)
            ->whereIn('clients.id', explode(',', $this->client_ids));
    }

    public function getClientsAttribute()
    {
        return Client::whereIn('id', explode(',', $this->client_ids))->get();
    }

    public function getRouteName(){
        return 'airwallet-accounts';
    }
    public function getWithdrawn()
    {
        return $this->hasMany(AirwalletMoney::class, 'account_id', 'id')
            ->where('status', 0)
            ->sum('money');
    }
}
