<?php

namespace Modules\StripeManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\StripeManager\Database\Factories\StripeMoneyFactory;

class StripeMoney extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'stripe_domain',
        'money',
        'buyer_email',
        'buyer_name',
    ];

    public static function factory(): StripeMoneyFactory
    {
        return new StripeMoneyFactory();
    }
}