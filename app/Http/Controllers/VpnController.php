<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VpnController extends Controller
{
    public function getDemo()
    {
        $user = Auth::user();


    }
}
