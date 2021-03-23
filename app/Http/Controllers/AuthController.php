<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// TODO: переместить все респонсы в контроллер...
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

  public function resetPassword(Request $request)
  {
    $token = $request->get('token');
    $newPassword = $request->post('new-password');

    $this->userService->validateResetPassword($token, $newPassword);

    $email = DB::table('password_resets')
      ->select('email')
      ->where('token', $token)
      ->value('email');
    $user = User::firstWhere('email', $email);

    $this->userService->resetPassword($user, $newPassword);
  }

  public function viewResetPasswordPage(Request $request)
  {
    $token = $request->get('token');

    $this->userService->validateViewResetPasswordPage($token);
  }

  public function forgotPassword(Request $request)
  {
    $email = $request->post('email');
    $this->userService->validateForgotPassword($email);

    $user = User::firstWhere('email', $email);

    $this->userService->createResetPasswordLink($user);
  }
}
