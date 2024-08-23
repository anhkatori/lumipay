<?php

namespace Modules\PayPalManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaypalAccountStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the PayPal accounts for the status.
     */
    public function paypalAccounts()
    {
        return $this->hasMany(PaypalAccount::class);
    }
    
}
