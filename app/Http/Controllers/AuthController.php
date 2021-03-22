<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function signUp(Request $request, UserService $userService)
  {
    $userService->validateSignUp($request->post());

    $user = $userService->create($request->post());
    $token = $user->createToken('Laravel Password Grant Client')->accessToken;

    return response([
      'success' => true,
      'message' => 'You have been successfully signed up.',
      'user' => $user,
      'token' => $token
    ]);
  }

  public function signIn(Request $request, UserService $userService)
  {
    $userService->validateSignIn($request->post());

    /**
     * @var $user User
     */
    if (Auth::attempt($request->post())) {
      $user = Auth::user();

      $token = $user->createToken('Laravel Password Grant Client')->accessToken;

      return response([
        'success' => true,
        'message' => "You have been successfully signed in. Welcome, {$user->name}.",
        'user' => $user,
        'token' => $token
      ]);
    } else {
      return response([
        'success' => false,
//        'message' => 'Such a user doesn\'t exist'
        'message' => 'No match in our records.'
      ]);
    }
  }

  public function signOut(Request $request)
  {
    $token = $request->user()->token();
    $token->revoke();

    return response([
      'success' => true,
      'message' => 'You have been successfully signed out.'
    ]);
  }
}
