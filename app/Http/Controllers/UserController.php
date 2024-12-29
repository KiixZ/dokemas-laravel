<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function updateRole(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'role' => 'required|in:user,staff,admin',
        ]);

        $user->update(['role' => $validatedData['role']]);
        return response()->json($user, 200);
    }
}