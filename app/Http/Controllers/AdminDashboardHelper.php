<?php

namespace App\Helpers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminDashboardHelper {

    public static function index()
    {
        return view('admin.home');
    }

}