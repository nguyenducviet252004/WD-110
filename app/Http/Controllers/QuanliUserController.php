<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class QuanliUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateUser($request);

        $user = User::create([
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']), // Sử dụng Hash::make()
            'email' => $validatedData['email'],
            'fullname' => $request->input('fullname'),
            'birth_day' => $request->input('birth_day'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'role' => $request->input('role', 0),
            'is_active' => $request->input('is_active', 1),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $this->validateUser($request, $id);

        $user->username = $validatedData['username'] ?? $user->username;
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']); // Sử dụng Hash::make()
        }
        $user->email = $validatedData['email'] ?? $user->email;
        $user->fullname = $request->input('fullname', $user->fullname);
        $user->birth_day = $request->input('birth_day', $user->birth_day);
        $user->phone = $request->input('phone', $user->phone);
        $user->address = $request->input('address', $user->address);
        $user->role = $request->input('role', $user->role);
        $user->is_active = $request->input('is_active', $user->is_active);

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    // Phương thức validate chung cho store và update
    private function validateUser(Request $request, $id = null)
    {
        $uniqueEmail = 'unique:users,email' . ($id ? ",$id" : '');
        $uniqueUsername = 'unique:users,username' . ($id ? ",$id" : '');

        return $request->validate([
            'username' => "required|max:255|$uniqueUsername",
            'password' => 'sometimes|required|min:6',
            'email' => "required|email|$uniqueEmail",
        ]);
    }


}