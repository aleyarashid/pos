<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HomeController extends Controller
{
    //Home Page
    public function index()
    {
        return Inertia::render('HomePage');
    }

   
}


