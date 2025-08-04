<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
            'success' => false,
            'message' => 'The provided credentials does not match']);
        }
        $token = $user->createToken($user->role, ['server:update'])->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()]);
        }
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->createToken('token')->accessToken;
        return response()->json([
            'success' => true,
            'message' => 'User register successfully.'
        ]);
    }

    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
            'success' => false,
            'message' => 'You are not registered with us.']);
        }
        return response()->json([
            'success' => true,
        ]);
    }
    public function reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
            'success' => false,
            'message' => 'You are not registered with us.']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.'
        ]);
    }
}
