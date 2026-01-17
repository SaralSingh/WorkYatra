<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //this function will display home page
    public function index()
    {
        return view('front.home');
    }

    public function dump()
    {
        dd(Auth::user()->avatar);
    }
}
