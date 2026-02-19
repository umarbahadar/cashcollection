<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminDashboardHelper {

    public static function index()
    {
        return view('admin.dashboard');
    }

}