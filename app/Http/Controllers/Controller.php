<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function validateLimit($limit)
    {
        $limit = intval($limit); 
        if ($limit < 1) {
            $limit = 10; 
        }
        if ($limit > 100) {
            $limit = 100; 
        }

        return $limit;
    }
}
