<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Services\RegisterService;
use App\Traits\AuthenticatorTrait;

class AuthController extends BaseController
{
    use AuthenticatorTrait;
    
    public function __construct(
        protected RegisterService $registerService
    )
    {
    }

}
