<?php

namespace App\Http\Controllers;

use App\Models\LogDeploy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $validator = $request->validate([
            'perpage' => 'nullable|integer',
        ]);
        $perpage = $request->perpage ?? 10;
        $dataLog = [];
        if  ($request->session()->get('login')) {
            $dataLog = LogDeploy::orderBy('id', 'desc')->paginate($perpage);
        }
        return view('home', compact('dataLog'));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        if ($request->username == env('USER_ACCOUNT') && $request->password == env('PASSWORD_ACCOUNT')) {
            $request->session()->put('login', true);
        }
        
        return redirect('/');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('login');
        return redirect('/');
    }
}
