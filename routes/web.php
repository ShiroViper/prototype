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
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', 'AdminController@index')->name('admin-dashboard');
        Route::resource('/users', 'UsersController', [
            'names' => [
                'index' => 'users-index'
            ]
        ]);
        Route::get('/requests/{id}/accept', 'LoanRequestsController@accept');
        Route::get('/requests/{id}/reject', 'LoanRequestsController@reject');
        Route::resource('/requests', 'LoanRequestsController', [
            'names' => [
                'index' => 'admin-requests'
            ]
        ])->except([
            'edit', 'show', 'update'
        ]);
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);
        Route::get('/calendar', 'SchedulesController@index')->name('admin-calendar');
    });

    Route::prefix('member')->group(function () {
        Route::get('/dashboard', 'SchedulesController@index')->name('member-dashboard');
        Route::resource('/requests', 'LoanRequestsController', [
            'names' => [
                'index' => 'member-requests',
                'create' => 'member-create-request'
            ]
        ]);
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);
        Route::view('/transactions','users.admin.dashboard',['active'=>'transactions'])->name('member-transactions');
    });
    
    Route::view('/terms', 'terms', ['active' => 'terms']);
});

// Route::get('/home', 'HomeController@index')->name('home');
