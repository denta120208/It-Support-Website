<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        session()->forget('menuParentActive');
        session()->forget('menuSubActive');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->__construct();
        session(['menuParentActive' => "homepage"]);
        return view("home");
    }
    
    public function adminHome(){
        return view('adminHome');
    }
}
