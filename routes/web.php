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

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::prefix('admin')->middleware('admin-routes')->group(function () {
        Route::get('/dashboard', 'TransactionController@index')->name('admin-dashboard');
        Route::get('/adminTrans', 'TransactionController@adminTransaction')->name('admin-trans');
        Route::resource('/users', 'UsersController', [
            'names' => [
                'index' => 'users-index'
            ]
        ]);
        Route::get('/users/{id}/inactive', 'UsersController@inactive');
        Route::get('/users/{id}/active', 'UsersController@active');
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
        Route::get('/failed', 'TransactionController@failed')->name('collector-failed');
        Route::get('/deliquent', 'TransactionController@deliquent')->name('collector-deliquent');
        Route::resource('/transaction','TransactionController',[
            'names'=>[
                'index'=>'collector-dashboard',
                'create'=>'transaction-collect'
                
            ]
        ]);
        Route::resource('/process', 'ProcessController', [
            'names' => [
                'index' => 'admin-process',
                'create' => 'admin-create',
                'store' => 'admin-process-store'
            ]
        ]);
        Route::get('/calendar', 'SchedulesController@index')->name('admin-calendar');
        
        Route::get('/cancel/{id}/accept', 'MemberController@accept')->name('admin-cancel-accept');
        Route::get('/cancel/{id}/reject', 'MemberController@reject')->name('admin-cancel-reject');
    });

    Route::prefix('member')->group(function () {
        Route::get('/dashboard', 'SchedulesController@index')->name('member-dashboard');
        // When the user has not yet setup his account [The calendar wont show]
        Route::post('/dashboard/setup', 'UsersController@setup')->name('member-setup');
        Route::resource('/requests', 'LoanRequestsController', [
            'names' => [
                'index' => 'member-requests',
                'create' => 'member-create-request',
                'show' => 'member-loan'
            ]
        ]);
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);
        // TWO REDUNDUNDANT REDIRECTS??? : okay na
        Route::get('/transactions', 'TransactionController@index')->name('member-transactions');
        Route::get('/sent/{id}/accept', 'TransactionController@accept')->name('loan-payment-accept');
        Route::get('/sent/{id}/d_accept', 'TransactionController@deposit_accept')->name('deposit-accept');
        
        Route::get('/receive/{id}/accept', 'ProcessController@accept')->name('member-accept');
        // Route::get('/process/{id}/edit', 'ProcessController@col_edit')->name('member-process');
        Route::resource('/process', 'ProcessController', [
            'names' => [
                'index' => 'member-process',
                'create' => 'member-create',
                'store' => 'member-process-store'
            ]
        ]);
        Route::resource('/status', 'StatusController', [
            'names' => [
                'index' => 'member-status'
            ]
        ]);
        Route::get('/cancel', 'MemberController@cancel')->name('member-cancel');
        Route::post('/cancel/archive/', 'MemberController@update')->name('member-cancel-archive');
        Route::get('/cancel/destroy', 'MemberController@destroy')->name('member-cancel-destroy');
    });

    Route::prefix('collector')->group(function () {
        Route::get('/failed', 'TransactionController@failed')->name('collector-failed');
        Route::get('/deliquent', 'TransactionController@deliquent')->name('collector-deliquent');
        
        Route::resource('/transaction','TransactionController',[
            'names'=>[
                // 'index'=>'collector-dashboard',
                'create'=>'transaction-collect' 
            ]
        ]);
        Route::get('/transaction', 'TransactionController@index')->name('collector-dashboard');
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);
        Route::get('/receive/{id}/accept', 'ProcessController@accept')->name('collector-accept');
        Route::get('/process/{id}/edit', 'ProcessController@col_edit')->name('collector-process');
        Route::resource('/process', 'ProcessController', [
            'names' => [
                'index' => 'collector-requests',
                'create' => 'collector-create',
                'store' => 'collector-process-store'
            ]
        ]);
    });


    Route::view('/terms', 'terms', ['active' => 'terms']);
});

// Route::get('/home', 'HomeController@index')->name('home');
