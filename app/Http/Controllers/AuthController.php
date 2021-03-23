<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
  private UserService $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function signUp(Request $request)
  {
    $user = $this->userService->signUp($request->post());
    $token = $user->createToken('access_token')->accessToken;

    return response([
      'message' => 'You have been successfully signed up.',
      'user' => $user,
      'token' => $token
    ]);
  }

  public function signIn(Request $request)
  {
    $credentials = [
      'email' => $request->post('email'),
      'password' => $request->post('password')
    ];

    $userSignedIn = $this->userService->signIn($credentials);

    if (! $userSignedIn) {
      return response([
        'message' => 'Name and password mismatch. Try using different name or password.'
      ], Response::HTTP_FORBIDDEN);
    }

    /** @var User $user */
    $user = Auth::user();
    $token = $user->createToken('access_token')->accessToken;

    return response([
      'message' => "You have been successfully signed in. Welcome, {$user->name}.",
      'user' => $user,
      'token' => $token
    ]);
  }

  public function signOut()
  {
    $this->userService->signOut();

    return response([
      'message' => 'You have been successfully signed out.'
    ]);
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

    $this->userService->resetPassword($email, $newPassword);

    return response([
      'message' => 'Password has been reset successfully.',
    ]);
  }

  public function resetPasswordPage(Request $request)
  {
    $token = $request->get('token');

    $this->userService->validateResetPasswordPage($token);

    return response([
      'message' => 'You may now proceed with resetting your password.'
    ]);
  }

  public function forgotPassword(Request $request)
  {
    $email = $request->post('email');
    $this->userService->validateForgotPassword($email);

    $user = User::firstWhere('email', $email);

    $this->userService->createResetPasswordLink($user);
  }
}
