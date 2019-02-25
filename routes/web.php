<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::middleware(['guest'])->group(function () {
    Route::view('/', 'welcome', ['active' => 'welcome']);
    Route::view('/about', 'about', ['active' => 'about']);
    Route::view('/terms', 'terms', ['active' => 'terms']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'AdminController@index')->name('admin-dashboard');
    Route::resource('/users', 'UsersController', [
        'names' => [
            'index' => 'users-index'
        ]
    ]);
});

// Route::get('/home', 'HomeController@index')->name('home');
