<?php

namespace Modules\AirwalletManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AirwalletManager\Database\factories\AirwalletMoneyFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AirwalletMoney extends Model
{
    use HasFactory;

    const STATUSES = [
        0 => 'hold',
        1 => 'active',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'domain',
        'money',
        'buyer_name',
        'buyer_email',
        'status'
    ];
    
    public function getStatus(){
        return self::STATUSES[$this->status];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(AirwalletAccount::class);
    }
}
