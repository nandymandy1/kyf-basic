<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationData;
use Illuminate\Support\Facades\Storage;
use App\Users;
use App\Models\Bkpi;
use App\Models\Fkpi;
use App\Models\Skpi;
use App\Models\Gkpi;
use App\Models\Ckpi;
use App\Models\Qkpi;


class InputController extends Controller
{
    // Save Today's Cutting Data
    public function saveCutting(Request $req){
      // ValidationData
      $validatedData = $req->validate([
        'cqty' => 'required',
        'tpeople' => 'required',
        'psew' => 'required',
        'factory_id' => 'required',
        'fout' => 'required',
        'pcut' => 'required',
        'pemb' => 'required'
      ]);


      $c = new Ckpi;
      $c->cqty = $req->input('cqty');
      $c->tpeople = $req->input('tpeople');
      $c->factory_id = $req->input('factory_id');
      $c->psew = $req->input('psew');
      $c->pcut = $req->input('pcut');
      $c->pemb = $req->input('pemb');
      $c->fout = $req->input('fout');
      $c->save();

      return redirect('/home')->with('success', 'Cutting Data Saved');
    }


    // Saving Sewing Data
    public function saveSewing(Request $req){
      // ValidationData
      $validatedData = $req->validate([
        'income' => 'required',
        'sopr' => 'required',
        'kopr' => 'required',
        'factory_id' => 'required',
        'prod' => 'required',
        'target' => 'required',
        'actual' => 'required',
        'outcome' => 'required',
        'sam' => 'required',
      ]);


      $c = new Skpi;
      $c->income = $req->input('income');
      $c->sopr = $req->input('sopr');
      $c->factory_id = $req->input('factory_id');
      $c->kopr = $req->input('kopr');
      $c->prod = $req->input('prod');
      $c->target = $req->input('target');
      $c->actual = $req->input('actual');
      $c->outcome = $req->input('outcome');
      $c->sam = $req->input('sam');
      $c->save();

      return redirect('/home')->with('success', 'Sewing Data Saved');
    }

    // Finishing KPI Storage
    // Saving Sewing Data

    public function saveFinishing(Request $req){
      // ValidationData
      $validatedData = $req->validate([
        'income' => 'required',
        'factory_id' => 'required',
        'feed' => 'required',
        'pkd' => 'required',
      ]);


      $c = new Fkpi;
      $c->income = $req->input('income');
      $c->factory_id = $req->input('factory_id');
      $c->pkd = $req->input('pkd');
      $c->feed = $req->input('feed');
      $c->save();

      return redirect('/home')->with('success', 'Finishing Data Saved');
    }

    // Save QUality Data
    public function saveQuality(Request $req){
      // ValidationData
      $validatedData = $req->validate([
        'inspected' => 'required',
        'factory_id' => 'required',
        'failed' => 'required',
      ]);


      $c = new Qkpi;
      $c->inspected = $req->input('inspected');
      $c->factory_id = $req->input('factory_id');
      $c->failed = $req->input('failed');
      $c->save();
      return redirect('/home')->with('success', 'Quality Data Saved');
    }

    // Save Daily General data
    public function saveGeneral(Request $req){
      // ValidationData
      $validatedData = $req->validate([
        'inspected' => 'required',
        'factory_id' => 'required',
        'failed' => 'required',
      ]);

      $c = new Gkpi;
      $c->inspected = $req->input('inspected');
      $c->factory_id = $req->input('factory_id');
      $c->failed = $req->input('failed');
      $c->save();
      return redirect('/home')->with('success', 'Quality Data Saved');
    }


}
