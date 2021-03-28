<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
  public function show($id)
  {
    $user = User::find($id);

    return response([
      'user' => $user
    ]);
  }
}
