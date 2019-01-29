<?php

namespace BetterFly\Skeleton\App\Http\Controllers\Admin;

use BetterFly\Skeleton\App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check())
            return redirect(route('dashboard'));

        return view('betterfly::admin.login');
    }
}
