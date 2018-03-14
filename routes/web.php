<?php
use Illuminate\Notifications\RoutesNotifications;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Fetch Factory for register
Route::get('/factorylist', 'OutputController@fetchFactory');

Route::prefix('/factory')->group(function(){
  // Cutting Data Routes
  Route::get('/cuttinginput', function(){
    return view('factory.forms.cutting');
  });
  Route::post('/cutting/data', 'InputController@saveCutting');
  // Sewing Data Routes
  Route::get('/sewinginput', function(){
    return view('factory.forms.sewing');
  });
  Route::post('/sewing/data', 'InputController@saveSewing');
  // Finishing Data
  Route::get('finishinginput', function(){
    return view('factory.forms.finishing');
  });
  Route::post('/finish/data', 'InputController@saveFinishing');
  // Quality Routes
  Route::get('qualityinput', function(){
    return view('factory.forms.quality');
  });
  Route::post('/quality/data', 'InputController@saveQuality');
  // General Routes
  Route::get('generalinput', function(){
    return view('factory.forms.general');
  });
  Route::post('/general/data', 'InputController@saveGeneral');
});



// MASTER ROUTES
Route::prefix('/master')->group(function(){
  Route::get('/cutting', 'FactoryController@cutting');
  Route::get('/sewing', 'FactoryController@sewing');
  Route::get('/finishing', 'FactoryController@finishing');
  Route::get('/quality', 'FactoryController@quality');
  Route::get('/general', 'FactoryController@general');
});


// Admin Routes
Route::prefix('/admin')->group(function () {

  // View reports routes for the factories
  Route::get('/factory/master/{id}', 'OutputController@factoryDashboard');
  Route::post('/factory/reports/{req}', 'OutputController@master');
  // For the Crisp Industry Report
  Route::post('/factory/reportsmaster/{req}', 'OutputController@maindashboard');

  Route::post('/factory', 'FactController@store');
  Route::resource('/factory', 'FactController');
  Route::get('/factory/endis/{id}', 'FactController@enable_disable');
  Route::post('/factories', 'FactController@getFactory');
  Route::get('/user/endis/{id}', 'FactController@endisUser');

  Route::get('/users', function(){
    return view('admin.users');
  });

  Route::post('/usersfetch', 'FactController@getUsers');
});

// Testing of the Pages routes
Route::get('/test', function() {
  return view('factory.master');
});
