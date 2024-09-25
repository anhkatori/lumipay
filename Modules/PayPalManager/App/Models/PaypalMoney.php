<?php

namespace Modules\PayPalManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PayPalManager\Database\Factories\PaypalMoneyFactory;

class PaypalMoney extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'paypal_email',
        'money',
        'buyer_email',
        'buyer_name',
    ];

    public static function factory(): PaypalMoneyFactory
    {
        return new PaypalMoneyFactory();
    }
}