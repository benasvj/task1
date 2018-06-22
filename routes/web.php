<?php

use App\Device;
use maxh\Nominatim\Nominatim;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
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
    
    $devices = Device::all();
    foreach($devices as $key => $value){
        //address extraction
        $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2";
        $nominatim = new Nominatim($url);
        $gps = list($lat, $lon) = explode(',', $value['coordinates']);
        $lat = floatval($lat);
        $lon = floatval($lon);
        $reverse = $nominatim->newReverse()
                ->latlon($lat, $lon);
    
        $address = $nominatim->find($reverse);
        if(sizeOf($address)<=2){ //laikinai. galima geriau isspresti
            $addressInfo = "Error. Coordinates could be set incorrectly!";
        }else{
            $addressInfo = $address["display_name"];
        };
        //markers
        $marker['position'] = $value['coordinates'];
        //markers info
        $marker['infowindow_content'] = "Device Id:".$value['device_id']."<br>". "Place:".$value['place']."<br>". "Address:".$addressInfo;
        GMaps::add_marker($marker);
    }

    $map = GMaps::create_map();

    //checking if recently added device was "work" to send mail (cia neproduktyvus kodas ir reiktu daryti atskirame controlerio metode,nes spamins kiekviena karta. paliksiu taip kolkas nes darau vienam lange viska)
    if($newDevice['place']==="work"){
        $data = array('device_id'=>$newDevice['device_id'], 'address'=>$addressInfo);
        Mail::send('email', $data, function($message){
            $message->to('b.vajega@gmail.com', 'Device Web')
                    ->subject('Testing Mail');
        });
    }

    return view('index', [
        'map'=>$map,
        'devices'=>$devices
    ]);
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

