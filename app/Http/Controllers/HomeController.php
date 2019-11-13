<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Home page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }
}
