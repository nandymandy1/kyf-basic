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

    public function factoryDashboard($req){

      return view('admin.dashboard', ['req' => $req]);

    }

    public function master($req){
      // to fetch all the cutting reports from the datatabse for the admin view

      $factory = Factory::where('id',$req)->get(['name', 'id']);

      $reports['cutting'] = Ckpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();
      $reports['sewing'] = Skpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      $production = DB::table('skpis')->where('factory_id', $req)
      ->select(DB::raw('SUM(prod) as t_prod, MONTH(created_at) as month, YEAR(created_at) as year'))
      ->groupBy(DB::raw('YEAR(created_at) ASC, MONTH(created_at) ASC'))->get();

      $reports['finishing'] = Fkpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      $reports['quality'] = Qkpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      $reports['d_general'] = Gkpi::where('factory_id', $req)->orderBy('created_at', 'DESC')->take(30)->get();

      return response()->json([ 'factory' => $factory, 'reports' => $reports, 'production' => $production]);

      // return view('admin.dashboard', ['reports'=> $reports, 'production' => $production]);

    }


}
