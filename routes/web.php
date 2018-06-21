<?php

use App\Device;
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

Route::get('/', 'DeviceController@index');

Route::resource('device', 'DeviceController');

Route::get('/', function(){
    $newDevice = Device::orderBy('id', 'desc')->first();
    $config['center'] = $newDevice['coordinates'];
    $config['zoom'] = '10';
    $config['map_height'] = '500px';
    $config['scrollwheel'] = false;
    
    GMaps::initialize($config);
    
    //markers
    $devices = Device::all();
    foreach($devices as $key => $value){
        $marker['position'] = $value['coordinates'];
        $marker['infowindow_content'] = "Device Id:".$value['device_id']."<br>". "Place:".$value['place']."<br>". "Address:";
        GMaps::add_marker($marker);
    }

    $map = GMaps::create_map();

    return view('index')->with('map', $map);
});

// Route::get('/directions', function(){
//     $config['zoom'] = '10';
//     $config['map_height'] = '500px';
//     $config['scrollwheel'] = false;
    
//     $config['directions'] = true;
//     $config['directionsStart'] = 'Riga';
//     $config['directionsEnd'] = 'Bauska';
//     $config['directionsDivID'] = 'directionsDiv'; 
//     GMaps::initialize($config);
//     $map = GMaps::create_map();

//     return view('index')->with('map', $map);
// });


Auth::routes();

