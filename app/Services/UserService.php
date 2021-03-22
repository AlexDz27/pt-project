<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
  const PASSWORD_MIN_CHARS = 3;

  const VALIDATION_FAILURE_CODE = 422;

  /**
   * Send access token to the user so that they could proceed with the app.
   * @param $credentials
   * @return Response
   */
  public function signIn($credentials)
  {
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
   * Sign up and return new user.
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

  public function signOut()
  {
    if (Auth::check()) {
      $token = Auth::user()->token();
      $token->revoke();

      return response([
        'success' => true,
        'message' => 'You have been successfully signed out.'
      ])->send();
    }

    return response([
      'success' => false,
      'message' => 'You are already signed in.'
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
      return response([
        'message' => 'You have errors in your sign-up form request.',
        'errors' => $validator->errors(),
      ], self::VALIDATION_FAILURE_CODE)->send();
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
      return response([
        'message' => 'You have errors in your sign-in form request.',
        'errors' => $validator->errors(),
      ], self::VALIDATION_FAILURE_CODE)->send();
    }
  }
}
