<?php

namespace Modules\PayPalManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ClientManager\App\Models\Client;
use Modules\PayPalManager\Database\Factories\PaypalAccountFactory;
class PaypalAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 
        'password', 
        'domain_site_fake', 
        'max_receive_amount', 
        'active_amount', 
        'hold_amount', 
        'max_order_receive_amount', 
        'proxy', 
        'days_stopped', 
        'status_id', 
        'description', 
        'payment_method',
        'client_id'
    ];

    /**
     * Get the status associated with the PayPal account.
     */
    public function status()
    {
        return $this->belongsTo(PaypalAccountStatus::class, 'status_id');
    }

    public static function getPaymentMethods(){
        return [
            'site_fake' => 'Site Fake',
            'invoice' => 'Invoice'
        ];
    }

    public function getPaymentMethod(){
        return $this->getPaymentMethods()[$this->payment_method];
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public static function factory(): PaypalAccountFactory
    {
        return new PaypalAccountFactory();
    }
    
}
