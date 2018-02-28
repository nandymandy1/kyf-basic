<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factory;
use Auth;
use PharIo\Manifest\Type;
use App\Users;
use App\Models\Bkpi;
use App\Models\Fkpi;
use App\Models\Skpi;
use App\Models\Gkpi;
use App\Models\Ckpi;
use App\Models\Qkpi;
use Illuminate\Support\Facades\DB;

class AdminFactoryController extends Controller
{
    //
    public function cutting($req){
      // to fetch all the cutting reports from the datatabse for the admin view
      $reports = Ckpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      return response()->json($reports);
    }

    public function sewing($req){
      // to fetch all the cutting reports from the datatabse for the admin view
      $reports = Skpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      $production = DB::table('skpis')->where('factory_id', $req)
      ->select(DB::raw('SUM(prod) as t_prod, MONTH(created_at) as month, YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at) ASC, MONTH(created_at) ASC'))->get();

      return response()->json([$reports, $production]);
    }

    public function finishing($req){
      // to fetch all the cutting reports from the datatabse for the admin view
      $reports = Fkpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      return response()->json($reports);
    }

    public function quality($req){
      // to fetch all the cutting reports from the datatabse for the admin view
      $reports = Qkpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      return response()->json($reports);
    }

    public function general($req){
      // to fetch all the cutting reports from the datatabse for the admin view
      $reports = Gkpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      return response()->json($reports);
    }

    public function fetchFactories(){
      $factories = Factory::orderBy('name', 'ASC')->get();
      return response()->json($factories);
    }

    public function destroyFactory(Request $req){
      Factory::where('id', $req)->delete();
    }


}
