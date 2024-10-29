<?php

namespace Modules\OrderManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\OrderManager\Database\factories\OrderFactory;
use Modules\AirwalletManager\App\Models\AirwalletAccount;
use Modules\StripeManager\App\Models\StripeAccount;
use Modules\PayPalManager\App\Models\PaypalAccount;

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
        'canceled',
        'canceled',
        'addtional'
    ];

    protected $orderStatus = [
        'processing' => 'Processing',
        'complete' => 'Complete',
        'dispute' => 'Dispute',
        'close_dispute' => 'Closed Dispute',
        'canceled' => 'Canceled',
        'echeck' => 'Echeck',
    ];

    protected static function factory(): OrderFactory
    {
        return OrderFactory::new();
    }
    public function paypalAccount()
    {
        return $this->hasOne(PaypalAccount::class, 'id', 'method_account');
    }

    public function stripeAccount()
    {
        return $this->hasOne(StripeAccount::class, 'id', 'method_account');
    }
    public function airwalletAccount()
    {
        return $this->hasOne(AirwalletAccount::class, 'id', 'method_account');
    }

    public function getOrderStatusLabel($status)
    {
        return isset($this->orderStatus[$status]) ? $this->orderStatus[$status] : $status;
    }
}
