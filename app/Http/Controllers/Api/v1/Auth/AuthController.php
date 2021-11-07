<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {


        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
        ]);


        $user = resolve(UserRepository::class)->create($request);


        $defaultSuperAdminEmail = config('permission.default_super_admin_email');
        $user->email === $defaultSuperAdminEmail ? $user->assignRole('Super Admin') : $user->assignRole('User');

        return response()->json([
            'message' => 'User Created successfully'
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(Auth::user(), Response::HTTP_OK);
        }

        throw ValidationException::withMessages([
            'email' => 'incorrect credentials.'
        ]);
    }


    public function user()
    {
        return response()->json(Auth::user(), Response::HTTP_OK);
    }

    public function logout()
    {

        Auth::logout();

        return response()->json([
            'message' => 'Logout successfully'
        ], Response::HTTP_CREATED);
    }
}
