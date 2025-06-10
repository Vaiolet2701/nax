<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
public function index()
{
    $users = User::where('role', 'user')->paginate(10);
    $teachers = User::where('role', 'teacher')->get();

    return view('admin.users.index', compact('users', 'teachers'));
}

}