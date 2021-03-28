<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $city = $request->get('city');
    $quantity = $request->get('quantity') ?? 15;
    $priceMin = $request->get('priceMin') ?? 0;
    $priceMax = $request->get('priceMax') ?? 10**9;

    $locations = Location::where('city', 'like', "%${city}%")
      ->where('price', '>=', $priceMin)
      ->where('price', '<=', $priceMax);


    if (! is_null($bedrooms = $request->get('bedrooms'))) {
      $locations->where('bedrooms', '=', $bedrooms);
    }

    return [
      'locations' => $locations->paginate($quantity)
    ];
  }
}
