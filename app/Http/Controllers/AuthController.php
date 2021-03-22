<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  private UserService $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function signUp(Request $request)
  {
    $this->userService->signUp($request->post());
  }

  public function signIn(Request $request)
  {
    $credentials = [
      'email' => $request->post('email'),
      'password' => $request->post('password')
    ];

    $this->userService->signIn($credentials);
  }

  public function signOut()
  {
    $this->userService->signOut();
  }
}
