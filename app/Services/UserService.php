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
   * Sign up new user and return it.
   * @param $signUpData
   * @return User
   */
  public function signUp($signUpData): User
  {
    $this->validateSignUp($signUpData);

    $userData = [
      'name' => $signUpData['name'],
      'email' => $signUpData['email'],
      'password' => Hash::make($signUpData['password']),
    ];

    $user = User::create($userData);

    return $user;
  }

  /**
   * If the user is not already signed in, validate the credentials (email, password), try to sign them in and
   * return the result.
   * @param $credentials
   * @return bool
   */
  public function signIn($credentials): bool
  {
    $this->validateSignIn($credentials);

    return Auth::attempt($credentials);
  }

  /**
   * Sign user out, i.e. destroy the user's access token and delete its record in the database.
   * @param $signUpData
   * @return void
   */
  public function signOut(): void
  {
    $token = Auth::user()->token();
    $token->revoke();

    Passport::token()->where('user_id', Auth::id())->delete();
  }

  /**
   * Reset user's password by email. Just make a new hash and store it as a new password.
   * @param string $email
   * @param string $newPassword
   * @return void
   */
  public function resetPassword(string $email, string $newPassword): void
  {
    $user = User::firstWhere('email', $email);

    $user->password = Hash::make($newPassword);
    $user->save();
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
        'message' => 'Error trying to reset password.',
        'errors' => $validator->errors(),
      ], Response::HTTP_BAD_REQUEST)->send();
      die();
    }
  }

  /**
   * Validate reset password page request (token must exist).
   * If token doesn't exist, then it is probably a hacker, show error message.
   * @param string|null $token
   * @return void|Response
   */
  public function validateResetPasswordPage(string|null $token)
  {
    $validator = Validator::make(['token' => $token], [
      'token' => 'required|exists:password_resets',
    ]);

    if ($validator->fails()) {
      response([
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
        'message' => 'You have errors in your forgot password form request.',
        'errors' => $validator->errors(),
      ], Response::HTTP_BAD_REQUEST)->send();
      die();
    }
  }
}
