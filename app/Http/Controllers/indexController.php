<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class indexController extends Controller
{
    //
    public function index()
    {
        return view('index');
    }

    /**
     * about
     * ---
     * This is for the about page
     * 
     */
    public function about()
    {
        return view('index.about');
    }

    /**
     * contact
     * ---
     * This is for the contact page
     * 
     */
    public function contact()
    {
        return view('index.contact');
    }
}
