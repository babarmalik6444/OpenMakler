<?php

use Illuminate\Support\Facades\Route;

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

// Homepage
Route::get('/', function () {
    return view('homepage');
});


// Public routes
Route::get('/immo/{realEstate}/{slug}', function(\App\Models\Openimmo\RealEstate $realEstate) {
    if(!$realEstate->isActive()) {
        abort(\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
    }
    if($realEstate->getUrl()  != URL::current()) {
        return redirect($realEstate->getUrl());
    }

    $images = $realEstate->getImages()->map(function(\App\Models\Openimmo\Anhang $anhang) {
        return [
            "title" => $anhang->anhangtitel,
            "src" => $anhang->getUrl(),
            "gruppe" => $anhang->gruppe
        ];
    });

    return view("immo.realestate.detail", [
        "realEstate" => $realEstate,
        "images" => $images
    ]);
});


// App routes
Route::middleware(config('filament.middleware.auth'))->prefix("app")->name("test.")->group(function() {
    // Tests
    Route::prefix("test")->name("test.")->group(function(){
        Route::get('immo', function(){
            dd(1);
        })->name('immo');

        Route::get('wireui', function () {
            return view('test.wireui');
        });
    });
});

Route::get('verify/code', function () {
    return redirect();
});
