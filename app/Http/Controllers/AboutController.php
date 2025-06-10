<?php

namespace App\Http\Controllers;

use App\Models\User;

class AboutController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('about', compact('teachers'));
    }
}
