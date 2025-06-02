<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }
    public function dashboard(Request $request)
    {
        $data = Auth::user();
        return view('dashboard', ['data' => $data]);
    }


    public function store(Request $request)
    {

        $id = $request->id;
        $emailRule = 'required|email|unique:admin,email';
        if ($id) {
            $emailRule = 'required|email|unique:admin,email,' . $id;
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'country' => 'required',
            'password' => 'required|string|',
        ]);

        if (!empty($id)) {
            $data = Admin::find($id);
            if ($data) {
                $data->name = $request->name;
                $data->email = $request->email;
                $data->country = $request->country;
                $data->role = "user";
                $data->password = bcrypt($request->password);
                $data->save();
            }
        } else {
            $data = new Admin();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->role = "user"; 

            $data->password = bcrypt($request->password);
            $data->save();
        }
        Auth::login($data);

        return response()->json(['success' => true]);
    }



    public function checkadmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = Admin::where('email', '=', $request->email)->first();

        if ($user && Hash::check($request->password, hashedValue: $user->password)) {
            Auth::login($user);
            // return redirect()->route('dashboard', ['data' => $user]);
            if ($user->role === 'user') {
                return response()->json(['success' => true, 'message' => 'Login successful','redirect' => 'user']);
            }else if ($user->role === 'admin') {
                return response()->json(['success' => true, 'message' => 'Login successful','redirect' => 'admin']);
            } 
            else {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            } 
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ], 422);
    }





    public function delete(Request $request)
    {
        $id = $request->id;
        $data = Admin::find($id);
        if ($data) {
            $data->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 404);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

}
