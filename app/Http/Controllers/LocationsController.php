<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationsController extends Controller
{
  public function show($id) {
    return Location::find($id);
  }
}
