<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class UserService
{
  const PASSWORD_MIN_CHARS = 3;

  /**
   * If the user is not logged in, send access token to the user so that they could proceed with the app.
   * @param $credentials
   * @return Response
   */
  public function signIn($credentials): Response
  {
    if (Auth::check()) {
      return response([
        'success' => false,
        'message' => 'You are already signed in.'
      ])->send();
      die();
    }

    $this->validateSignIn($credentials);

    if (Auth::attempt($credentials)) {
      /** @var User $user */
      $user = Auth::user();

      $token = $user->createToken('Laravel Password Grant Client')->accessToken;

      return response([
        'success' => true,
        'message' => "You have been successfully signed in. Welcome, {$user->name}.",
        'user' => $user,
        'token' => $token
      ])->send();
    }

    return response([
      'success' => false,
      'message' => 'Name or password mismatch. Try using different name / password.'
    ])->send();
  }

  /**
   * Sign up new user and send access token to the user so that they could proceed with the app.
   * @param $signUpData
   * @return Response
   */
  public function signUp($signUpData): Response
  {
    $this->validateSignUp($signUpData);

    $userData = [
      'name' => $signUpData['name'],
      'email' => $signUpData['email'],
      'password' => Hash::make($signUpData['password']),
    ];

    $user = User::create($userData);

    $token = $user->createToken('Laravel Password Grant Client')->accessToken;

    return response([
      'success' => true,
      'message' => 'You have been successfully signed up.',
      'user' => $user,
      'token' => $token
    ])->send();
  }

  /**
   * Sign user out. Destroy the user's access token.
   * @param $signUpData
   * @return Response
   */
  public function signOut(): Response
  {
    $token = Auth::user()->token();
    $token->revoke();

    Passport::token()->where('user_id', Auth::id())->delete();

    return response([
      'success' => true,
      'message' => 'You have been successfully signed out.'
    ])->send();
  }

  public function resetPassword(User $user, string $newPassword)
  {
    $user->password = Hash::make($newPassword);
    $user->save();

    return response([
      'success' => true,
      'message' => 'Password reset successfully.',
      'new_pass' => $user->password
    ])->send();
    die();
  }

  /**
   * Create reset password link (it should be sent to the user's email).
   * Then user follows the link and is able to change their password.
   * @param User $user
   * @return Response
   */
  public function createResetPasswordLink(User $user): Response
  {
    $token = base64_encode(Hash::make($user->email));

    $link = config('app.url') . '/api/auth/reset-password/link?' . http_build_query([
      'token' => $token
    ]);

    DB::table('password_resets')->insert([
      'email' => $user->email,
      'token' => $token
    ]);

    return response([
      'success' => true,
      'message' => 'Here\'s your link for password reset.',
      'link' => $link
    ])->send();
  }

  /**
   * Validate sign-up data (name, email, password). If incorrect, show error message.
   * @param $signUpData
   * @return void|Response
   */
  public function validateSignUp($signUpData)
  {
    $validator = Validator::make($signUpData, [
      'name' => 'required|unique:users',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:' . self::PASSWORD_MIN_CHARS
    ]);

    if ($validator->fails()) {
      response([
        'message' => 'You have errors in your sign-up form request.',
        'errors' => $validator->errors(),
      ], Response::HTTP_UNPROCESSABLE_ENTITY)->send();
      die();
    }
  }

  /**
   * Validate sign-in data (email, password). If incorrect, show error message.
   * @param $signInData
   * @return void|Response
   */
  public function validateSignIn($signInData)
  {
    $validator = Validator::make($signInData, [
      'email' => 'required|email',
      'password' => 'required|min:' . self::PASSWORD_MIN_CHARS
    ]);

    if ($validator->fails()) {
      response([
        'message' => 'You have errors in your sign-in form request.',
        'errors' => $validator->errors(),
      ], Response::HTTP_UNPROCESSABLE_ENTITY)->send();
      die();
    }
  }

  public function validateResetPassword(string|null $token, string $newPassword)
  {
    $validator = Validator::make(['token' => $token, 'new-password' => $newPassword], [
      'token' => 'required|exists:password_resets',
      'new-password' => 'required|min:' . self::PASSWORD_MIN_CHARS,
    ]);

    if ($validator->fails()) {
      response([
        'success' => false,
        'message' => 'Error trying to reset password.',
        'errors' => $validator->errors(),
      ], Response::HTTP_BAD_REQUEST)->send();
      die();
    }
  }

  /**
   * Validate view reset password page request (token must exist).
   * If token doesn't exist, then it is probably a hacker, show error message.
   * @param string|null $token
   * @return void|Response
   */
  public function validateViewResetPasswordPage(string|null $token)
  {
    $validator = Validator::make(['token' => $token], [
      'token' => 'required|exists:password_resets',
    ]);

    if ($validator->fails()) {
      response([
        'success' => false,
        'message' => 'Such a password reset token doesn\'t exist. Are you a script kiddie?',
        'errors' => $validator->errors(),
      ], Response::HTTP_BAD_REQUEST)->send();
      die();
    }
  }

  /**
   * Validate forgot password request (email must exist). If email doesn't exist, show error message.
   * @param string|null $email
   * @return void|Response
   */
  public function validateForgotPassword(string|null $email)
  {
    $validator = Validator::make(['email' => $email], [
      'email' => 'required|email|exists:users',
    ]);

    if ($validator->fails()) {
      response([
        'success' => false,
        'message' => 'You have errors in your forgot password form request.',
        'errors' => $validator->errors(),
      ], Response::HTTP_BAD_REQUEST)->send();
      die();
    }
  }
}
