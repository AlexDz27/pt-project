<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
  const PASSWORD_MIN_CHARS = 3;

  const VALIDATION_FAILURE_CODE = 422;

  /**
   * Create new user.
   * @param $signUpData
   * @return User
   */
  public function create($signUpData)
  {
    $userData = [
      'name' => $signUpData['name'],
      'email' => $signUpData['email'],
      'password' => Hash::make($signUpData['password']),
    ];

    /**
     * @var $user User
     */
    $user = User::create($userData);

    return $user;
  }

  /**
   * Validate sign-up data (name, email, password). If incorrect, show error message.
   * @param $signUpData
   * @return null|Response
   */
  public function validateSignUp($signUpData)
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
  public function validateSignIn($signInData)
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
