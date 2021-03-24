<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
  public function view($id)
  {
    $user = User::find($id);

    return response([
      'user' => $user
    ]);
  }
}
