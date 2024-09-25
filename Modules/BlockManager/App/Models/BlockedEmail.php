<?php

namespace Modules\BlockManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BlockManager\Database\Factories\BlockedEmailFactory;

class BlockedEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'name',
        'money_account',
        'money_bonus',
        'status_lock',
        'status_delete'
    ];

    protected static function factory(): BlockedEmailFactory
    {
        return BlockedEmailFactory::new();
    }

    public function getRouteName()
    {
        return 'blocked-email';
    }
}
