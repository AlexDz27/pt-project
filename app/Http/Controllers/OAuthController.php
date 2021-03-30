<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
  private UserService $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function google(Request $request)
  {
    $user = User::where('email', $request->post('email'))->whereNotNull('google_id')->first();
    if ((bool) $user) {
      $token = $user->createToken('access_token')->accessToken;

      return response([
        'message' => "You have been successfully signed in. Welcome, {$user->name}.",
        'user' => $user,
        'token' => $token
      ]);
    } else {
      $user = $this->userService->signUp($request->post());

      $token = $user->createToken('access_token')->accessToken;

      return response([
        'message' => 'You have been successfully signed up.',
        'user' => $user,
        'token' => $token
      ]);
    }
  }
}
