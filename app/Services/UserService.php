<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class UserService
{
  const PASSWORD_MIN_CHARS = 3;

  const VALIDATION_FAILURE_CODE = 422;

  /**
   * If the user is not logged in, send access token to the user so that they could proceed with the app.
   * @param $credentials
   * @return Response
   */
  public function signIn($credentials)
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
  public function signUp($signUpData)
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
  public function signOut()
  {
    $token = Auth::user()->token();
    $token->revoke();

    Passport::token()->where('user_id', Auth::id())->delete();

    return response([
      'success' => true,
      'message' => 'You have been successfully signed out.'
    ])->send();
  }

  /**
   * Validate sign-up data (name, email, password). If incorrect, show error message.
   * @param $signUpData
   * @return null|Response
   */
  private function validateSignUp($signUpData)
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
      ], self::VALIDATION_FAILURE_CODE)->send();
      die();
    }
  }

  /**
   * Validate sign-in data (email, password). If incorrect, show error message.
   * @param $signInData
   * @return null|Response
   */
  private function validateSignIn($signInData)
  {
    $validator = Validator::make($signInData, [
      'email' => 'required|email',
      'password' => 'required|min:' . self::PASSWORD_MIN_CHARS
    ]);

    if ($validator->fails()) {
      response([
        'message' => 'You have errors in your sign-in form request.',
        'errors' => $validator->errors(),
      ], self::VALIDATION_FAILURE_CODE)->send();
      die();
    }
  }
}
