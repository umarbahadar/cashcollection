<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Directly return the Blade view
        return view('home');
    }
}