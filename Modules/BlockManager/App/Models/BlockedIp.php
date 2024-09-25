<?php

namespace Modules\BlockManager\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BlockManager\Database\Factories\BlockedIpFactory;

class BlockedIp extends Model
{
    use HasFactory;
    protected $fillable = [
        'ip_ban', 
        'sort_ip' 
    ];

    protected static function factory(): BlockedIpFactory
    {
        return BlockedIpFactory::new();
    }

    public function getRouteName(){
        return 'blocked-ip';
    }
}
