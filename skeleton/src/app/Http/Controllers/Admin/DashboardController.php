<?php

namespace BetterFly\Skeleton\App\Http\Controllers\Admin;

use BetterFly\Skeleton\App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('betterfly::admin.dashboard');
    }
}
