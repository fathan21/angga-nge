<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class NotifController extends ApiController
{
    
    public function __construct( Request $request)
    {
        
        $this->parseRequest($request);
    }

    public function index(Request $request)
    {
        $result = [
            
        ];
        return $this->success($result);
    }
    
}
