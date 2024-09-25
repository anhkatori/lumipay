<?php

namespace Modules\Document\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class DocumentController extends Controller
{

    public function index(Request $request)
    {
        return view('document::api.index');
    }
}
