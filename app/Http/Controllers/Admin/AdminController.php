<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function register()
    {
        return view('admin.register');
    }

    public function dashboard()
    {
        $data = Admin::all();
        return view('admin.dashboard', ['data' => $data]);
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

        $data = Admin::find($id);

        if (isset($id) && $data) {
            $data->name = $request->name;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->role = "admin"; // <-- assign role


            if ($request->filled('password')) {
                $data->password = bcrypt($request->password);
            }
            $data->save();
        } else {
            $data = new Admin();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->country = $request->country;
            $data->role = "admin";
            $data->password = bcrypt($request->password);
            $data->save();
        }

        // return redirect()->route('admin.dashboard');
        return response()->json(['success' => true, 'message' => 'Data saved successfully', 'data' => $data]);

    }


    public function checkadmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $data = Admin::where('email', $request->email)->first();


        if ($data && Hash::check($request->password, $data->password)) {
            Auth::login($data);
            if ($data->role === 'admin') {
                return response()->json(['success' => true, 'message' => 'Login successful']);
            }else{
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }
        }
        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
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
        return redirect('/admin');
    }
}
