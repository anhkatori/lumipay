<?php

namespace Modules\AirwalletManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AirwalletManager\Database\Factories\AirwalletAccountFactory;

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
        'status'
    ];

    public function getStatus(){
        return self::STATUSES[$this->status];
    }

    protected static function factory(): AirwalletAccountFactory
    {
        return AirwalletAccountFactory::new();
    }
}
