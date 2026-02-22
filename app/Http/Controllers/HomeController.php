<?php

namespace App\Http\Controllers;

use App\Models\Skate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $skates = Skate::all();
        return view('welcome', compact('skates'));
    }
}
