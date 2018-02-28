<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factory;

class OutputController extends Controller
{
      // Factory Controller to return all the list of factories to the register page
      public function fetchFactory(Request $req) {
      $factory = Factory::all('name', 'id');
       return response()->json($factory);
      }

}
