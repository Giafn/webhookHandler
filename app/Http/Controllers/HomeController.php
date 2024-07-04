<?php

namespace App\Http\Controllers;

use App\Models\LogDeploy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $dataLog = LogDeploy::orderBy('id', 'desc')->paginate(5);
        return view('home', compact('dataLog'));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        if ($request->username == 'admin' && $request->password == '123456') {
            $request->session()->put('login', true);
        } 
        
        return redirect('/');
    }
}
