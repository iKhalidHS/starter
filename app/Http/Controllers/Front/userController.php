<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class userController extends Controller
{

    public function showUserName() {
        return 'khalid hassan';
    }
}
