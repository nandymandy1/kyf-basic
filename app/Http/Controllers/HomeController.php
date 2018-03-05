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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // Check if the user is Active or not
      if(Auth::user()->isActive){
        // Check if the user is of type admin or apache_note
        if(Auth::user()->type == 'admin'){

          return view('admin.admin');

        } else {
          // Check for the Job Type of the user
          // Get the factory of the logged in user

          $factory = Factory::find(Auth::user()->factory_id);

          if(Auth::user()->job == 'cutting'){

            $reports = Ckpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();

            if($reports[0]->created_at->isToday()){
              $today = false;
            } else {
              $today = true;
            }

            // return response()->json($reports);
            return view('factory.cutting', ['reports'=> $reports, 'today'=> $today]);

          } elseif (Auth::user()->job == 'sewing') {

            $reports = Skpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();

            $production = DB::table('skpis')->where('factory_id', $factory->id)
            ->select(DB::raw('SUM(prod) as t_prod, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->groupBy(DB::raw('YEAR(created_at) ASC, MONTH(created_at) ASC'))->get();

            if($reports[0]->created_at->isToday()){
              $today = false;
            } else {
              $today = true;
            }

            // return response()->json($today);
            return view('factory.sewing', ['reports'=> $reports, 'today'=> $today, 'production' => $production]);


          } elseif (Auth::user()->job == 'finishing') {

            $reports = Fkpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();

            if($reports[0]->created_at->isToday()){
              $today = false;
            } else {
              $today = true;
            }

             //return response()->json($reports);
            return view('factory.finishing', ['reports'=> $reports, 'today'=> $today]);

          } elseif (Auth::user()->job == 'quality') {

            $reports = Qkpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();

            if($reports[0]->created_at->isToday()){
              $today = false;
            } else {
              $today = true;
            }

            // return response()->json($reports);
            return view('factory.quality', ['reports'=> $reports, 'today'=> $today]);

          } elseif (Auth::user()->job == 'general') {

            $reports = Gkpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();
            $mmr = Skpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get(['sopr', 'kopr','created_at']);

            if($reports[0]->created_at->isToday()){
              $today = false;
            } else {
              $today = true;
            }



            //return response()->json($mmr);
            return view('factory.general', ['reports'=> $reports, 'today'=> $today, 'mmr'=> $mmr]);

          } elseif (Auth::user()->job == 'master') {

            $reports['c'] = Ckpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();
            $reports['s'] = Skpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();
            $reports['q'] = Qkpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();
            $reports['f'] = Fkpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();
            $reports['g'] = Gkpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();
            $reports['b'] = Bkpi::where('factory_id', $factory->id)->orderBy('created_at', 'DESC')->take(30)->get();

            // return view('factory.master',['reports' => $reports]);
            return redirect('/master/cutting');

          } else{

            return view('service.noservice');

          }
        }



      } else {
          return view('service.noauth');
      }
    }
}
